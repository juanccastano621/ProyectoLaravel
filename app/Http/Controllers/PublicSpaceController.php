<?php

namespace App\Http\Controllers;

use App\Models\Space;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

class PublicSpaceController extends Controller
{
    public function index(Request $request)
    {
        $query = Space::where('is_active', true);

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('capacity')) {
            $query->where(
                'capacity',
                '>=',
                $request->capacity
            );
        }

        if ($request->filled('max_price')) {
            $query->where(
                'price_per_hour',
                '<=',
                $request->max_price
            );
        }

        return Inertia::render('Public/Spaces/Index', [
            'spaces' => $query->get(),

            'filters' => [
                'search' => $request->search ?? '',
                'type' => '',
                'capacity' => $request->capacity ?? '',
                'max_price' => $request->max_price ?? '',
            ]
        ]);
    }

    public function show(Request $request, $slug)
    {
        $space = Space::where('slug', $slug)
            ->with([
                'availabilities',
                'blockedSlots',
                'reservations' => function ($query) {
                    $query->whereIn('status', [
                        'pendiente',
                        'confirmada'
                    ]);
                }
            ])
            ->firstOrFail();

        $date = $request->input(
            'date',
            now()->format('Y-m-d')
        );

        $dayOfWeek = date('N', strtotime($date));

        $availability = $space->availabilities
            ->where('day_of_week', $dayOfWeek)
            ->first();

        $availableSlots = [];

        if ($availability) {

            $start = Carbon::parse(
                $availability->start_time
            );

            $end = Carbon::parse(
                $availability->end_time
            );

            while (
                $start->copy()->addHour() <= $end
            ) {

                $slotStart = $start->format('H:i');
                $slotEnd = $start
                    ->copy()
                    ->addHour()
                    ->format('H:i');

                $fullStart =
                    $date . ' ' . $slotStart . ':00';

                $isOccupied = $space->reservations
                    ->contains(function ($reservation) use ($fullStart) {

                        return Carbon::parse(
                            $reservation->start_time
                        )->format('Y-m-d H:i:s')
                            === $fullStart;
                    });

                $isBlocked = $space->blockedSlots
                    ->contains(function ($blocked) use ($date, $slotStart) {

                        return Carbon::parse(
                            $blocked->start_time
                        )->format('Y-m-d')
                            === $date
                            &&
                            Carbon::parse(
                                $blocked->start_time
                            )->format('H:i')
                            === $slotStart;
                    });

                $isPast = Carbon::parse(
                    $fullStart
                )->isPast();

                if (
                    !$isOccupied &&
                    !$isBlocked &&
                    !$isPast
                ) {
                    $availableSlots[] = [
                        'start' => $slotStart,
                        'end' => $slotEnd,
                        'datetime' => $fullStart
                    ];
                }

                $start->addHour();
            }
        }

        return Inertia::render(
            'Public/Spaces/Show',
            [
                'space' => $space,
                'selectedDate' => $date,
                'availableSlots' => $availableSlots
            ]
        );
    }
}