<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminReservationController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Reservations/Index', [
            'reservations' => Reservation::with(['user', 'space'])->latest()->get()
        ]);
    }

    public function accept(Reservation $reservation)
    {
        $reservation->update(['status' => 'confirmada']);
        return redirect()->back()->with('success', 'Reserva confirmada.');
    }

    public function reject(Reservation $reservation)
    {
        $reservation->update(['status' => 'rechazada']);
        return redirect()->back()->with('success', 'Reserva rechazada.');
    }

    public function cancel(Reservation $reservation)
    {
        $reservation->update(['status' => 'cancelada']);
        return redirect()->back()->with('success', 'Reserva cancelada.');
    }

    public function destroy(Reservation $reservation)
    {
        $reservation->delete();
        return redirect()->back()->with('success', 'Reserva eliminada.');
    }
}