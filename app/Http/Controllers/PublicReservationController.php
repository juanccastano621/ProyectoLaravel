<?php

namespace App\Http\Controllers;

use App\Models\Space;
use App\Models\Reservation;
use App\Models\BlockedSlot;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PublicReservationController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'space_id' => 'required|exists:spaces,id',
            'start_time' => 'required|date|after:now',
            'end_time' => 'required|date|after:start_time',
            'user_name' => 'required|string|max:255',
            'user_email' => 'required|email',
        ]);

        $space = Space::findOrFail($request->space_id);
        $start = Carbon::parse($request->start_time);
        $end = Carbon::parse($request->end_time);

        $dayOfWeek = $start->dayOfWeek;
        $isOpen = $space->availabilities()
            ->where('day_of_week', $dayOfWeek)
            ->where('start_time', '<=', $start->format('H:i:s'))
            ->where('end_time', '>=', $end->format('H:i:s'))
            ->exists();

        if (!$isOpen) {
            return redirect()->back()->withErrors(['error' => 'El espacio no está disponible en ese horario semanal.']);
        }

        $isBlocked = $space->blockedSlots()
            ->where(function ($query) use ($start, $end) {
                $query->whereBetween('start_time', [$start, $end])
                      ->orWhereBetween('end_time', [$start, $end]);
            })->exists();

        if ($isBlocked) {
            return redirect()->back()->withErrors(['error' => 'El horario está bloqueado por mantenimiento o evento interno.']);
        }

        $overlap = Reservation::where('space_id', $space->id)
            ->whereIn('status', ['pendiente', 'confirmada'])
            ->where(function ($query) use ($start, $end) {
                $query->whereBetween('start_time', [$start, $end])
                      ->orWhereBetween('end_time', [$start, $end]);
            })->exists();

        if ($overlap) {
            return redirect()->back()->withErrors(['error' => 'Ya existe una reserva para este horario.']);
        }

        Reservation::create([
            'space_id' => $space->id,
            'start_time' => $start,
            'end_time' => $end,
            'user_name' => $request->user_name,
            'user_email' => $request->user_email,
            'status' => 'pendiente',
        ]);

        return redirect()->back()->with('success', 'Reserva solicitada. Espera la confirmación por correo.');
    }
}