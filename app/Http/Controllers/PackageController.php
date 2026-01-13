<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends ApiController
{
    public function index()
    {
        return $this->success(
            Package::all()
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'class_id' => 'required|integer',
            'title' => 'required|string',
            'description' => 'required|string',
            'quota' => 'required|integer',
            'price' => 'required|numeric|min:0'
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
            'class_id' => 'sometimes|integer',
            'title' => 'sometimes|string',
            'description' => 'sometimes|string',
            'quota' => 'sometimes|integer',
            'price' => 'sometimes|numeric|min:0',
            'is_active' => 'sometimes|boolean'
        ]);

        $package->update($data);
        return $this->success($package, 'Package Updated Successfully', 200);
    }

    public function destroy(Package $package)
    {
        $package->update(['is_active' => false]);
        return response()->noContent();
    }
}
