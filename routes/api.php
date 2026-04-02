<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::post('/register', [AuthController::class, 'register']);




Route::middleware(['auth:sanctum', 'role:super_admin'])->group(function () {
    // Hanya Super Admin yang bisa akses ini untuk buat Admin baru
    Route::post('/admin/manage-users', [AdminManagementController::class, 'store']);
});