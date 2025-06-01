<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HackathonController as PublicHackathonController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\HackathonController as AdminHackathonController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Homepage / Public Dashboard
Route::get('/', [DashboardController::class, 'index'])->name('home');

// === Public Hackathon Routes ===
Route::get('/hackathons', [PublicHackathonController::class, 'index'])->name('hackathons.public.index');
Route::get('/hackathons/{hackathon}', [PublicHackathonController::class, 'show'])->name('hackathons.public.show');
// Note: Auth middleware routes for hackathons can remain here or be grouped if preferred
Route::post('/hackathons/{hackathon}/register', [PublicHackathonController::class, 'register'])->name('hackathons.public.register')->middleware('auth');
Route::post('/hackathons/{hackathon}/submit-project', [PublicHackathonController::class, 'submitProject'])->name('hackathons.public.submit_project')->middleware('auth');


// === USER PROJECT MANAGEMENT (Authenticated) ===
// Define specific project actions like 'create' BEFORE the wildcard '{project}' route.
Route::middleware(['auth'])->group(function () {
    Route::get('/projects/create', [ProjectController::class, 'create'])->name('projects.create');
    Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');

    // You might want to add other user-specific project routes here in the future:
    // Route::get('/my-projects', [ProjectController::class, 'myProjects'])->name('projects.my');
    // Route::get('/projects/{project}/edit', [ProjectController::class, 'edit'])->name('projects.edit'); // Ensure {project} is constrained or carefully ordered
    // Route::put('/projects/{project}', [ProjectController::class, 'update'])->name('projects.update');
    // Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');
});


// === Public Project Routes ===
// This wildcard route should come AFTER specific routes like /projects/create
Route::get('/projects/{project}', [ProjectController::class, 'showPublic'])->name('projects.show-public');
Route::post('/projects/{project}/comments', [CommentController::class, 'store'])->name('projects.comments.store')->middleware('auth');
Route::post('/projects/{project}/refresh-github', [ProjectController::class, 'refreshGithubData'])
    ->name('projects.refreshGithub')
    ->middleware('auth');

// === Authenticated User General Routes (like Breeze dashboard, profile) ===
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware('verified')->name('dashboard');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
}); // End of general auth middleware group


// === Admin Routes ===
Route::middleware(['auth', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/hackathons', [AdminHackathonController::class, 'index'])->name('hackathons.index');
    Route::get('/hackathons/create', [AdminHackathonController::class, 'create'])->name('hackathons.create');
    Route::post('/hackathons', [AdminHackathonController::class, 'store'])->name('hackathons.store');
    Route::get('/hackathons/{hackathon:id}', [AdminHackathonController::class, 'show'])->name('hackathons.show');
    Route::get('/hackathons/{hackathon:id}/edit', [AdminHackathonController::class, 'edit'])->name('hackathons.edit');
    Route::put('/hackathons/{hackathon:id}', [AdminHackathonController::class, 'update'])->name('hackathons.update');
    Route::delete('/hackathons/{hackathon:id}', [AdminHackathonController::class, 'destroy'])->name('hackathons.destroy');
}); // End of admin middleware group

// Auth routes scaffolded by Breeze
if (file_exists(__DIR__.'/auth.php')) {
    require __DIR__.'/auth.php';
}
