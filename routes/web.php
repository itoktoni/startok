<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PosController;
use Buki\AutoRoute\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified', 'access'])->group(function () {
    Route::auto('/user', 'UsersController', ['name' => 'user']);
    Route::auto('/satuan', 'SatuanController', ['name' => 'satuan']);
    Route::auto('/product', 'ProductController', ['name' => 'product']);
    Route::auto('/category', 'CategoryController', ['name' => 'category']);
    Route::auto('/customer', 'CustomerController', ['name' => 'customer']);
    Route::auto('/salesorder', 'SalesOrderController', ['name' => 'salesorder']);

    // // SalesOrder - Manual routes to avoid Buki auto-route conflicts
    // // Using app('router') directly (like AutoRoute does) to preserve 'name' key in route action array
    // app('router')->group(['name' => 'sales-order', 'as' => 'sales-order.'], function () {
    //     Route::get('/sales-order/table', [App\Http\Controllers\SalesOrderController::class, 'getTable'])->name('getTable');
    //     Route::get('/sales-order/create', [App\Http\Controllers\SalesOrderController::class, 'getCreate'])->name('getCreate');
    //     Route::post('/sales-order/create', [App\Http\Controllers\SalesOrderController::class, 'postCreate'])->name('postCreate');
    //     Route::get('/sales-order/edit/{id}', [App\Http\Controllers\SalesOrderController::class, 'getUpdate'])->name('getUpdate');
    //     Route::post('/sales-order/edit/{id}', [App\Http\Controllers\SalesOrderController::class, 'postUpdate'])->name('postUpdate');
    //     Route::get('/sales-order/show/{id}', [App\Http\Controllers\SalesOrderController::class, 'getShow'])->name('getShow');
    //     Route::get('/sales-order/delete/{id}', [App\Http\Controllers\SalesOrderController::class, 'getDelete'])->name('getDelete');
    //     Route::post('/sales-order/delete', [App\Http\Controllers\SalesOrderController::class, 'postDelete'])->name('postDelete');
    // });

    Route::get('dashboard', \App\Http\Controllers\DashboardController::class)->name('dashboard');
    Route::view('dashboard-warehouse', 'dashboard-warehouse')->name('dashboard-warehouse');

    // Warehouse Routes
    Route::prefix('warehouse')->name('warehouse.')->group(function () {
        Route::view('dashboard', 'warehouse.dashboard')->name('dashboard');
        Route::view('register', 'warehouse.register')->name('register');
        Route::view('stock', 'warehouse.stock')->name('stock');
        Route::view('inbound', 'warehouse.inbound')->name('inbound');
        Route::view('barang', 'warehouse.barang')->name('barang');
        Route::view('active-tasks', 'warehouse.active-tasks')->name('active-tasks');
        Route::view('forklift', 'warehouse.forklift')->name('forklift');
        Route::view('generate-barcode', 'warehouse.generate-barcode')->name('generate-barcode');
        Route::view('opname', 'warehouse.opname')->name('opname');
        Route::view('outbound-orders', 'warehouse.outbound-orders')->name('outbound-orders');
        Route::view('prepare-barang', 'warehouse.prepare-barang')->name('prepare-barang');
        Route::view('putaway', 'warehouse.putaway')->name('putaway');
        Route::view('selected-stock', 'warehouse.selected-stock')->name('selected-stock');
        Route::view('split-barang', 'warehouse.split-barang')->name('split-barang');
        Route::view('work-orders', 'warehouse.work-orders')->name('work-orders');
    });

    // POS Routes
    Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
    Route::get('/pos/table', [PosController::class, 'index'])->name('pos.table');
    Route::post('/pos-checkout', [PosController::class, 'checkout'])->name('pos.checkout');
});

require __DIR__.'/settings.php';
