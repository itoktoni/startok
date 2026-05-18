<?php

use Buki\AutoRoute\Facades\Route;

Route::view('/', 'welcome')->name('home');


Route::middleware(['auth', 'verified', 'access'])->group(function () {
    Route::auto('/product', 'ProductController', ['name' => 'product']);
    Route::auto('/test', 'ProductController', ['name' => 'test']);

    Route::get('dashboard', \App\Http\Controllers\DashboardController::class)->name('dashboard');
});

require __DIR__.'/settings.php';
