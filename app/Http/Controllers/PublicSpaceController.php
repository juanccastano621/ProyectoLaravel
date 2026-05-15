<?php

namespace App\Http\Controllers;

use App\Models\Space;
use App\Models\Reservation;
use App\Models\BlockedSlot;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

class PublicSpaceController extends Controller
{
    public function index()
    {
        $spaces = Space::where('is_active', true)->get();
        return Inertia::render('Public/Spaces/Index', [
            'spaces' => $spaces
        ]);
    }

    public function show(Request $request, $slug)
    {
        $space = Space::where('slug', $slug)->where('is_active', true)->firstOrFail();
        
        $dateString = $request->input('date', Carbon::today()->toDateString());
        $date = Carbon::parse($dateString);
        $dayOfWeek = $date->dayOfWeek; 

        $availability = $space->availabilities()->where('day_of_week', $dayOfWeek)->first();
        $availableSlots = [];
        
        if ($availability) {
            $slotDuration = (int) env('RESERVATION_SLOT_MINUTES', 60);
            $start = Carbon::parse($dateString . ' ' . $availability->start_time);
            $end = Carbon::parse($dateString . ' ' . $availability->end_time);
            
            $reservations = Reservation::where('space_id', $space->id)
                ->whereDate('start_time', $dateString)
                ->whereNotIn('status', ['rechazada', 'cancelada'])
                ->get();
                
            $blocked = BlockedSlot::where('space_id', $space->id)
                ->whereDate('start_time', $dateString)
                ->get();
            
            while ($start->copy()->addMinutes($slotDuration)->lte($end)) {
                $slotStart = $start->copy();
                $slotEnd = $start->copy()->addMinutes($slotDuration);
                $isAvailable = true;
                
                if ($slotStart->isPast()) {
                    $isAvailable = false;
                }
                
                foreach ($reservations as $res) {
                    if ($slotStart->lt(Carbon::parse($res->end_time)) && $slotEnd->gt(Carbon::parse($res->start_time))) {
                        $isAvailable = false; break;
                    }
                }
                
                foreach ($blocked as $block) {
                    if ($slotStart->lt(Carbon::parse($block->end_time)) && $slotEnd->gt(Carbon::parse($block->start_time))) {
                        $isAvailable = false; break;
                    }
                }
                
                if ($isAvailable) {
                    $availableSlots[] = [
                        'start' => $slotStart->format('H:i'),
                        'end' => $slotEnd->format('H:i'),
                        'datetime' => $slotStart->toDateTimeString()
                    ];
                }
                $start->addMinutes($slotDuration);
            }
        }
        
        return Inertia::render('Public/Spaces/Show', [
            'space' => $space,
            'selectedDate' => $dateString,
            'availableSlots' => $availableSlots
        ]);
    }
}