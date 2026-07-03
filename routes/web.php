<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;
use Inertia\Inertia;
use Illuminate\Foundation\Application;
use App\Http\Controllers\FollowController;

Route::post('/users/{user}/follow', [FollowController::class, 'store'])->name('users.follow');
Route::delete('/users/{user}/unfollow', [FollowController::class, 'destroy'])->name('users.unfollow');


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [PostController::class, 'index'])->name('dashboard');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
});

// 1. Dashboard route (protected by 'auth' and 'verified' middleware)
Route::get('/dashboard', [PostController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// 2. Your custom post creation route
Route::post('/posts', [PostController::class, 'store'])
    ->middleware(['auth'])
    ->name('posts.store');

// 3. Welcome page

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::post('/users/{user}/follow', [FollowController::class, 'store'])->name('users.follow');
// This line imports all the login/register/logout routes from Breeze
require __DIR__.'/auth.php';