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
            'image_file' => 'nullable|string',
            'description' => 'required|string',
            'class_capacity' => 'required|integer'
        ]);

        $courseClass = CourseClass::create($data);
        return $this->success($courseClass, 'Class Created Successfuly', 201);
    }

    public function show(CourseClass $courseClass)
    {
        return $this->success($courseClass);
    }

    public function update(Request $request, CourseClass $courseClass)
    {
        $data = $request->validate([
            'name' => 'sometimes|string',
            'image_file' => 'sometimes|string',
            'description' => 'sometimes|string',
            'class_capacity' => 'sometimes|integer',
            'is_active' => 'sometimes|boolean'
        ]);

        if (isset($data['image_file'])) {
            $data['image_url'] = $data['image_file'];
            unset($data['image_file']);
        }
        
        $courseClass->update($data);
        return $this->success($courseClass, 'Class Updated Successfully', 200);
    }

    public function destroy(CourseClass $courseClass)
    {
        $courseClass->update(['is_active' => false]);
        return response()->noContent();
    }
}
