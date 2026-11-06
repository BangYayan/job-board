<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\JobController;
use \App\Http\Controllers\AuthController;

Route::redirect('/', '/jobs');

Route::resource('jobs', JobController::class);

Route::get('login', [AuthController::class, 'index'])->name('login');

Route::post('login', [AuthController::class, 'store'])->name('auth.store');

Route::delete('logout', [AuthController::class, 'destroy'])->name('auth.destroy');