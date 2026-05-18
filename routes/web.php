<?php

use Buki\AutoRoute\Facades\Route;

Route::view('/', 'welcome')->name('home');


Route::middleware(['auth', 'verified'])->group(function () {
    Route::auto('/product', 'ProductController', ['middleware' => 'auth']);
    Route::get('dashboard', \App\Http\Controllers\DashboardController::class)->name('dashboard');
});

require __DIR__.'/settings.php';
