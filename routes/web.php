<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminRegistrationController;
use App\Http\Controllers\Admin\AdminSettingController;
use App\Http\Controllers\Admin\AdminSmsController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\BankController;
use App\Http\Controllers\Admin\BkashSettingController;
use App\Http\Controllers\Admin\SmsTemplateController;
use App\Http\Controllers\BkashWebhookController;
use App\Http\Controllers\RegistrationController;
use Illuminate\Support\Facades\Route;

/* ============================================================
 | Public Routes (with throttling)
============================================================ */
Route::get('/', [RegistrationController::class, 'index'])->name('home');

Route::post('/dhanmondi-15no', [RegistrationController::class, 'store'])
    ->middleware('throttle:public-form')
    ->name('register.store');

Route::get('/registration/success/{token}', [RegistrationController::class, 'success'])
    ->middleware('throttle:public-track')
    ->name('register.success');

Route::get('/registration/status/{token}', [RegistrationController::class, 'status'])
    ->middleware('throttle:public-track')
    ->name('register.status');

Route::get('/registration/track', [RegistrationController::class, 'trackForm'])
    ->name('register.track');

Route::post('/registration/track', [RegistrationController::class, 'track'])
    ->middleware('throttle:public-track')
    ->name('register.track.post');

/* ============================================================
 | bKash Webhook (no CSRF, throttled)
============================================================ */
Route::post('/webhooks/bkash', [BkashWebhookController::class, 'handle'])
    ->withoutMiddleware(['csrf'])
    ->middleware('throttle:webhook')
    ->name('bkash.webhook');

/* ============================================================
 | Breeze dashboard redirect
============================================================ */
Route::middleware('auth')->get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->name('dashboard');

/* ============================================================
 | Admin Routes (auth + admin middleware)
============================================================ */
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'admin'])
    ->group(function () {

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        /* Registrations */
        Route::get('/registrations/pending',    [AdminRegistrationController::class, 'pending'])->name('registrations.pending');
        Route::get('/registrations/verified',   [AdminRegistrationController::class, 'verified'])->name('registrations.verified');
        Route::get('/registrations/registered', [AdminRegistrationController::class, 'registered'])->name('registrations.registered');
        Route::get('/registrations/print',      [AdminRegistrationController::class, 'print'])->name('registrations.print');
        Route::get('/registrations/pdf',        [AdminRegistrationController::class, 'exportPdf'])->name('registrations.pdf');
        Route::get('/registrations/export',     [AdminRegistrationController::class, 'export'])->name('registrations.export');
        Route::get('/registrations',            [AdminRegistrationController::class, 'index'])->name('registrations');
        Route::post('/registrations/bulk-action', [AdminRegistrationController::class, 'bulkAction'])->name('registrations.bulk');
        Route::get('/registrations/{registration}',      [AdminRegistrationController::class, 'show'])->name('registrations.show');
        Route::get('/registrations/{registration}/edit', [AdminRegistrationController::class, 'edit'])->name('registrations.edit');
        Route::put('/registrations/{registration}',      [AdminRegistrationController::class, 'update'])->name('registrations.update');
        Route::delete('/registrations/{registration}',   [AdminRegistrationController::class, 'destroy'])->name('registrations.destroy');

        /* Actions */
        Route::post('/registrations/{registration}/query-bkash',    [AdminRegistrationController::class, 'queryBkash'])->name('query.bkash');
        Route::post('/registrations/{registration}/verify-payment', [AdminRegistrationController::class, 'verifyPayment'])->name('verify.payment');
        Route::post('/registrations/{registration}/reject-payment', [AdminRegistrationController::class, 'rejectPayment'])->name('reject.payment');
        Route::post('/registrations/{registration}/approve',        [AdminRegistrationController::class, 'approve'])->name('approve');
        Route::post('/registrations/{registration}/reject',         [AdminRegistrationController::class, 'reject'])->name('reject');

        /* Banks */
        Route::resource('banks', BankController::class);

        /* SMS Templates */
        Route::prefix('sms-templates')->name('sms.templates.')->group(function () {
            Route::get('/',                      [SmsTemplateController::class, 'index'])->name('index');
            Route::get('/create',                [SmsTemplateController::class, 'create'])->name('create');
            Route::post('/',                     [SmsTemplateController::class, 'store'])->name('store');
            Route::get('/{template}/edit',       [SmsTemplateController::class, 'edit'])->name('edit');
            Route::put('/{template}',            [SmsTemplateController::class, 'update'])->name('update');
            Route::delete('/{template}',         [SmsTemplateController::class, 'destroy'])->name('destroy');
            Route::post('/{template}/duplicate', [SmsTemplateController::class, 'duplicate'])->name('duplicate');
            Route::post('/{template}/toggle',    [SmsTemplateController::class, 'toggle'])->name('toggle');
            Route::post('/{template}/reset',     [SmsTemplateController::class, 'reset'])->name('reset');
            Route::get('/{template}/preview',    [SmsTemplateController::class, 'preview'])->name('preview');
        });

        /* bKash settings */
        Route::get('/bkash-settings', [BkashSettingController::class, 'index'])->name('bkash.index');
        Route::post('/bkash-settings', [BkashSettingController::class, 'update'])->name('bkash.update');
        Route::post('/bkash-settings/test', [BkashSettingController::class, 'testConnection'])->name('bkash.test');

        /* SMS Logs */
        Route::get('/sms-logs', [AdminSmsController::class, 'index'])->name('sms.index');
        Route::post('/sms-logs/test', [AdminSmsController::class, 'sendTest'])->name('sms.test');
        Route::post('/sms-logs/{log}/resend', [AdminSmsController::class, 'resend'])->name('sms.resend');

        /* Users */
        Route::resource('users', AdminUserController::class);

        /* Settings */
        Route::get('/settings', [AdminSettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [AdminSettingController::class, 'update'])->name('settings.update');
    });

require __DIR__.'/auth.php';