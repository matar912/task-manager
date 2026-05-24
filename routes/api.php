<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes — Task Manager
|--------------------------------------------------------------------------
*/

// ── Auth (public) ──────────────────────────────────────────────────────────
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login',    [AuthController::class, 'login']);
});

// ── Routes protégées par JWT (Sanctum) ────────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/auth/logout', [AuthController::class, 'logout']);

    // Tasks CRUD
    Route::apiResource('tasks', TaskController::class);

    // Categories CRUD
    Route::apiResource('categories', CategoryController::class);

    // Notifications
    Route::prefix('notifications')->group(function () {
        Route::get('/',         [NotificationController::class, 'index']);
        Route::put('/{id}/read',[NotificationController::class, 'markAsRead']);
    });
});

// ── Health check ───────────────────────────────────────────────────────────
Route::get('/health', fn () => response()->json([
    'status'  => 'ok',
    'service' => 'task-manager',
    'time'    => now()->toISOString(),
]));
