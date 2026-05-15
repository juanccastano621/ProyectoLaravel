<?php

namespace App\Http\Controllers;

use App\Mail\ReservationCreatedMail;
use App\Models\BlockedSlot;
use App\Models\Reservation;
use App\Models\Space;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PublicReservationController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'space_id' => 'required|exists:spaces,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'user_name' => 'required|string|max:255',
            'user_email' => 'required|email|max:255',
            'notes' => 'nullable|string|max:1000'
        ]);

        $space = Space::findOrFail(
            $validated['space_id']
        );

        $start = Carbon::parse(
            $validated['start_time']
        );

        $end = Carbon::parse(
            $validated['end_time']
        );

        if ($start->isPast()) {

            return back()->withErrors([
                'error' => 'No puedes reservar una hora pasada.'
            ]);
        }

        $dayOfWeek = $start->dayOfWeekIso;

        $isOpen = $space->availabilities()
            ->where(
                'day_of_week',
                $dayOfWeek
            )
            ->where(
                'start_time',
                '<=',
                $start->format('H:i:s')
            )
            ->where(
                'end_time',
                '>=',
                $end->format('H:i:s')
            )
            ->exists();

        if (!$isOpen) {

            return back()->withErrors([
                'error' => 'El auditorio no está disponible en ese horario.'
            ]);
        }

        $isBlocked = BlockedSlot::where(
            'space_id',
            $space->id
        )
        ->where(function ($query) use ($start, $end) {

            $query->where(
                'start_time',
                '<',
                $end
            )
            ->where(
                'end_time',
                '>',
                $start
            );
        })
        ->exists();

        if ($isBlocked) {

            return back()->withErrors([
                'error' => 'Este horario ha sido bloqueado por la administración.'
            ]);
        }

        $overlap = Reservation::where(
            'space_id',
            $space->id
        )
        ->whereIn('status', [
            'pendiente',
            'confirmada'
        ])
        ->where(function ($query) use ($start, $end) {

            $query->where(
                'start_time',
                '<',
                $end
            )
            ->where(
                'end_time',
                '>',
                $start
            );
        })
        ->exists();

        if ($overlap) {

            return back()->withErrors([
                'error' => 'Ya existe una reserva en este horario.'
            ]);
        }

        $reservation = Reservation::create([
            'space_id' => $validated['space_id'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'user_name' => $validated['user_name'],
            'user_email' => $validated['user_email'],
            'notes' => $validated['notes'] ?? null,
            'status' => 'pendiente'
        ]);

        Mail::to(
            $reservation->user_email
        )->send(
            new ReservationCreatedMail(
                $reservation
            )
        );

        return redirect()
            ->route('spaces.index')
            ->with(
                'success',
                'Reserva solicitada correctamente.'
            );
    }
}