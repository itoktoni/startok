<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::prefix('product')->controller(ProductController::class)->group(function () {
    Route::get('/', 'getTable');
    Route::get('/data', 'getData');
    Route::get('/{id}', 'getUpdate');
    Route::post('/', 'postCreate');
    Route::put('/{id}', 'postUpdate');
    Route::delete('/{id}', 'postDelete');
    Route::post('/delete-bulk', 'postDeleteBulk');
});
