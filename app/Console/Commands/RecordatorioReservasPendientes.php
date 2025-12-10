<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reserva;
use App\Mail\RecordatorioReservaMail;
use Illuminate\Support\Facades\Mail;

class RecordatorioReservasPendientes extends Command
{
    protected $signature = 'reservas:recordatorio';
    protected $description = 'Enviar recordatorios de reservas pendientes con más de 20 segundos sin respuesta';

    public function handle()
    {
        $this->info("Buscando reservas pendientes sin gestionar...");

        // 1. Buscar reservas pendientes > 20 segundos
        $reservas = Reserva::where('estado', 'PENDIENTE')
            ->where('created_at', '<=', now()->subSeconds(20))
            ->where('notificado', 0)
            ->with('ride.usuario') // traer chofer
            ->get();

        if ($reservas->isEmpty()) {
            $this->info("No hay reservas pendientes para notificar.");
            return;
        }

        // 2. Enviar correos
        foreach ($reservas as $reserva) {

            $chofer = $reserva->ride->usuario;

            if (!$chofer || !$chofer->email) {
                continue;
            }

            // Enviar correo
            Mail::to($chofer->email)->send(new RecordatorioReservaMail($reserva));

            // Marcar notificado
            $reserva->notificado = 1;
            $reserva->save();

            $this->info("Notificación enviada al chofer: {$chofer->email}");
        }
    }
}
