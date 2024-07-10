<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;


Route::post('/login', [AdminController::class, 'login']);

Route::get('/profiles', [ProfileController::class, 'index']);

Route::post('/profiles', [ProfileController::class, 'store'])->middleware('auth:sanctum');;
Route::get('/profile/{id}', [ProfileController::class, 'show']);
