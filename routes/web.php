<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PosController;
use Buki\AutoRoute\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified', 'access'])->group(function () {
    Route::auto('/user', 'UsersController', ['name' => 'user']);
    Route::auto('/product', 'ProductController', ['name' => 'product']);
    Route::auto('/category', 'CategoryController', ['name' => 'category']);

    Route::get('dashboard', DashboardController::class)->name('dashboard');
    Route::get('pos', [PosController::class, 'index'])->name('pos.index');
    Route::get('pos/table', [PosController::class, 'getTable'])->name('pos.table');
});

require __DIR__.'/settings.php';
