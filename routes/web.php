<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

Route::get('/dashboard', [PostController::class, 'index'])->name('dashboard');
Route::post('/posts', [PostController::class, 'store'])->name('posts.store');

Route::get('/', function () {
    return view('welcome');
});
