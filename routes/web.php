<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StallController;
use App\Http\Controllers\Trader\BookingController;
use App\Http\Controllers\Trader\StallController as TraderStallController;
use App\Http\Controllers\Trader\DashboardController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Officer\DashboardController as OfficerDashboard;

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
| Restricted Account Page
|--------------------------------------------------------------------------
*/
Route::get('/account/restricted', function () {
    if (!auth()->check() || auth()->user()->account_restriction === 'none') {
        return redirect('/');
    }
    return view('errors.restricted');
})->name('account.restricted')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Profile Routes (Auth Only)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
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
Route::middleware(['auth'])
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

        // 1. Dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

        // 2. ✅ Admin Manual Assignment Routes (Moved ABOVE resource)
        Route::get('/stalls/assign', [AdminDashboardController::class, 'createAssignment'])
            ->name('stalls.assign.create');

        Route::post('/stalls/assign', [AdminDashboardController::class, 'assignStall'])
            ->name('stalls.assign.store');

        // 3. Stall Resource Route
        Route::resource('stalls', StallController::class);

        // Restrict / Warn / Block / Ban Trader
        Route::patch('/traders/{user}/restrict',
            [AdminDashboardController::class, 'updateRestriction'])
            ->name('traders.restrict');

        // View All Currently Booked Stalls
        Route::get('/booked-stalls',
            [AdminDashboardController::class, 'bookedStalls'])
            ->name('stalls.booked');

        // View Specific Trader Booking History
        Route::get('/traders/{user}/history',
            [AdminDashboardController::class, 'traderHistory'])
            ->name('traders.history');
    });

/*
|--------------------------------------------------------------------------
| Officer Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'officer'])
    ->prefix('officer')
    ->name('officer.')
    ->group(function () {
        Route::get('/dashboard', [OfficerDashboard::class, 'index'])
            ->name('dashboard');
    });

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';