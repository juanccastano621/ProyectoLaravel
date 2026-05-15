<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class AdminReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::with('space')
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('Admin/Reservations/Index', [
            'reservations' => $reservations
        ]);
    }

    public function updateStatus(Request $request, Reservation $reservation)
    {
        $validated = $request->validate([
            'status' => 'required|in:aprobada,rechazada',
        ]);

        $reservation->update([
            'status' => $validated['status']
        ]);

        try {
            $fechaFormateada = Carbon::parse($reservation->start_time)->format('d/m/Y');
            $horaInicio = Carbon::parse($reservation->start_time)->format('H:i');
            $horaFin = Carbon::parse($reservation->end_time)->format('H:i');

            if ($validated['status'] === 'aprobada') {
                $asunto = '¡Tu reserva ha sido aprobada! 🎉';
                $mensaje = "Hola {$reservation->user_name},\n\n" .
                           "¡Excelentes noticias! Tu solicitud de reserva ha sido APROBADA.\n\n" .
                           "Detalles de la reserva:\n" .
                           "• Espacio: {$reservation->space->name}\n" .
                           "• Fecha: {$fechaFormateada}\n" .
                           "• Horario: {$horaInicio} - {$horaFin}\n\n" .
                           "Te esperamos en nuestras instalaciones.";
            } else {
                $asunto = 'Actualización sobre tu solicitud de reserva';
                $mensaje = "Hola {$reservation->user_name},\n\n" .
                           "Lamentamos informarte que tu solicitud de reserva para el día {$fechaFormateada} " .
                           "ha sido RECHAZADA debido a disponibilidad o políticas internas.\n\n" .
                           "Te invitamos a revisar nuestro catálogo para elegir otro horario o espacio.";
            }

            Mail::raw($mensaje, function ($mail) use ($reservation, $asunto) {
                $mail->to($reservation->user_email)
                     ->subject($asunto);
            });

        } catch (\Exception $e) {
            \Log::error("Error enviando correo de reserva: " . $e->getMessage());
            
            return redirect()->back()->with('success', 'El estado se actualizó a ' . $validated['status'] . ', pero el correo no pudo enviarse.');
        }

        return redirect()->back()->with('success', 'La reserva ha sido ' . $validated['status'] . ' con éxito y se envió la notificación.');
    }

    public function destroy(Reservation $reservation)
    {
        $reservation->delete();

        return redirect()->back()->with('success', 'La reserva ha sido eliminada permanentemente del sistema.');
    }

    public function show(Reservation $reservation)
    {
        return Inertia::render('Admin/Reservations/Show', [
            'reservation' => $reservation->load('space')
        ]);
    }
}