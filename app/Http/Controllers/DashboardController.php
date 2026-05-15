<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Space;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
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

        $stats = [
            'pending' => Reservation::where(
                'status',
                'pendiente'
            )->count(),

            'approved' => Reservation::where(
                'status',
                'confirmada'
            )->count(),

            'cancelled' => Reservation::whereIn(
                'status',
                [
                    'cancelada',
                    'rechazada'
                ]
            )->count(),

            'finalized' => Reservation::where(
                'status',
                'finalizada'
            )->count(),

            'total_spaces' => Space::count(),

            'total_reservations' => Reservation::count(),

            'month_reservations' => Reservation::whereMonth(
                'created_at',
                now()->month
            )
            ->whereYear(
                'created_at',
                now()->year
            )
            ->count(),

            'most_reserved_space' => Str::limit(
                $this->getMostReservedSpace(),
                30
            )
        ];

        $recentReservations = Reservation::with(
            'space'
        )
        ->latest()
        ->take(5)
        ->get();

        $monthlyReservations = Reservation::selectRaw('
                EXTRACT(MONTH FROM created_at) as month_number,
                COUNT(*) as total
            ')
            ->whereYear(
                'created_at',
                now()->year
            )
            ->groupBy('month_number')
            ->pluck(
                'total',
                'month_number'
            );

        $chartData = collect(range(1, 12))
            ->map(function ($month) use ($monthlyReservations) {

                return [
                    'month' => Carbon::create()
                        ->month($month)
                        ->locale('es')
                        ->translatedFormat('M'),

                    'total' => $monthlyReservations[$month] ?? 0
                ];
            });

        return Inertia::render(
            'Dashboard',
            [
                'stats' => $stats,
                'recentReservations' => $recentReservations,
                'chartData' => $chartData
            ]
        );
    }

    private function getMostReservedSpace()
    {
        $space = Reservation::select(
                'space_id',
                DB::raw('COUNT(*) as total')
            )
            ->with('space')
            ->groupBy('space_id')
            ->orderByDesc('total')
            ->first();

        return $space?->space?->name
            ?? 'Sin datos';
    }
}