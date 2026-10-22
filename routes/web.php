<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\JobController;

Route::redirect('/', '/jobs');

Route::resource('jobs', JobController::class);