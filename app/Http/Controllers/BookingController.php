<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        return $this->success(
            Booking::all()
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|integer',
            'order_detail_id' => 'required|integer',
            'class_id' => 'required|integer',
            'trainer_id' => 'required|integer',
            'schedule_id' => 'required|integer',
            'booking_date' => 'required|date',
            'status' => 'required|string'
        ]);

        $booking = Booking::create($data);
        return $this->success($booking, 'Booking Created Successfuly', 201);
    }

    public function show(Booking $booking)
    {
        return $this->success($booking);
    }

    public function update(Request $request, Booking $booking)
    {
        $data = $request->validate([
            'user_id' => 'sometimes|integer',
            'order_detail_id' => 'sometimes|integer',
            'class_id' => 'sometimes|integer',
            'trainer_id' => 'sometimes|integer',
            'schedule_id' => 'sometimes|integer',
            'booking_date' => 'sometimes|date',
            'status' => 'sometimes|string',
            'is_active' => 'sometimes|boolean'
        ]);
        
        $booking->update($data);
        return $this->success($booking, 'Booking Updated Successfully', 200);
    }

    public function destroy(Booking $booking)
    {
        $booking->update(['is_active' => false]);
        return response()->noContent();
    }
}
