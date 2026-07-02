<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;
use Inertia\Inertia;
use Illuminate\Foundation\Application;


Route::middleware('auth')->group(function () {
    Route::post('/likes', [LikeController::class, 'store'])->name('likes.store');
    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
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


// This line imports all the login/register/logout routes from Breeze
require __DIR__.'/auth.php';