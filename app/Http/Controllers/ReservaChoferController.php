<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use Illuminate\Http\Request;

class ReservaChoferController extends Controller
{
    public function listar(Request $request)
    {
        $request->validate([
            'chofer_id' => 'required|exists:usuarios,id'
        ]);

        // Reservas de rides del chofer
        $reservas = Reserva::with(['ride', 'pasajero'])
            ->whereHas('ride', function ($q) use ($request) {
                $q->where('usuario_id', $request->chofer_id);
            })
            ->orderBy('fecha_solicitud', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'reservas' => $reservas
        ]);
    }
}
