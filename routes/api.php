<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PackageController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\CourseClassController;
use App\Http\Controllers\TrainerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\BookingController;

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
Route::apiResource('package', PackageController::class);
Route::apiResource('course-class', CourseClassController::class);
Route::apiResource('trainer', TrainerController::class);
Route::apiResource('orders', OrderController::class);
Route::apiResource('schedule', ScheduleController::class);
Route::apiResource('booking', BookingController::class);
Route::get('orders/my-transaction/{userId}', [OrderController::class, 'myTransaction']);

