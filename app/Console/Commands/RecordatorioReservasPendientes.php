<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reserva;
use App\Mail\RecordatorioReservaMail;
use Illuminate\Support\Facades\Mail;

class RecordatorioReservasPendientes extends Command
{
    protected $signature = 'reservas:recordatorio';
    protected $description = 'Enviar recordatorios de reservas pendientes que lleven X minutos sin respuesta';

    public function handle()
    {
        $this->info("Buscando reservas pendientes sin gestionar...");

        // Tiempo máximo sin respuesta (puedes cambiarlo)
        $minutos = 1;

        // Buscar reservas PENDIENTES con más de X minutos sin respuesta
        $reservas = Reserva::where('estado', 'PENDIENTE')
            ->where('fecha_solicitud', '<=', now()->subMinutes($minutos))
            ->where('notificado', 0)
            ->with('ride.usuario') // extraer chofer
            ->get();

        if ($reservas->isEmpty()) {
            $this->info("No hay reservas pendientes para notificar.");
            return;
        }

        foreach ($reservas as $reserva) {

            $chofer = $reserva->ride->usuario;

            if (!$chofer || !$chofer->email) {
                continue;
            }

            // Enviar el correo
            Mail::to($chofer->email)->send(new RecordatorioReservaMail($reserva));

            // Marcar como notificado
            $reserva->notificado = 1;
            $reserva->save();

            $this->info("Correo enviado a: {$chofer->email}");
        }
    }
}
