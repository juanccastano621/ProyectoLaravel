<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PublicSpaceController;
use App\Http\Controllers\PublicReservationController;
use App\Http\Controllers\AdminReservationController;
use App\Http\Controllers\AdminSpaceController;
use App\Http\Controllers\CalendarController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicSpaceController::class, 'index'])->name('spaces.index');
Route::get('/espacios/{slug}', [PublicSpaceController::class, 'show'])->name('spaces.show');
Route::post('/reservations', [PublicReservationController::class, 'store'])->name('reservations.store');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('admin')->name('admin.')->group(function () {

        Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar');
        Route::resource('spaces', AdminSpaceController::class);

        Route::post('/calendar/block', [CalendarController::class, 'block'])
            ->name('calendar.block');

        Route::delete('/calendar/block/{blockedSlot}', [CalendarController::class, 'unblock'])
            ->name('calendar.unblock');

        Route::get('/reservas', [AdminReservationController::class, 'index'])
            ->name('reservations.index');

        Route::post('/reservas/{reservation}/accept', [AdminReservationController::class, 'accept'])
            ->name('reservations.accept');

        Route::post('/reservas/{reservation}/reject', [AdminReservationController::class, 'reject'])
            ->name('reservations.reject');

        Route::post('/reservas/{reservation}/cancel', [AdminReservationController::class, 'cancel'])
            ->name('reservations.cancel');

        Route::delete('/reservas/{reservation}', [AdminReservationController::class, 'destroy'])
            ->name('reservations.destroy');
    });
});