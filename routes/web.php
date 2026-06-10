<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\PushNotificationController;
use App\Http\Livewire\NotificationSettings;
use App\Models\User;
use Buki\AutoRoute\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::get('test-push', function () {
    $user = User::find(3);
    if (! $user) {
        return response()->json(['error' => 'User 1 not found']);
    }

    $subscriptions = $user->pushSubscriptions()->count();
    if ($subscriptions === 0) {
        return response()->json([
            'error' => 'No push subscriptions for user 1',
            'hint' => 'Open the frontend app, login, go to Profile tab, and toggle "Notifikasi Push" ON first',
            'vapid_key' => config('push.vapid.public_key') ? 'configured' : 'MISSING',
        ]);
    }

    try {
        $user->sendPushNotification('Test Notifikasi', 'Ini adalah test push notification dari Halo Bunda!', '/');

        return response()->json(['success' => true, 'message' => "Push sent to {$subscriptions} subscription(s)"]);
    } catch (Throwable $e) {
        return response()->json(['error' => $e->getMessage()]);
    }
});

Route::get('test-broadcast', function () {
    \App\Events\NotificationSent::dispatch(1, 'Test Broadcast', 'Ini adalah test broadcast notification!');
    return response()->json(['message' => 'Broadcast sent!']);
});

Route::middleware(['auth', 'verified', 'access'])->group(function () {

    Route::get('dashboard', DashboardController::class)->name('dashboard');

    Route::auto('/user', 'UsersController', ['name' => 'user']);
    Route::auto('/satuan', 'SatuanController', ['name' => 'satuan']);
    Route::auto('/product', 'ProductController', ['name' => 'product']);
    Route::auto('/category', 'CategoryController', ['name' => 'category']);
    Route::auto('/customer', 'CustomerController', ['name' => 'customer']);
    Route::auto('/salesorder', 'SalesOrderController', ['name' => 'salesorder']);

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

    Route::get('notifications', NotificationSettings::class)->name('notifications');
    Route::prefix('push')->group(function () {
        Route::post('/subscribe', [PushNotificationController::class, 'subscribe'])->name('web.push.subscribe');
        Route::post('/unsubscribe', [PushNotificationController::class, 'unsubscribe'])->name('web.push.unsubscribe');
        Route::get('/status', [PushNotificationController::class, 'status'])->name('web.push.status');
    });

    // POS Routes
    Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
    Route::get('/pos/table', [PosController::class, 'index'])->name('pos.table');
    Route::post('/pos-checkout', [PosController::class, 'checkout'])->name('pos.checkout');
});

require __DIR__.'/settings.php';
