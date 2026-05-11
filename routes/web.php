<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PollController;
use App\Http\Controllers\CategoryController; 
use App\Http\Controllers\Auth\GoogleAuthController as GoogleAuth; 
// ADDED THIS IMPORT:
use App\Http\Controllers\Auth\AuthenticatedSessionController; 
use App\Models\Poll;
use Illuminate\Support\Facades\Route;

// 1. Public Welcome Page
Route::get('/', function () {
    $trendingPolls = Poll::with(['category', 'options'])
        ->withCount('votes')
        ->where('is_active', true)
        ->orderBy('votes_count', 'desc')
        ->take(3)
        ->get();

    return view('welcome', compact('trendingPolls'));
});

// 2. Google Authentication Routes
Route::get('auth/google', [GoogleAuth::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [GoogleAuth::class, 'handleGoogleCallback']);

// 3. Authenticated User Routes (Regular Users)
Route::middleware(['auth', 'verified'])->group(function () {
    
    Route::get('/dashboard', [PollController::class, 'index'])->name('dashboard');
    Route::get('/my-content', [PollController::class, 'myContent'])->name('polls.my-content');
    Route::post('/polls/{poll}/favourite', [PollController::class, 'toggleFavourite'])->name('polls.favourite');
    Route::get('/about', function () { return view('about'); })->name('about');
    Route::patch('/polls/{poll}/toggle-status', [PollController::class, 'toggleStatus'])->name('polls.toggle-status');

    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // Poll Interactions
    Route::prefix('polls')->name('polls.')->group(function () {
        Route::get('/create', [PollController::class, 'create'])->name('create');
        Route::post('/', [PollController::class, 'store'])->name('store');
        Route::get('/{poll}', [PollController::class, 'show'])->name('show');
        Route::post('/{poll}/vote', [PollController::class, 'vote'])->name('vote');
        Route::get('/{poll}/edit', [PollController::class, 'edit'])->name('edit');
        Route::put('/{poll}', [PollController::class, 'update'])->name('update');
        Route::delete('/{poll}', [PollController::class, 'destroy'])->name('destroy');
    });
});

// 4. Admin & Sub-Admin Routes (PROTECTED)
Route::middleware(['auth', 'role:admin,sub_admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard & Stats
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

    // Poll Management (Admin Actions)
    Route::get('/manage-polls', [AdminController::class, 'managePolls'])->name('polls.index');
    Route::patch('/polls/{poll}/toggle-active', [AdminController::class, 'togglePollStatus'])->name('polls.toggle-active');
    Route::patch('/polls/{poll}/toggle-ban', [AdminController::class, 'togglePollBan'])->name('polls.toggle-ban');
    Route::delete('/polls/{poll}', [AdminController::class, 'destroyPoll'])->name('polls.destroy');

    // User Management
    Route::get('/users/{user}', [AdminController::class, 'showUser'])->name('users.show');
    Route::patch('/users/{user}/role', [AdminController::class, 'updateRole'])->name('users.update-role');
    Route::patch('/users/{user}/toggle-status', [AdminController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');

    // Category Management
    Route::resource('categories', CategoryController::class);
});

require __DIR__.'/auth.php';