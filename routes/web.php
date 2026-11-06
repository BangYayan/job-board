<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmployerController;
use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\MyJobApplicationController;
use App\Http\Controllers\MyJobController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/jobs');

Route::resource('jobs', JobController::class)->only(['index', 'show']);

Route::get('login', fn () => to_route('auth.create'))->name('login');
Route::resource('auth', AuthController::class)->only(['create', 'store', 'destroy']);
Route::delete('logout', fn () => to_route('auth.destroy', 'auth'))->name('logout');

Route::middleware('auth')->group(function () {
    Route::resource('job.application', JobApplicationController::class)
        ->only(['create', 'store']);

    Route::resource('my-job-applications', MyJobApplicationController::class)
        ->only(['index', 'destroy']);

    Route::resource('employer', EmployerController::class)
        ->only(['create', 'store']);

    Route::middleware('employer')->group(function () {
        Route::resource('my-jobs', MyJobController::class);
        Route::get('my-jobs/{job}/applications/{application}/cv', [MyJobController::class, 'downloadCv'])
            ->name('my-jobs.cv');
    });
});
