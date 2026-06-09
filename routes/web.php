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

Route::middleware(['auth', 'verified', 'access'])->group(function () {
    Route::auto('/user', 'UsersController', ['name' => 'user']);
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

    Route::get('dashboard', DashboardController::class)->name('dashboard');

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
