<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PackagesController;
use App\Http\Controllers\UserManagementController;

//LOGIN
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('auth')->group(function () {
    Route::post('/login', [UserManagementController::class, 'login']);
    Route::post('/logout', [UserManagementController::class, 'logout'])
        ->middleware('auth:sanctum');
});

//API's
Route::apiResource('user-management', UserManagementController::class);
Route::apiResource('packages', PackagesController::class);
Route::apiResource('course-class', CourseClassController::class);
Route::apiResource('trainer', TrainerController::class);
