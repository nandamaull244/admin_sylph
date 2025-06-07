<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\ArtworkController;


Route::get('/', [AdminController::class, 'index'])->name('admin.index');
Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
Route::post('/users', [UserController::class, 'store'])->name('users.store');
Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
Route::delete('/images/{id}', [UserController::class, 'deleteImage'])->name('images.destroy');
Route::delete('/mind-files/{id}', [UserController::class, 'deleteMindFile'])->name('mind-files.delete');

// tracking routes
Route::get('/tracking', [TrackingController::class, 'index'])->name('tracking.index');


//artwork routes
Route::get('/artworks', [ArtworkController::class, 'index'])->name('artwork.index');
Route::delete('/artworks/{id}', [ArtworkController::class, 'destroy'])->name('artwork.destroy');



