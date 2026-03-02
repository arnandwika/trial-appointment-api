<?php

namespace App\Http\Controllers;

use App\Models\Trainer;
use Illuminate\Http\Request;

class TrainerController extends ApiController
{
    public function index()
    {
        return $this->success(
            $trainers = Trainer::with('courseClasses')->get();
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'class_id' => 'required|integer',
            'name' => 'required|string',
            'phone_number' => 'required|string',
            'email' => 'required|string',
            'gender' => 'required|in:male,female',
        ]);

        $trainer = Trainer::create($data);
        return $this->success($trainer, 'Trainer Created Successfuly', 201);
    }

    public function show(Trainer $trainer)
    {
        return $this->success($trainer);
    }

    public function update(Request $request, Trainer $trainer)
    {
        $data = $request->validate([
            'class_id' => 'sometimes|integer|exists:course_classes,id',
            'name' => 'sometimes|string',
            'phone_number' => 'sometimes|string',
            'email' => 'sometimes|string',
            'gender' => 'sometimes|in:male,female',
            'is_active' => 'sometimes|boolean'
        ]);
        
        if (empty($data)) {
            return $this->error(null, 'No Trainer to update', 422);
        }
        
        $trainer->update($data);
        return $this->success($trainer, 'Trainer Updated Successfully', 200);
    }

    public function destroy(Trainer $trainer)
    {
        $trainer->update(['is_active' => false]);
        return response()->noContent();
    }
}
