<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\OrderDetail;
use App\Models\Schedule;
use App\Models\CourseClass;
use App\Models\UserManagement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class BookingController extends ApiController
{
    public function index()
    {
        return $this->success(
            Booking::all()
        );
    }

    public function store(Request $request, OrderDetail $orderDetail, Schedule $schedule)
    {
        $data = $request->validate([
            'user_id' => 'required|integer',
            'order_detail_id' => 'required|integer|exists:order_details,id',
            'class_id' => 'required|integer|exists:course_classes,id',
            'trainer_id' => 'required|integer|exists:trainers,id',
            'schedule_id' => 'required|integer|exists:schedules,id',
            'booking_date' => 'required|date',
            'status' => 'required|string'
        ]);

        DB::transaction(function () use ($data, &$booking) {
            //check + lock the order detail & schedule first
            $orderDetail = DB::table('order_details')
                ->where('id', $data['order_detail_id'])
                ->lockForUpdate()
                ->first();

            if ($orderDetail->used_quota >= $orderDetail->total_quota) {
                abort(422, 'Quota exhausted');
            }
            
            $schedule = Schedule::with('courseClass')
                ->lockForUpdate()
                ->find($data['schedule_id']);

           if ($schedule->used_capacity >= $schedule->courseClass->class_capacity) {
                abort(422, 'Schedule is full');
            }

            //insert the booking
            $booking = Booking::create($data);

            //update the increment counters
            DB::table('order_details')
                ->where('id', $data['order_detail_id'])
                ->increment('used_quota', 1);

            DB::table('schedules')
                ->where('id', $data['schedule_id'])
                ->increment('used_capacity', 1);
        });

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
            'order_detail_id' => 'sometimes|integer|exists:order_details,id',
            'class_id' => 'sometimes|integer|exists:course_classes,id',
            'trainer_id' => 'sometimes|integer|exists:trainers,id',
            'schedule_id' => 'sometimes|integer|exists:schedules,id',
            'booking_date' => 'sometimes|date',
            'status' => 'sometimes|string',
            'is_active' => 'sometimes|boolean'
        ]);

        if (empty($data)) {
            return $this->error(null, 'No Booking to update', 422);
        }

        $booking->update($data);
        return $this->success($booking, 'Booking Updated Successfully', 200);
    }

    public function destroy(Booking $booking)
    {
        $booking->update(['is_active' => false]);
        return response()->noContent();
    }

    public function bookingDetails(Request $request)
    {
        try {
            $request->validate([
                'schedule_id' => 'required|integer'
            ]);

            $bookings = Booking::with('userManagement')
                ->where('schedule_id', $request->schedule_id)
                ->get();

            return $this->success($bookings);
        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);

        }
    }

    public function myBooking(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|integer'
            ]);

            $bookings = Booking::with('schedule.trainer', 'schedule.courseClass')
                ->where('user_id', $request->user_id)
                ->get();

            return $this->success($bookings);
        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);

        }
    }
}
