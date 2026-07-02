<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
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
    return view('welcome');
});

// 4. THIS IS WHAT YOU ARE MISSING: 
// This line imports all the login/register/logout routes from Breeze
require __DIR__.'/auth.php';