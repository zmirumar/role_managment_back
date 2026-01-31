<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

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

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/posts', [App\Http\Controllers\Api\PostController::class, 'index']);
Route::get('/posts/{id}', [App\Http\Controllers\Api\PostController::class, 'show']);

// Protected routes
Route::middleware('auth:api')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Blog Management
    Route::post('/posts', [App\Http\Controllers\Api\PostController::class, 'store'])->middleware('permission:post.create');
    Route::put('/posts/{id}', [App\Http\Controllers\Api\PostController::class, 'update'])->middleware('permission:post.edit');
    Route::delete('/posts/{id}', [App\Http\Controllers\Api\PostController::class, 'destroy'])->middleware('permission:post.delete');

    // Admin Dashboard (ADMIN only)
    Route::middleware('role:ADMIN')->prefix('admin')->group(function () {
        Route::get('/users', [App\Http\Controllers\Api\AdminController::class, 'users']);
        Route::put('/users/{id}/role', [App\Http\Controllers\Api\AdminController::class, 'changeRole']);
        Route::get('/roles', [App\Http\Controllers\Api\AdminController::class, 'roles']);
        Route::put('/roles/{id}/permissions', [App\Http\Controllers\Api\AdminController::class, 'updatePermissions']);
    });
});
