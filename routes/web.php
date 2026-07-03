<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProfileController;
use Inertia\Inertia;
use Illuminate\Foundation\Application;
use App\Http\Controllers\FollowController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [PostController::class, 'index'])->name('dashboard');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');

    // Likes routes
    Route::post('/likes', [LikeController::class, 'store'])->name('likes.store');
    Route::delete('/likes/{post}', [LikeController::class, 'destroy'])->name('likes.destroy');

    // Follow routes
    Route::post('/users/{user}/follow', [FollowController::class, 'store'])->name('users.follow');
    Route::delete('/users/{user}/unfollow', [FollowController::class, 'destroy'])->name('users.unfollow');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/posts/{post}/comments', [App\Http\Controllers\CommentController::class, 'store'])->name('posts.comments.store');
});

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