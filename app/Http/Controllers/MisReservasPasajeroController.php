<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use Illuminate\Http\Request;

class MisReservasPasajeroController extends Controller
{
    public function listar(Request $request)
    {
        $reservas = Reserva::with('ride')
            ->where('pasajero_id', $request->pasajero_id)
            ->get();

        return response()->json([
            'success' => true,
            'reservas' => $reservas
        ]);
    }

    public function cancelar(Request $request)
    {
        $reserva = Reserva::find($request->id);

        if (!$reserva || $reserva->estado !== 'PENDIENTE') {
            return response()->json([
                'success' => false,
                'message' => 'No se puede cancelar esta reserva.'
            ]);
        }

        // Restaurar espacios al ride
        $ride = $reserva->ride;
        $ride->espacios_disponibles += $reserva->cantidad_asientos;
        $ride->save();

        $reserva->estado = 'CANCELADA';
        $reserva->save();

        return response()->json([
            'success' => true,
            'message' => 'Reserva cancelada correctamente.'
        ]);
    }
}
