<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LearnerProgressController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/learner-progress', [LearnerProgressController::class, 'index'])->name('learner-progress');
