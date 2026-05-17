<?php

use Buki\AutoRoute\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::auto('/product', 'ProductController');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';
