<?php

use App\Http\Controllers\SaleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;

Route::get('/', [SaleController::class, 'index'])->name('home');
Route::get('/login', function () {
    return view('auth.login');
})->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', function () {
    return view('auth.register');
})->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/profile', [SaleController::class, 'profile'])->name('profile');
Route::post('/products', [SaleController::class, 'store'])->name('products.store');
Route::get('/products/{id}', [SaleController::class, 'show'])->name('products.show');
Route::delete('/products/{id}', [SaleController::class, 'destroy'])->name('products.destroy');
Route::post('/products/{id}/toggle-sold', [SaleController::class, 'toggleSold'])->name('products.toggleSold');
Route::get('/products/{id}/edit', [SaleController::class, 'edit'])->name('products.edit');
Route::put('/products/{id}', [SaleController::class, 'update'])->name('products.update');
Route::delete('/images/{id}', [SaleController::class, 'destroyImage'])->name('images.destroy');