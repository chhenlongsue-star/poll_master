<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PollController;
use App\Models\Poll;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    // Fetch the top 3 polls with the most votes for the welcome page
    $trendingPolls = Poll::with(['category', 'options'])
        ->withCount('votes')
        ->where('is_active', true)
        ->orderBy('votes_count', 'desc')
        ->take(3)
        ->get();

    return view('welcome', compact('trendingPolls'));
});

/*
|--------------------------------------------------------------------------
| Authenticated User Routes (Users, Sub-Admins, Admins)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    
    // The main dashboard
    Route::get('/dashboard', [PollController::class, 'index'])->name('dashboard');

    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Poll Actions
    Route::prefix('polls')->name('polls.')->group(function () {
        Route::get('/create', [PollController::class, 'create'])->name('create');
        Route::post('/', [PollController::class, 'store'])->name('store');
        Route::get('/{poll}', [PollController::class, 'show'])->name('show');
        Route::post('/{poll}/vote', [PollController::class, 'vote'])->name('vote');
        
        // Edit/Update/Delete
        Route::get('/{poll}/edit', [PollController::class, 'edit'])->name('edit');
        Route::put('/{poll}', [PollController::class, 'update'])->name('update');
        Route::delete('/{poll}', [PollController::class, 'destroy'])->name('destroy');
    });
});

/*
|--------------------------------------------------------------------------
| Admin & Sub-Admin Routes (Management Only)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin,sub_admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Admin Dashboard (Statistics & Charts)
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

    // --- EXCLUSIVE MAIN ADMIN ONLY (User Management) ---
    Route::middleware(['role:admin'])->group(function () {
        // Changed from 'promote' to 'updateRole' to handle sub_admin, user, and admin
        Route::post('/users/{user}/update-role', [AdminController::class, 'updateRole'])->name('users.updateRole');
        Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');
    });
});

require __DIR__.'/auth.php';