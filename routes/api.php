<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LangkahKecilController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('web')->group(function () {
    Route::post('/pos-checkout', [App\Http\Controllers\PosController::class, 'checkout'])->name('pos.checkout');
    Route::get('/pos/data', [App\Http\Controllers\PosController::class, 'apiData'])->name('pos.data');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/pos/checkout-api', [App\Http\Controllers\PosController::class, 'apiCheckout'])->name('pos.checkout-api');

    Route::prefix('langkahkecil')->group(function () {
        Route::get('/anak', [LangkahKecilController::class, 'getAnakList'])->name('langkahkecil.anak.index');
        Route::post('/anak', [LangkahKecilController::class, 'addAnak'])->name('langkahkecil.anak.store');
        Route::put('/anak/{anakId}', [LangkahKecilController::class, 'updateAnak'])->name('langkahkecil.anak.update');
        Route::delete('/anak/{anakId}', [LangkahKecilController::class, 'deleteAnak'])->name('langkahkecil.anak.destroy');

        Route::post('/anak/{anakId}/skills', [LangkahKecilController::class, 'addSkill'])->name('langkahkecil.anak.skills.store');
        Route::put('/anak/{anakId}/skills/{skillId}', [LangkahKecilController::class, 'updateSkill'])->name('langkahkecil.anak.skills.update');
        Route::delete('/anak/{anakId}/skills/{skillId}', [LangkahKecilController::class, 'deleteSkill'])->name('langkahkecil.anak.skills.destroy');

        Route::post('/anak/{anakId}/activities', [LangkahKecilController::class, 'addActivity'])->name('langkahkecil.anak.activities.store');
        Route::delete('/anak/{anakId}/activities', [LangkahKecilController::class, 'deleteActivity'])->name('langkahkecil.anak.activities.destroy');

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
    });
});
