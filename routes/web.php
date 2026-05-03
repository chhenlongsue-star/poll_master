<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PollController;
use App\Http\Controllers\CategoryController; 
use App\Http\Controllers\Auth\GoogleAuthController as GoogleAuth; 
use App\Models\Poll;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    $trendingPolls = Poll::with(['category', 'options'])
        ->withCount('votes')
        ->where('is_active', true)
        ->where('is_active', true)
        ->orderBy('votes_count', 'desc')
        ->take(3)
        ->get();

    return view('welcome', compact('trendingPolls'));
});

/*
|--------------------------------------------------------------------------
| Google Socialite Routes
|--------------------------------------------------------------------------
*/
Route::get('auth/google', [GoogleAuth::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [GoogleAuth::class, 'handleGoogleCallback']);

/*
|--------------------------------------------------------------------------
| Authenticated User Routes (Users, Sub-Admins, Admins)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    
    // User Dashboard & Content
    Route::get('/dashboard', [PollController::class, 'index'])->name('dashboard');
    Route::get('/my-content', [PollController::class, 'myContent'])->name('polls.my-content');
    Route::post('/polls/{poll}/favourite', [PollController::class, 'toggleFavourite'])->name('polls.favourite');

    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /*
    | Poll Management Routes
    */
    Route::prefix('polls')->name('polls.')->group(function () {
        Route::get('/create', [PollController::class, 'create'])->name('create');
        Route::post('/', [PollController::class, 'store'])->name('store');
        Route::get('/{poll}', [PollController::class, 'show'])->name('show');
        Route::post('/{poll}/vote', [PollController::class, 'vote'])->name('vote');
        
        // Ownership/Admin protected routes
        Route::get('/{poll}/edit', [PollController::class, 'edit'])->name('edit');
        Route::put('/{poll}', [PollController::class, 'update'])->name('update');
        Route::delete('/{poll}', [PollController::class, 'destroy'])->name('destroy');
    });
});

/*
|--------------------------------------------------------------------------
| Staff Routes (Admin & Sub-Admin Shared)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin,sub_admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Admin Dashboards
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/manage-polls', [AdminController::class, 'managePolls'])->name('polls.index');
    
    // Category Management (Shared: Sub-Admins can manage these)
    Route::resource('categories', CategoryController::class);

    // User Management (Shared: Sub-Admins can now Ban, Change Role, and Delete)
    Route::patch('/users/{user}/toggle-status', [AdminController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::post('/users/{user}/update-role', [AdminController::class, 'updateRole'])->name('users.updateRole');
    Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');

    // Poll Management (Shared: Both can toggle active/inactive)
    Route::patch('/polls/{poll}/toggle-active', [AdminController::class, 'togglePollStatus'])->name('polls.toggle-active');
});

require __DIR__.'/auth.php';