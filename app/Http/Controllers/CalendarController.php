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

        $date = $request->date ? Carbon::parse($request->date) : Carbon::now();
        $startOfWeek = $date->copy()->startOfWeek(Carbon::MONDAY);
        $endOfWeek = $date->copy()->endOfWeek(Carbon::SUNDAY);

        $reservations = [];
        $blocks = [];

        if ($selectedSpace) {
            $reservations = Reservation::with('user')
                ->where('space_id', $selectedSpace->id)
                ->where(function ($query) use ($startOfWeek, $endOfWeek) {
                    $query->whereBetween('start_time', [$startOfWeek, $endOfWeek])
                          ->orWhereBetween('end_time', [$startOfWeek, $endOfWeek]);
                })
                ->whereIn('status', ['pendiente', 'confirmada'])
                ->get();

            $blocks = BlockedSlot::where('space_id', $selectedSpace->id)
                ->where(function ($query) use ($startOfWeek, $endOfWeek) {
                    $query->whereBetween('start_time', [$startOfWeek, $endOfWeek])
                          ->orWhereBetween('end_time', [$startOfWeek, $endOfWeek]);
                })
                ->get();
        }

        return Inertia::render('Admin/Calendar', [
            'spaces' => $spaces,
            'selectedSpace' => $selectedSpace,
            'reservations' => $reservations,
            'blocks' => $blocks,
            'currentDate' => $date->format('Y-m-d'),
            'weekRange' => [
                'start' => $startOfWeek->format('d M'),
                'end' => $endOfWeek->format('d M Y')
            ]
        ]);
    }
}