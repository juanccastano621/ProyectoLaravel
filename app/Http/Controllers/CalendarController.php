<?php

namespace App\Http\Controllers;

use App\Models\Space;
use App\Models\Reservation;
use App\Models\BlockedSlot;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Inertia\Inertia;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        $spaces = Space::where('is_active', true)->get();

        $selectedSpace = $request->space
            ? Space::where('slug', $request->space)->first()
            : $spaces->first();

        $date = $request->date
            ? Carbon::parse($request->date)
            : Carbon::now();

        $startOfWeek = $date->copy()->startOfWeek(Carbon::MONDAY);
        $endOfWeek = $date->copy()->endOfWeek(Carbon::SUNDAY);

        $reservations = [];
        $blocks = [];

        if ($selectedSpace) {
            $reservations = Reservation::with('space')
                ->where('space_id', $selectedSpace->id)
                ->where(function ($query) use ($startOfWeek, $endOfWeek) {
                    $query->whereBetween('start_time', [$startOfWeek, $endOfWeek])
                        ->orWhereBetween('end_time', [$startOfWeek, $endOfWeek]);
                })
                ->whereIn('status', ['pendiente', 'confirmada'])
                ->orderBy('start_time')
                ->get();

            $blocks = BlockedSlot::where('space_id', $selectedSpace->id)
                ->where(function ($query) use ($startOfWeek, $endOfWeek) {
                    $query->whereBetween('start_time', [$startOfWeek, $endOfWeek])
                        ->orWhereBetween('end_time', [$startOfWeek, $endOfWeek]);
                })
                ->orderBy('start_time')
                ->get();
        }

        return Inertia::render('Admin/Calendar', [
            'spaces' => $spaces,
            'selectedSpace' => $selectedSpace,
            'reservations' => $reservations,
            'blocks' => $blocks,
            'currentDate' => $date->format('Y-m-d')
        ]);
    }

    public function block(Request $request)
    {
        $validated = $request->validate([
            'space_id' => 'required|exists:spaces,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'reason' => 'nullable|string|max:255'
        ]);

        $overlapReservation = Reservation::where('space_id', $validated['space_id'])
            ->whereIn('status', ['pendiente', 'confirmada'])
            ->where(function ($query) use ($validated) {
                $query->where('start_time', '<', $validated['end_time'])
                    ->where('end_time', '>', $validated['start_time']);
            })
            ->exists();

        if ($overlapReservation) {
            return back()->withErrors([
                'error' => 'Existe una reserva en ese horario.'
            ]);
        }

        $overlapBlock = BlockedSlot::where('space_id', $validated['space_id'])
            ->where(function ($query) use ($validated) {
                $query->where('start_time', '<', $validated['end_time'])
                    ->where('end_time', '>', $validated['start_time']);
            })
            ->exists();

        if ($overlapBlock) {
            return back()->withErrors([
                'error' => 'Ese horario ya está bloqueado.'
            ]);
        }

        BlockedSlot::create($validated);

        return back()->with('success', 'Horario bloqueado correctamente.');
    }

    public function unblock(BlockedSlot $blockedSlot)
    {
        $blockedSlot->delete();

        return back()->with('success', 'Bloqueo eliminado correctamente.');
    }
}