<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Space;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminReservationController extends Controller
{
    public function index(Request $request)
            {
        Reservation::where(
            'status',
            'confirmada'
        )
        ->where(
            'end_time',
            '<',
            now()
        )
        ->update([
            'status' => 'finalizada'
        ]);

        $query = Reservation::with([
            'space'
        ])->latest();

        if (
            $request->filled('status') &&
            $request->status !== 'todos'
        ) {
            $query->where(
                'status',
                $request->status
            );
        }

        if (
            $request->filled('space') &&
            $request->space !== 'todos'
        ) {
            $query->whereHas('space', function ($q) use ($request) {
                $q->where(
                    'slug',
                    $request->space
                );
            });
        }

        if ($request->filled('date')) {

            $query->whereDate(
                'start_time',
                $request->date
            );
        }

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where(
                    'user_name',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'user_email',
                    'like',
                    "%{$search}%"
                );
            });
        }

        return Inertia::render(
            'Admin/Reservations/Index',
            [
                'reservations' => $query->get(),

                'spaces' => Space::orderBy('name')->get(),

                'filters' => [
                    'status' => $request->status ?? 'todos',
                    'space' => $request->space ?? 'todos',
                    'date' => $request->date ?? '',
                    'search' => $request->search ?? '',
                ]
            ]
        );
    }

    public function accept(Reservation $reservation)
    {
        $reservation->update([
            'status' => 'confirmada'
        ]);

        return back()->with(
            'success',
            'Reserva confirmada.'
        );
    }

    public function reject(Reservation $reservation)
    {
        $reservation->update([
            'status' => 'rechazada'
        ]);

        return back()->with(
            'success',
            'Reserva rechazada.'
        );
    }

    public function cancel(Reservation $reservation)
    {
        $reservation->update([
            'status' => 'cancelada'
        ]);

        return back()->with(
            'success',
            'Reserva cancelada.'
        );
    }

    public function destroy(Reservation $reservation)
    {
        $reservation->delete();

        return back()->with(
            'success',
            'Reserva eliminada.'
        );
    }
}