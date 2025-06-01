<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\Admin\HackathonController as AdminHackathonController;
use App\Http\Controllers\HackathonController as PublicHackathonController;


Route::get('/', function () {
    return view('welcome');
});

// Homepage / Public Dashboard
Route::get('/', [DashboardController::class, 'index'])->name('home'); // <-- CHANGE THIS

// === Public Hackathon Routes ===
Route::get('/hackathons', [PublicHackathonController::class, 'index'])->name('hackathons.public.index');
//Route::get('/hackathons/{hackathon:id}', [PublicHackathonController::class, 'show'])->name('hackathons.public.show');
Route::get('/hackathons/{hackathon:slug}', [PublicHackathonController::class, 'show'])->name('hackathons.public.show');

Route::get('/hackathons/{hackathon}', [PublicHackathonController::class, 'show'])->name('hackathons.public.show');
Route::post('/hackathons/{hackathon}/register', [PublicHackathonController::class, 'register'])->name('hackathons.public.register')->middleware('auth');

Route::post('/hackathons/{hackathon:slug}/submit-project', [PublicHackathonController::class, 'submitProject'])->name('hackathons.public.submit_project')->middleware('auth');


// Route for public project detail page
Route::get('/projects/{project}', [ProjectController::class, 'showPublic'])->name('projects.show-public');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


// === Admin Routes ===
Route::middleware(['auth', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {
     Route::get('/hackathons', [AdminHackathonController::class, 'index'])->name('hackathons.index');
    Route::get('/hackathons/create', [AdminHackathonController::class, 'create'])->name('hackathons.create');
    Route::post('/hackathons', [AdminHackathonController::class, 'store'])->name('hackathons.store');


    Route::get('/hackathons/{hackathon:id}/edit', [AdminHackathonController::class, 'edit'])->name('hackathons.edit');
    Route::put('/hackathons/{hackathon:id}', [AdminHackathonController::class, 'update'])->name('hackathons.update');
    Route::delete('/hackathons/{hackathon:id}', [AdminHackathonController::class, 'destroy'])->name('hackathons.destroy');
    Route::get('/hackathons/{hackathon:id}', [AdminHackathonController::class, 'show'])->name('hackathons.show');

});



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::post('/projects/{project}/comments', [CommentController::class, 'store'])->name('projects.comments.store')->middleware('auth');

require __DIR__.'/auth.php';
