<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProfileController;
use Inertia\Inertia;
use Illuminate\Foundation\Application;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\PublicProfileController;
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [PostController::class, 'index'])->name('dashboard');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('posts.comments.store');

    Route::post('/likes', [LikeController::class, 'store'])->name('likes.store');
    Route::delete('/likes/{post}', [LikeController::class, 'destroy'])->name('likes.destroy');

    Route::post('/users/{user}/follow', [FollowController::class, 'toggle'])->name('users.follow');
Route::get('/profile/{username}', [PublicProfileController::class, 'show'])->name('profile.show');
    Route::get('/users/search', [PublicProfileController::class, 'search'])->name('users.search');
    Route::patch('/profile/bio', [PublicProfileController::class, 'updateBio'])->name('profile.bio.update');
});

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});
require __DIR__.'/auth.php';