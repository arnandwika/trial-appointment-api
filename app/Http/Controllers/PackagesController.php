<?php

namespace App\Http\Controllers;

use App\Models\Packages;
use Illuminate\Http\Request;

Packages PackagesController extends Controller
{
    public function index()
    {
        return $this->success(
            Packages::all()
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'class_id' => 'required|integer',
            'name' => 'required|string',
            'desc' => 'required|string',
            'quota' => 'required|integer',
            'price' => 'required|numeric|min:0'
        ]);

        $packages = Packages::create($data);

        return $this->success($packages, 'Packages Created Successfuly', 201);
    }

    public function show(Packages $packages)
    {
        return $this->success($packages);
    }

    public function update(Request $request, Packages $packages)
    {
        $data = $request->validate([
            'class_id' => 'sometimes|integer',
            'name' => 'sometimes|string',
            'desc' => 'sometimes|string',
            'quota' => 'sometimes|integer',
            'price' => 'sometimes|numeric|min:0',
            'is_active' => 'sometimes|boolean'
        ]);

        $packages->update($data);

        return $this->success($packages, 'Packages Updated Successfully', 200);
    }

    public function destroy(Packages $packages)
    {
        $packages->update(['is_active' => false]);
        return response()->noContent();
    }
}
