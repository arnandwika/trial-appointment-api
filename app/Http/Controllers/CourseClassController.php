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
            'image_file' => 'required|file|image|mimes:jpg,jpeg,png|max:5048',
            'description' => 'required|string',
            'class_capacity' => 'required|integer'
        ]);
        // Store file
        $path = $request->file('image_file')->store('course_classes', 'public');

        // Replace image_file with image_url
        $data['image_url'] = $path;
        unset($data['image_file']);

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
            'image_file' => 'sometimes|file|image|mimes:jpg,jpeg,png|max:5048'
            'description' => 'sometimes|string',
            'class_capacity' => 'sometimes|integer',
            'is_active' => 'sometimes|boolean'
        ]);

        if ($request->hasFile('image_file')) {

            // Optional: delete old image
            if ($courseClass->image_url) {
                Storage::disk('public')->delete($courseClass->image_url);
            }

            // Store new file
            $path = $request->file('image_file')->store('course_classes', 'public');

            // Save to DB as image_url
            $data['image_url'] = $path;
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
