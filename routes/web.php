<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\ArtworkController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\HargaArtworkController;
use App\Http\Controllers\TrafficChartController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\PageController;


// Landing page route
Route::get('/', [PageController::class, 'index'])->name('landing_page.index');
Route::get('/privacy-policy', [PageController::class, 'privacyPolicy'])->name('landing_page.privacy');
//login routes
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login-attempt', [LoginController::class, 'login'])->name('login.attempt');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth:web'])->group(function () {
    // Protected routes that require authentication
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    // user management routes
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::delete('/images/{id}', [UserController::class, 'deleteImage'])->name('images.destroy');
    Route::delete('/mind-files/{id}', [UserController::class, 'deleteMindFile'])->name('mind-files.delete');
    //artwork routes
    Route::delete('/artworks/{id}', [ArtworkController::class, 'destroy'])->name('artworks.destroy');
    Route::get('/artworks', [ArtworkController::class, 'index'])->name('artwork.index');
    Route::get('/admin/harga-artwork', [HargaArtworkController::class, 'index'])->name('harga_artwork.index');
    Route::get('/admin/harga-artwork/{id}/edit', [HargaArtworkController::class, 'edit'])->name('harga_artwork.edit');
    Route::patch('/admin/harga-artwork/{id}', [HargaArtworkController::class, 'update'])->name('harga_artwork.update');
    //dashboard routes
    Route::get('/admin/user-image-count', [TrafficChartController::class, 'getUserImageCountChart']);
    //produk routes
    Route::get('/produk', [ProdukController::class, 'index'])->name('produk.index');
    Route::get('/produk/create', [ProdukController::class, 'create'])->name('produk.create');
    Route::post('/produk', [ProdukController::class, 'store'])->name('produk.store');
    Route::get('/produk/{id}/edit', [ProdukController::class, 'edit'])->name('produk.edit');
    Route::put('/produk/{id}', [ProdukController::class, 'update'])->name('produk.update');
    Route::delete('/produk/{id}', [ProdukController::class, 'destroy'])->name('produk.destroy');

});

// tracking routes
Route::get('/tracking', [TrackingController::class, 'index'])->name('tracking.index');





