<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends ApiController
{
    public function index()
    {
        return $this->success(
            Package::with('courseClass')->get()
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'class_id' => 'required|integer|exists:course_classes,id',
            'title' => 'required|string',
            'description' => 'required|string',
            'quota' => 'required|integer',
            'price' => 'required|numeric|min:0',
            'valid_days' => 'required|integer'

        ]);

        $package = Package::create($data);
        return $this->success($package, 'Package Created Successfuly', 201);
    }

    public function show(Package $package)
    {
        return $this->success($package);
    }

    public function update(Request $request, Package $package)
    {
        $data = $request->validate([
            'class_id' => 'sometimes|integer|exists:course_classes,id',
            'title' => 'sometimes|string',
            'description' => 'sometimes|string',
            'quota' => 'sometimes|integer',
            'price' => 'sometimes|numeric|min:0',
            'is_active' => 'sometimes|boolean'
        ]);

        if (empty($data)) {
            return $this->error(null, 'No Package to update', 422);
        }

        $package->update($data);
        return $this->success($package, 'Package Updated Successfully', 200);
    }

    public function destroy(Package $package)
    {
        $package->update(['is_active' => false]);
        return response()->noContent();
    }
}
