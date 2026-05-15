<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reserva Rechazada</title>
</head>
<body style="font-family: Arial, sans-serif; background: #f4f4f4; padding: 30px;">

    <div style="max-width: 600px; margin: auto; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,.08);">

        <div style="background: #dc2626; padding: 30px; text-align: center;">
            <h1 style="color: white; margin: 0;">
                Solicitud Rechazada
            </h1>
        </div>

        <div style="padding: 30px; color: #374151;">

            <p>Hola <strong>{{ $reservation->user_name }}</strong>,</p>

            <p>
                Lamentamos informarte que tu solicitud de reserva para el auditorio
                <strong>{{ $reservation->space->name }}</strong>
                no pudo ser aprobada.
            </p>

            <div style="background: #f9fafb; border-radius: 10px; padding: 20px; margin: 25px 0;">

                <p>
                    <strong>Fecha solicitada:</strong><br>
                    {{ \Carbon\Carbon::parse($reservation->start_time)->format('d/m/Y h:i A') }}
                </p>

                <p>
                    <strong>Estado:</strong>
                    Rechazada
                </p>

            </div>

            <p>
                Puedes intentar reservar otro horario disponible.
            </p>

            <p style="margin-top: 30px;">
                Gracias por usar nuestro sistema de reservas.
            </p>

        </div>

    </div>

</body>
</html>