<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Trader\BookingController;
use App\Http\Controllers\Trader\StallController as TraderStallController;
use App\Http\Controllers\Trader\DashboardController;
use App\Http\Controllers\Trader\MpesaController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\StallController as AdminStallController;
use App\Http\Controllers\Officer\DashboardController as OfficerDashboardController;
use App\Http\Controllers\Officer\ViolationController;

/*
|--------------------------------------------------------------------------
| Root Route (Role Aware with Welcome Page)
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    if (auth()->check()) {

        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if (auth()->user()->role === 'officer') {
            return redirect()->route('officer.dashboard');
        }

        return redirect()->route('trader.dashboard');
    }

    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Terms & Conditions (Public — No Auth Required)
|--------------------------------------------------------------------------
*/
Route::get('/terms', function () {
    return view('terms');
})->name('terms');

/*
|--------------------------------------------------------------------------
| Restricted Account Page
|--------------------------------------------------------------------------
*/
Route::get('/account/restricted', function () {

    if (!auth()->check() || auth()->user()->account_restriction === 'none') {
        return redirect('/');
    }

    return view('errors.restricted');

})->middleware('auth')->name('account.restricted');

/*
|--------------------------------------------------------------------------
| Profile Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Trader Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])
    ->prefix('trader')
    ->name('trader.')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/stalls', [TraderStallController::class, 'index'])
            ->name('stalls.index');

        Route::get('/my-bookings', [BookingController::class, 'index'])
            ->name('bookings.index');

        Route::get('/book/{stallId}', [BookingController::class, 'create'])
            ->name('bookings.create');

        Route::post('/bookings', [BookingController::class, 'store'])
            ->name('bookings.store');

        Route::post('/bookings/{id}/cancel', [BookingController::class, 'cancel'])
            ->name('bookings.cancel');

        Route::get('/bookings/{booking}/renew', [BookingController::class, 'renew'])
            ->name('bookings.renew');

        Route::post('/feedback', [DashboardController::class, 'storeFeedback'])
            ->name('feedback.store');

        // Payment page
        Route::get('/bookings/{id}/pay', [BookingController::class, 'pay'])
            ->name('bookings.pay');

        // Receipt download
        Route::get('/bookings/{id}/receipt', [BookingController::class, 'receipt'])
            ->name('bookings.receipt');

        // M-Pesa endpoints
        Route::post('/mpesa/pay', [MpesaController::class, 'initiatePayment'])
            ->name('mpesa.pay');

        Route::get('/mpesa/status/{booking}', [MpesaController::class, 'checkStatus'])
            ->name('mpesa.status');

        // Dev-only: Simulate payment
        Route::post('/mpesa/simulate', [MpesaController::class, 'simulateSuccess'])
            ->name('mpesa.simulate');
    });

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/stalls/assign', [AdminDashboardController::class, 'createAssignment'])
            ->name('stalls.assign.create');

        Route::post('/stalls/assign', [AdminDashboardController::class, 'assignStall'])
            ->name('stalls.assign.store');

        // ── Stall Management (Admin-only, no trader booking) ─────────
        Route::get('/stalls', [AdminStallController::class, 'index'])
            ->name('stalls.index');

        Route::post('/stalls/{stall}/block', [AdminStallController::class, 'block'])
            ->name('stalls.block');

        Route::post('/stalls/{stall}/unblock', [AdminStallController::class, 'unblock'])
            ->name('stalls.unblock');

        Route::post('/stalls/{stall}/maintenance', [AdminStallController::class, 'markMaintenance'])
            ->name('stalls.maintenance');

        Route::patch('/traders/{user}/restrict', [AdminDashboardController::class, 'updateRestriction'])
            ->name('traders.restrict');

        Route::get('/booked-stalls', [AdminDashboardController::class, 'bookedStalls'])
            ->name('stalls.booked');

        Route::get('/traders/{user}/history', [AdminDashboardController::class, 'traderHistory'])
            ->name('traders.history');

        Route::get('/feedback', [AdminDashboardController::class, 'feedbackIndex'])
            ->name('feedback.index');

        Route::patch('/feedback/{id}/resolve', [AdminDashboardController::class, 'resolveFeedback'])
            ->name('feedback.resolve');

        // Admin manually prompts a trader to pay
        Route::post('/bookings/{booking}/prompt-payment', [AdminDashboardController::class, 'promptPayment'])
            ->name('bookings.prompt');
    });

/*
|--------------------------------------------------------------------------
| Officer Dashboard (Professional Clean Route)
|--------------------------------------------------------------------------
*/
Route::get('/officer/dashboard',
    [OfficerDashboardController::class, 'index']
)->middleware(['auth','officer'])
 ->name('officer.dashboard');

/*
|--------------------------------------------------------------------------
| Officer Violation Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth','officer'])
    ->prefix('officer')
    ->name('officer.')
    ->group(function () {

        Route::get('/violations',
            [ViolationController::class, 'index']
        )->name('violations.index');

        Route::get('/violations/create',
            [ViolationController::class, 'create']
        )->name('violations.create');

        Route::post('/violations',
            [ViolationController::class, 'store']
        )->name('violations.store');

        Route::get('/violations/{id}/preview',
            [ViolationController::class, 'preview']
        )->name('violations.preview');

        Route::post('/violations/{id}/approve',
            [ViolationController::class, 'approve']
        )->name('violations.approve');

        // ✅ Send violation email
        Route::post('/violations/{id}/send-email',
            [ViolationController::class, 'sendEmail']
        )->name('violations.sendEmail');

        // ✅ Download PDF
        Route::get('/violations/{id}/pdf',
            [ViolationController::class, 'downloadPdf']
        )->name('violations.pdf');

        // ✅ Regenerate letter via Gemini AI (AJAX)
        Route::post('/violations/{id}/regenerate',
            [ViolationController::class, 'regenerateLetter']
        )->name('violations.regenerate');

        // ✅ Standalone printable letter view
        Route::get('/violations/{id}/letter',
            [ViolationController::class, 'showLetter']
        )->name('violations.letter');
    });

/*
|--------------------------------------------------------------------------
| M-Pesa Callback (Public - No Auth)
|--------------------------------------------------------------------------
*/
Route::post('/mpesa/callback', [MpesaController::class, 'callback'])
    ->name('mpesa.callback');

/*
|--------------------------------------------------------------------------
| Chat Assistant API
|--------------------------------------------------------------------------
*/
Route::post('/chat', [\App\Http\Controllers\ChatController::class, 'handleChat'])->name('chat.handle');

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';