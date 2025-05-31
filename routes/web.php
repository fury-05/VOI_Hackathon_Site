<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CommentController;

Route::get('/', function () {
    return view('welcome');
});

// Homepage / Public Dashboard
Route::get('/', [DashboardController::class, 'index'])->name('home'); // <-- CHANGE THIS


// Route for public project detail page
Route::get('/projects/{project}', [ProjectController::class, 'showPublic'])->name('projects.show-public');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::post('/projects/{project}/comments', [CommentController::class, 'store'])->name('projects.comments.store')->middleware('auth');

require __DIR__.'/auth.php';
