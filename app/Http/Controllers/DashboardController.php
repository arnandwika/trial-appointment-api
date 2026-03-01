<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Package;
use App\Models\Schedule;
use App\Models\CourseClass;
use Illuminate\Http\Request;

class DashboardController extends ApiController
{
    public function index()
    {
        try {

            // 🔢 TOTAL COUNTS
            $totalCourseClass = CourseClass::count();
            $totalSchedule    = Schedule::count();
            $totalPackage     = Package::count();
            $activeOrders     = Order::where('status', 'active')->count();

            // 📅 2 SCHEDULE TERDEKAT
            $upcomingSchedules = Schedule::whereDate('datetime_schedule', '>=', now())
                ->orderBy('datetime_schedule', 'asc')
                ->take(2)
                ->get();

            // 🧾 2 ORDER TERAKHIR
            $recentOrders = Order::with('orderDetails')
                ->latest()
                ->take(2)
                ->get();

            return $this->success([
                'total_course_class' => $totalCourseClass,
                'total_schedule'     => $totalSchedule,
                'total_package'      => $totalPackage,
                'active_orders'      => $activeOrders,
                'upcoming_schedules' => $upcomingSchedules,
                'recent_orders'      => $recentOrders,
            ], 'Dashboard data retrieved successfully');

        } catch (\Throwable $e) {

            return $this->error($e->getMessage(), 500);
        }
    }
}