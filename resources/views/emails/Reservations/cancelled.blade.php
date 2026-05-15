<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reserva Cancelada</title>
</head>
<body style="font-family: Arial, sans-serif; background: #f4f4f4; padding: 30px;">

    <div style="max-width: 600px; margin: auto; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,.08);">

        <div style="background: #6b7280; padding: 30px; text-align: center;">
            <h1 style="color: white; margin: 0;">
                Reserva Cancelada
            </h1>
        </div>

        <div style="padding: 30px; color: #374151;">

            <p>Hola <strong>{{ $reservation->user_name }}</strong>,</p>

            <p>
                Tu reserva del auditorio
                <strong>{{ $reservation->space->name }}</strong>
                ha sido cancelada.
            </p>

            <div style="background: #f9fafb; border-radius: 10px; padding: 20px; margin: 25px 0;">

                <p>
                    <strong>Fecha:</strong><br>
                    {{ \Carbon\Carbon::parse($reservation->start_time)->format('d/m/Y h:i A') }}
                </p>

                <p>
                    <strong>Estado:</strong>
                    Cancelada
                </p>

            </div>

            <p>
                Si necesitas una nueva reserva, puedes volver a solicitarla desde la plataforma.
            </p>

            <p style="margin-top: 30px;">
                Gracias por usar nuestro sistema de reservas.
            </p>

        </div>

    </div>

</body>
</html>