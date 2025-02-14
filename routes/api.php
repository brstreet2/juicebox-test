<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Post\PostController;
use App\Http\Controllers\Weather\WeatherController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
Route::get('/weather', [WeatherController::class, 'show']);

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
    Route::get('/users/{id}', [AuthController::class, 'show'])->name('users.show');

    Route::prefix('posts')->group(function () {
        Route::get('/', [PostController::class, 'list'])->name('posts.list');
        Route::get('/{id}', [PostController::class, 'show'])->name('posts.show');
        Route::post('/', [PostController::class, 'store'])->name('posts.store');
        Route::put('/{id}', [PostController::class, 'update'])->name('posts.update');
        Route::delete('/{id}', [PostController::class, 'destroy'])->name('posts.destroy');
    });
});
