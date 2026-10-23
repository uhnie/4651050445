<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HelloController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/auth', [AuthController::class, 'validateToken']);

Route::middleware('jwt')->group(function () {
    Route::get('/hello', [HelloController::class, 'index']);
});
