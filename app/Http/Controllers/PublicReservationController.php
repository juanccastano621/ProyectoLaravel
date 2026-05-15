<?php

namespace App\Http\Controllers;

use App\Models\Space;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

class PublicReservationController extends Controller
{
    public function create(Request $request)
    {
        $slug = $request->input('space');
        $start = $request->input('start');

        $space = Space::where('slug', $slug)->firstOrFail();
        
        $startTime = Carbon::parse($start);
        $slotDuration = (int) env('RESERVATION_SLOT_MINUTES', 60);
        $endTime = $startTime->copy()->addMinutes($slotDuration);

        return Inertia::render('Public/Spaces/Reservations/Create', [
            'space' => $space,
            'startTime' => $startTime->toDateTimeString(),
            'endTime' => $endTime->toDateTimeString(),
            'displayDate' => $startTime->format('d/m/Y'),
            'displayTime' => $startTime->format('H:i') . ' - ' . $endTime->format('H:i')
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'space_id' => 'required|exists:spaces,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'user_name' => 'required|string|max:255',
            'user_email' => 'required|email|max:255',
            'notes' => 'nullable|string',
        ]);

        Reservation::create([
            'space_id' => $validated['space_id'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'user_name' => $validated['user_name'],
            'user_email' => $validated['user_email'],
            'notes' => $validated['notes'],
            'status' => 'pendiente',
        ]);

        return redirect()->route('spaces.index')->with('success', '¡Reserva solicitada con éxito! Está pendiente de aprobación.');
    }
}