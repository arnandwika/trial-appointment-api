<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PackagesController;
use App\Http\Controllers\UserManagementController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/packages', [PackagesController::class, 'index']);

Route::post('/packages/insertpackages', [PackagesController::class, 'store']);
Route::apiResource('user-management', UserManagementController::class);
Route::prefix('auth')->group(function () {
    Route::post('/login', [UserManagementController::class, 'login']);
    Route::post('/logout', [UserManagementController::class, 'logout'])
        ->middleware('auth:sanctum');
});