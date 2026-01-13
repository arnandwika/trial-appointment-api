<?php

namespace App\Http\Controllers;

use App\Models\UserManagement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends ApiController
{

    public function index()
    {
        return $this->success(
            UserManagement::all()
        );
    }

 public function store(Request $request)
    {
        $data = $request->validate([
            'role' => 'required|string',
            'name' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'email' => 'required|email|unique:user_management,email',
            'gender' => 'required|in:male,female',
            'password' => 'required|string|min:6',
            'is_active' => 'nullable|boolean',
        ]);

        $data['password'] = Hash::make($data['password']);
        $data['is_active'] = $data['is_active'] ?? true;

        $user = UserManagement::create($data);

        return $this->success($user, 'User Created Successfully', 201);
    }

    public function update(Request $request, UserManagement $userManagement)
    {
        $data = $request->validate([
            'role' => 'sometimes|string',
            'name' => 'sometimes|string|max:255',
            'phone_number' => 'sometimes|nullable|string|max:20',
            'email' => 'sometimes|email|unique:user_management,email,' . $userManagement->id,
            'gender' => 'sometimes|in:male,female',
            'password' => 'sometimes|nullable|string|min:6',
            'is_active' => 'sometimes|boolean',
        ]);

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $userManagement->update($data);

        return $this->success(
            $userManagement->fresh(),
            'User Updated Successfully'
        );
    }

    public function destroy(UserManagement $userManagement)
    {
        $userManagement->delete();

        return $this->success(null, 'User Deleted Successfully');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $userManagement = UserManagement::where('email', $data['email'])->first();

        if (!$userManagement || !Hash::check($data['password'], $userManagement->password)) {
            return $this->error('Email atau password salah', 401);
        }

        if (!$userManagement->is_active) {
            return $this->error('Akun tidak aktif', 403);
        }

        $token = $userManagement->createToken('auth_token')->plainTextToken;

        return $this->success([
            'user' => $userManagement,
            'token' => $token,
            'token_type' => 'Bearer'
        ], 'Login Successful');
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->success(null, 'Logout Successful');
    }
}

    

