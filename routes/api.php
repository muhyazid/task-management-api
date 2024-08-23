<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthenticationController;


// Route untuk login
Route::post('/login', [AuthenticationController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    // Route untuk logout
    Route::get('/logout', [AuthenticationController::class, 'logout']);
});


Route::middleware('auth:sanctum')->group(function () {
   // Route untuk mendapatkan daftar semua project
    Route::get('/projects', [ProjectController::class, 'index']);
    // Route untuk membuat project baru
    Route::post('/projects', [ProjectController::class, 'store']);
    // Route untuk mengupdate project berdasarkan ID
    Route::put('/projects/{id}', [ProjectController::class, 'update']);
    // Route untuk menghapus project berdasarkan ID
    Route::delete('/projects/{id}', [ProjectController::class, 'destroy']);
    
});


// Rute untuk mendapatkan semua task
Route::get('/tasks', [TaskController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    // Rute untuk membuat task baru
    Route::post('/tasks', [TaskController::class, 'store']);
    // rute untuk menampikan task yang mana penanggung jawabnya adalah user tsb yang login
    Route::get('/my-tasks', [TaskController::class, 'myTasks']);
    // Rute untuk memperbarui task yang sudah ada
    Route::put('/tasks/{id}', [TaskController::class, 'update']);
    // Rute untuk menghapus task
    Route::delete('/tasks/{id}', [TaskController::class, 'destroy']);
});


 // Route untuk mendapatkan semua user
Route::get('/users', [UserController::class, 'index']);

Route::middleware(['auth:sanctum'])->group(function () {
    // Route untuk mendapatkan user yang sedang login
    Route::get('/me', [UserController::class, 'me']);
    // Route untuk membuat user baru
    Route::post('/users', [UserController::class, 'store']);
    // Route untuk mengupdate user
    Route::put('/users/{id}', [UserController::class, 'update']);
    // Route untuk menghapus user
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
});

