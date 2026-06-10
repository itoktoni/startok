<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LangkahKecilController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\PushNotificationController;
use App\Actions\PlanAction;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('web')->group(function () {
    Route::post('/pos-checkout', [PosController::class, 'checkout'])->name('pos.checkout');
    Route::get('/pos/data', [PosController::class, 'apiData'])->name('pos.data');
});

Route::get('/push/vapid-key', [PushNotificationController::class, 'vapidPublicKey'])->name('push.vapid-key');

Route::get('/activities', [ActivityController::class, 'index'])->name('activities.index');
Route::get('/activities/types', [ActivityController::class, 'types'])->name('activities.types');
Route::get('/activities/{slug}', [ActivityController::class, 'show'])->name('activities.show');

Route::get('/plans', function () {
    $plans = \App\Models\SubscriptionPlan::where('is_active', true)
        ->with('features')
        ->orderBy('sort_order')
        ->get()
        ->map(fn ($plan) => [
            'id' => $plan->id,
            'slug' => $plan->slug,
            'name' => $plan->name,
            'description' => $plan->description,
            'price' => $plan->price,
            'currency' => $plan->currency,
            'features' => $plan->features->map(fn ($f) => [
                'slug' => $f->slug,
                'name' => $f->name,
                'value' => $f->value,
            ]),
        ]);

    $discounts = \App\Models\Discount::where('discount_active', true)
        ->where(function ($q) {
            $q->whereNull('discount_start')->orWhere('discount_start', '<=', now());
        })
        ->where(function ($q) {
            $q->whereNull('discount_end')->orWhere('discount_end', '>=', now());
        })
        ->get()
        ->map(fn ($d) => [
            'code' => $d->discount_code,
            'name' => $d->discount_nama,
            'type' => $d->discount_type,
            'value' => $d->discount_value,
            'min_transaction' => $d->discount_min_transaction,
            'max_amount' => $d->discount_max_amount,
        ]);

    return response()->json([
        'plans' => $plans,
        'discounts' => $discounts,
        'trial_days' => (int) env('LANGKAHKECIL_TRIAL_DAYS', 10),
    ]);
})->name('plans.index');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me'])->name('me');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::put('/password', [AuthController::class, 'changePassword'])->name('password.change');
    Route::post('/purchase-plan', PlanAction::class . '@purchase')->name('purchase.plan');
    Route::get('/validate-plan', PlanAction::class . '@validatePlan')->name('validate.plan');
    Route::post('/pos/checkout-api', [PosController::class, 'apiCheckout'])->name('pos.checkout-api');
    Route::get('/notification/broadcast', [NotificationController::class, 'broadcast'])->name('notification.broadcast');

    Route::prefix('push')->group(function () {
        Route::post('/subscribe', [PushNotificationController::class, 'subscribe'])->name('push.subscribe');
        Route::post('/unsubscribe', [PushNotificationController::class, 'unsubscribe'])->name('push.unsubscribe');
        Route::get('/status', [PushNotificationController::class, 'status'])->name('push.status');
        Route::post('/send', [PushNotificationController::class, 'send'])->name('push.send');
        Route::post('/send-to-all', [PushNotificationController::class, 'sendToAll'])->name('push.send-to-all');
    });

    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('notifications.index');
        Route::put('/{id}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
        Route::put('/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.read-all');
        Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
        Route::delete('/', [NotificationController::class, 'clearAll'])->name('notifications.clear-all');
    });

    Route::prefix('langkahkecil')->group(function () {
        Route::get('/anak', [LangkahKecilController::class, 'getAnakList'])->name('langkahkecil.anak.index');
        Route::post('/anak', [LangkahKecilController::class, 'addAnak'])->name('langkahkecil.anak.store');
        Route::put('/anak/{anakId}', [LangkahKecilController::class, 'updateAnak'])->name('langkahkecil.anak.update');
        Route::delete('/anak/{anakId}', [LangkahKecilController::class, 'deleteAnak'])->name('langkahkecil.anak.destroy');

        Route::post('/anak/{anakId}/skills', [LangkahKecilController::class, 'addSkill'])->name('langkahkecil.anak.skills.store');
        Route::put('/anak/{anakId}/skills/{skillId}', [LangkahKecilController::class, 'updateSkill'])->name('langkahkecil.anak.skills.update');
        Route::delete('/anak/{anakId}/skills/{skillId}', [LangkahKecilController::class, 'deleteSkill'])->name('langkahkecil.anak.skills.destroy');

        Route::post('/anak/{anakId}/activities', [LangkahKecilController::class, 'addActivity'])->name('langkahkecil.anak.activities.store');
        Route::delete('/anak/{anakId}/activities/{activityId}', [LangkahKecilController::class, 'deleteActivity'])->name('langkahkecil.anak.activities.destroy');
        Route::put('/anak/{anakId}/activities/{activityId}/toggle', [LangkahKecilController::class, 'toggleActivity'])->name('langkahkecil.anak.activities.toggle');

        Route::post('/anak/{anakId}/completed-skills', [LangkahKecilController::class, 'addCompletedSkill'])->name('langkahkecil.anak.completed-skills.store');
        Route::delete('/anak/{anakId}/completed-skills/{key}', [LangkahKecilController::class, 'deleteCompletedSkill'])->name('langkahkecil.anak.completed-skills.destroy');

        Route::post('/anak/{anakId}/challenges', [LangkahKecilController::class, 'addChallenge'])->name('langkahkecil.anak.challenges.store');
        Route::put('/anak/{anakId}/challenges/{challengeId}', [LangkahKecilController::class, 'updateChallenge'])->name('langkahkecil.anak.challenges.update');
        Route::delete('/anak/{anakId}/challenges/{challengeId}', [LangkahKecilController::class, 'deleteChallenge'])->name('langkahkecil.anak.challenges.destroy');

        Route::post('/anak/{anakId}/challenge-history', [LangkahKecilController::class, 'addChallengeHistory'])->name('langkahkecil.anak.challenge-history.store');

        Route::post('/anak/{anakId}/checklists', [LangkahKecilController::class, 'addChecklist'])->name('langkahkecil.anak.checklists.store');
        Route::put('/anak/{anakId}/checklists/{checklistId}', [LangkahKecilController::class, 'updateChecklist'])->name('langkahkecil.anak.checklists.update');
        Route::delete('/anak/{anakId}/checklists/{checklistId}', [LangkahKecilController::class, 'deleteChecklist'])->name('langkahkecil.anak.checklists.destroy');

        Route::post('/anak/{anakId}/schedules', [LangkahKecilController::class, 'addSchedule'])->name('langkahkecil.anak.schedules.store');
        Route::put('/anak/{anakId}/schedules/{scheduleId}', [LangkahKecilController::class, 'updateSchedule'])->name('langkahkecil.anak.schedules.update');
        Route::delete('/anak/{anakId}/schedules/{scheduleId}', [LangkahKecilController::class, 'deleteSchedule'])->name('langkahkecil.anak.schedules.destroy');

        Route::post('/anak/{anakId}/worksheets', [LangkahKecilController::class, 'addWorksheet'])->name('langkahkecil.anak.worksheets.store');
        Route::delete('/anak/{anakId}/worksheets/{worksheetId}', [LangkahKecilController::class, 'deleteWorksheet'])->name('langkahkecil.anak.worksheets.destroy');

        Route::post('/sync', [LangkahKecilController::class, 'sync'])->name('langkahkecil.sync');

        Route::get('/anak/{anakId}/evaluations', [LangkahKecilController::class, 'getEvaluations'])->name('langkahkecil.anak.evaluations.index');
        Route::post('/anak/{anakId}/evaluations', [LangkahKecilController::class, 'addEvaluation'])->name('langkahkecil.anak.evaluations.store');
        Route::delete('/anak/{anakId}/evaluations/{evalId}', [LangkahKecilController::class, 'deleteEvaluation'])->name('langkahkecil.anak.evaluations.destroy');
    });
});
