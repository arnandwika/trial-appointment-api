<?php

namespace App\Http\Controllers;

use App\Models\CourseClass;
use Illuminate\Http\Request;

class CourseClassController extends ApiController
{
    public function index()
    {
        return $this->success(
            CourseClass::all()
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'image_url' => 'nullable|string',
            'description' => 'required|string'
        ]);

        $class = CourseClass::create($data);
        return $this->success($class, 'Class Created Successfuly', 201);
    }

    public function show(CourseClass $class)
    {
        return $this->success($class);
    }

    public function update(Request $request, CourseClass $class)
    {
        $data = $request->validate([
            'name' => 'sometimes|string',
            'image_url' => 'sometimes|numeric',
            'description' => 'sometimes|integer',
            'is_active' => 'sometimes|boolean'
        ]);

        $class->update($data);
        return $this->success($class, 'Class Updated Successfully', 204);
    }

    public function destroy(CourseClass $class)
    {
        $class->update(['is_active' => false]);
        return response()->noContent();
    }
}
