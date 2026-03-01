<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->success(
            Order::all()
        );
    }

    public function create()
    {
   
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id'                       => 'required|integer',
            'user_name'                     => 'required|string',
            'total_amount'                  => 'required|numeric|min:0',

            'order_details'                 => 'required|array|min:1',
            'order_details.*.package_id'    => 'required|integer',
            'order_details.*.class_id'      => 'required|integer',
            'order_details.*.package_name'  => 'required|string',
            'order_details.*.class_name'    => 'required|string',
            'order_details.*.total_quota'   => 'required|integer|min:1',
            'order_details.*.used_quota'    => 'nullable|integer|min:0',
        ]);

        DB::beginTransaction();

        try {
            // Generate Order No
            $orderNo = $this->generateOrderNo();

            // Create Order
            $order = Order::create([
                'user_id'      => $data['user_id'],
                'user_name'    => $data['user_name'],
                'order_no'     => $orderNo,
                'total_amount' => $data['total_amount'],
                'order_date'   => now(),
                'status'       => 'inactive',
            ]);

            // Create Order Details
            foreach ($data['order_details'] as $detail) {
                OrderDetail::create([
                    'order_id'        => $order->id,
                    'package_id'      => $detail['package_id'],
                    'class_id'        => $detail['class_id'],
                    'package_name'    => $detail['package_name'],
                    'class_name'      => $detail['class_name'],
                    'total_quota'     => $detail['total_quota'],
                    'used_quota'      => $detail['used_quota'] ?? 0,
                    'remaining_quota' => $detail['total_quota'] - ($detail['used_quota'] ?? 0),
                ]);
            }

            DB::commit();

            return $this->success(
                $order->load('orderDetails'),
                'Order berhasil dibuat',
                201
            );

        } catch (\Exception $e) {
            DB::rollBack();

            return $this->error(
                $e->getMessage(),
                500
            );
        }
    }


   public function myTransaction($userId)
    {
        try {
            $orders = Order::with('orderDetails')
                ->where('user_id', $userId)
                ->orderBy('order_date', 'desc')
                ->get();

            if ($orders->isEmpty()) {
                return $this->error(
                    'Transaksi tidak ditemukan',
                    404
                );
            }

            return $this->success(
                $orders,
                'Berhasil mengambil transaksi',
                200
            );

        } catch (\Exception $e) {
            return $this->error(
                $e->getMessage(),
                500
            );
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
    try {

        if ($order->status === 'active') {
            return $this->error(
                'Order sudah active',
                400
            );
        }

        $order->update([
            'status' => 'active'
        ]);

        return $this->success(
            $order,
            'Order berhasil di activekan',
            200
        );

    } catch (\Exception $e) {
            return $this->error(
                $e->getMessage(),
                500
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }

    private function generateOrderNo()
    {   
        $prefix = 'MRG';
        $date   = now()->format('ym'); // contoh: 2402

        $lastOrder = Order::where('order_no', 'like', $prefix . $date . '%')
            ->orderBy('order_no', 'desc')
            ->lockForUpdate() // 🔒 anti duplicate pas concurrent
            ->first();

        $lastNumber = $lastOrder
            ? (int) substr($lastOrder->order_no, -2)
            : 0;

        $newNumber = str_pad($lastNumber + 1, 2, '0', STR_PAD_LEFT);

        return $prefix . $date . $newNumber;
    }
}
