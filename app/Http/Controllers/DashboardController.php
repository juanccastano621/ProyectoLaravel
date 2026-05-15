<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Space;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'pending' => Reservation::where('status', 'pendiente')->count(),
            'approved' => Reservation::whereIn('status', ['confirmada', 'aprobada'])->count(),
            'total_spaces' => Space::count(),
            'total_reservations' => Reservation::count(),
        ];

        $recentReservations = Reservation::with('space')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return Inertia::render('Dashboard', [
            'stats' => $stats,
            'recentReservations' => $recentReservations
        ]);
    }
}