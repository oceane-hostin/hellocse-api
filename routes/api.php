<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController;

Route::get('/profiles', [ProfileController::class, 'index']);
