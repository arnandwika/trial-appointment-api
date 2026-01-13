<?php

namespace App\Http\Controllers;

use App\Models\CourseClass;
use Illuminate\Http\Request;

class CourseClassController extends Controller
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
            'class_type' => 'required|string',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'trainer_id' => 'required|integer'
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
            'class_type' => 'sometimes|string',
            'description' => 'nullable|string',
            'price' => 'sometimes|numeric',
            'trainer_id' => 'sometimes|integer'
        ]);

        $class->update($data);

        return $this->success($class, 'Class Updated Successfully', 204);
    }

    public function destroy(CourseClass $class)
    {
        $class->delete();

        return $this->success(null, 'Class Deleted Successfully', 204);
    }
}
