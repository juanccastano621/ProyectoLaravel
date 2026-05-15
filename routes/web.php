<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicSpaceController;
use App\Http\Controllers\PublicReservationController;
use App\Http\Controllers\AdminReservationController;
use App\Http\Controllers\AdminSpaceController;
use Inertia\Inertia;

Route::get('/', [PublicSpaceController::class, 'index'])->name('spaces.index');

Route::get('/spaces', [PublicSpaceController::class, 'index']);

Route::get('/spaces/{space:slug}', [PublicSpaceController::class, 'show'])->name('spaces.show');

Route::get('/reservations/new', [PublicReservationController::class, 'create'])->name('reservations.create');
Route::post('/reservations', [PublicReservationController::class, 'store'])->name('reservations.store');

Route::get('/espacios', [PublicSpaceController::class, 'index'])->name('spaces.index');
Route::get('/espacios/{slug}', [PublicSpaceController::class, 'show'])->name('spaces.show');

Route::post('/reservas', [PublicReservationController::class, 'store'])->name('reservations.store');

Route::get('/reservas/crear', [PublicReservationController::class, 'create'])->name('reservations.create');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::get('/admin/reservations', [AdminReservationController::class, 'index'])->name('admin.reservations.index');
    Route::post('/admin/reservations/{reservation}/accept', [AdminReservationController::class, 'accept'])->name('admin.reservations.accept');
    Route::post('/admin/reservations/{reservation}/reject', [AdminReservationController::class, 'reject'])->name('admin.reservations.reject');
    Route::post('/admin/reservations/{reservation}/cancel', [AdminReservationController::class, 'cancel'])->name('admin.reservations.cancel');
    
    Route::resource('/admin/spaces', AdminSpaceController::class)->names('admin.spaces');
});