<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends ApiController
{
    public function index()
    {
        return $this->success(
            Schedule::with(['courseClass', 'trainer'])->get()
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'class_id' => 'required|integer|exists:course_classes,id',
            'trainer_id' => 'required|integer|exists:trainers,id',
            'datetime_schedule' => 'required|array|min:1',
            'datetime_schedule.*' => 'required|date'
        ]);

        $insertData = [];

        foreach ($data['datetime_schedule'] as $date) {
            $insertData[] = [
                'class_id' => $data['class_id'],
                'trainer_id' => $data['trainer_id'],
                'datetime_schedule' => $date,
                'used_capacity' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Schedule::insert($insertData);

        return $this->success($insertData, 'Schedules Created Successfully', 201);
    }

    public function show(Schedule $schedule)
    {
        return $this->success($schedule);
    }

    public function update(Request $request, Schedule $schedule)
    {
        $data = $request->validate([
            'class_id' => 'sometimes|integer|exists:course_classes,id',
            'trainer_id' => 'sometimes|integer|exists:trainers,id',
            'datetime_schedule' => 'sometimes|date',
            'used_capacity' => 'sometimes|integer',
            'is_active' => 'sometimes|boolean'
        ]);

        if (empty($data)) {
            return $this->error(null, 'No Schedule to update', 422);
        }

        $schedule->update($data);
        return $this->success($schedule, 'Schedule Updated Successfully', 200);
    }

    public function destroy(Schedule $schedule)
    {
        $schedule->update(['is_active' => false]);
        return response()->noContent();
    }

    public function detailSchedule(Schedule $schedule)
    {
        return $this->success(
            $schedule->load(['courseClass', 'trainer'])
        );
    }
}
