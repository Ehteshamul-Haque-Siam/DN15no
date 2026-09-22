<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminRegistrationController;
use App\Http\Controllers\Admin\AdminSettingController;
use App\Http\Controllers\Admin\AdminSmsController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\BkashSettingController;
use App\Http\Controllers\BkashWebhookController;
use App\Http\Controllers\RegistrationController;
use Illuminate\Support\Facades\Route;

/* Public */
Route::get('/', [RegistrationController::class, 'index'])->name('home');
Route::post('/dhanmondi-15no', [RegistrationController::class, 'store'])
    ->middleware('throttle:public-form')->name('register.store');
Route::get('/registration/success/{token}', [RegistrationController::class, 'success'])->name('register.success');
Route::get('/registration/status/{token}',  [RegistrationController::class, 'status'])->name('register.status');
Route::get('/registration/track',           [RegistrationController::class, 'trackForm'])->name('register.track');
Route::post('/registration/track',          [RegistrationController::class, 'track'])
    ->middleware('throttle:public-track')->name('register.track.post');

/* bKash webhook — no CSRF */
Route::post('/webhooks/bkash', [BkashWebhookController::class, 'handle'])
    ->withoutMiddleware(['csrf'])
    ->name('bkash.webhook');

/* Breeze dashboard redirect */
Route::middleware('auth')->get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->name('dashboard');

/* Admin */
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'admin'])
    ->group(function () {

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::get('/registrations', [AdminRegistrationController::class, 'index'])->name('registrations');
        Route::get('/registrations/export', [AdminRegistrationController::class, 'export'])->name('registrations.export');
        Route::post('/registrations/bulk-action', [AdminRegistrationController::class, 'bulkAction'])->name('registrations.bulk');
        Route::get('/registrations/{registration}', [AdminRegistrationController::class, 'show'])->name('registrations.show');
        Route::post('/registrations/{registration}/query-bkash', [AdminRegistrationController::class, 'queryBkash'])->name('query.bkash');
        Route::post('/registrations/{registration}/verify-payment', [AdminRegistrationController::class, 'verifyPayment'])->name('verify.payment');
        Route::post('/registrations/{registration}/reject-payment', [AdminRegistrationController::class, 'rejectPayment'])->name('reject.payment');
        Route::post('/registrations/{registration}/approve', [AdminRegistrationController::class, 'approve'])->name('approve');
        Route::post('/registrations/{registration}/reject',  [AdminRegistrationController::class, 'reject'])->name('reject');

        Route::get('/bkash-settings', [BkashSettingController::class, 'index'])->name('bkash.index');
        Route::post('/bkash-settings', [BkashSettingController::class, 'update'])->name('bkash.update');
        Route::post('/bkash-settings/test', [BkashSettingController::class, 'testConnection'])->name('bkash.test');

        Route::get('/sms-logs', [AdminSmsController::class, 'index'])->name('sms.index');
        Route::post('/sms-logs/test', [AdminSmsController::class, 'sendTest'])->name('sms.test');
        Route::post('/sms-logs/{log}/resend', [AdminSmsController::class, 'resend'])->name('sms.resend');

        Route::resource('users', AdminUserController::class);

        Route::get('/settings', [AdminSettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [AdminSettingController::class, 'update'])->name('settings.update');
    });

require __DIR__.'/auth.php';