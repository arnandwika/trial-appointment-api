<?php

namespace App\Http\Controllers;

use App\Models\UserManagement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //function insert user
           $validated = $request->validate([
        'role' => 'required|string',
        'name' => 'required|string|max:255',
        'phone_number' => 'nullable|string|max:20',
        'email' => 'required|email|unique:user_management,email',
        'gender' => 'required|in:male,female',
        'password' => 'required|string|min:6',
        'is_active' => 'nullable|boolean',
    ]);

    $user = UserManagement::create([
        'role' => $validated['role'],
        'name' => $validated['name'],
        'phone_number' => $validated['phone_number'] ?? null,
        'email' => $validated['email'],
        'gender' => $validated['gender'],
        'password' => Hash::make($validated['password']),
        'is_active' => $validated['is_active'] ?? true,
    ]);

    return response()->json([
        'message' => 'User berhasil dibuat',
        'data' => $user,
    ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(UserManagement $userManagement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserManagement $userManagement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UserManagement $userManagement)
    {
    $validated = $request->validate([
        'role' => 'sometimes|string',
        'name' => 'sometimes|string|max:255',
        'phone_number' => 'sometimes|nullable|string|max:20',
        'email' => 'sometimes|email|unique:user_management,email,' . $userManagement->id,
        'gender' => 'sometimes|in:male,female',
        'password' => 'sometimes|nullable|string|min:6',
        'is_active' => 'sometimes|boolean',
    ]);

    if (isset($validated['password'])) {
        $validated['password'] = Hash::make($validated['password']);
    }

    $userManagement->update($validated);
    $userManagement->refresh();
    return response()->json([
        'message' => 'User berhasil diupdate',
        'data' => $userManagement
    ], 200);
}
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserManagement $userManagement)
    {
    $userManagement->delete();
    $userManagement->refresh();
    return response()->json([
        'message' => 'User berhasil dihapus'
    ], 200);
    }

    public function login(Request $request)
    {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required|string',
    ]);

    $user = UserManagement::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json([
            'message' => 'Email atau password salah'
        ], 401);
    }

    if (!$user->is_active) {
        return response()->json([
            'message' => 'Akun tidak aktif'
        ], 403);
    }

    // create token
    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'message' => 'Login berhasil',
        'data' => [
            'user' => $user,
            'token' => $token,
            'token_type' => 'Bearer',
        ]
    ], 200);
    }

    public function logout(Request $request)
    {
    // hapus token yang sedang dipakai
    $request->user()->currentAccessToken()->delete();

    return response()->json([
        'message' => 'Logout berhasil'
    ], 200);
    }

    
}
