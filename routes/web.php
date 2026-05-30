<?php

use Buki\AutoRoute\Facades\Route;

Route::view('/', 'welcome')->name('home');
Route::get('/pos', [App\Http\Controllers\PosController::class, 'index'])->name('pos')->withoutMiddleware(['auth', 'verified', 'access']);
Route::post('/pos-checkout', [App\Http\Controllers\PosController::class, 'checkout'])->name('pos.checkout');

Route::middleware(['auth', 'verified', 'access'])->group(function () {
    Route::auto('/user', 'UsersController', ['name' => 'user']);
    Route::auto('/product', 'ProductController', ['name' => 'product']);
    Route::auto('/category', 'CategoryController', ['name' => 'category']);

    Route::get('dashboard', \App\Http\Controllers\DashboardController::class)->name('dashboard');
});

require __DIR__.'/settings.php';
