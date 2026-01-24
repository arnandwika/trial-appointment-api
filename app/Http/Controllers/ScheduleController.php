<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends ApiController
{
    public function index()
    {
        return $this->success(
            Schedule::all()
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'class_id' => 'required|integer',
            'trainer_id' => 'required|integer',
            'datetime_schedule' => 'required|date'
        ]);

        $schedule = Schedule::create($data);
        return $this->success($schedule, 'Schedule Created Successfuly', 201);
    }

    public function show(Schedule $schedule)
    {
        return $this->success($schedule);
    }

    public function update(Request $request, Schedule $schedule)
    {
        $data = $request->validate([
            'class_id' => 'sometimes|integer',
            'trainer_id' => 'sometimes|integer',
            'datetime_schedule' => 'sometimes|date',
            'is_active' => 'sometimes|boolean'
        ]);

        $schedule->update($data);
        return $this->success($schedule, 'Schedule Updated Successfully', 200);
    }

    public function destroy(Schedule $schedule)
    {
        $schedule->update(['is_active' => false]);
        return response()->noContent();
    }
}
