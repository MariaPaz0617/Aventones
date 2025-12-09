<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ride;

class ListarRidesController extends Controller
{
    public function listar(Request $request)
    {
        try {
            $validated = $request->validate([
                'usuario_id' => 'required|exists:usuarios,id'
            ]);

            // Traer rides activos del chofer
            $rides = Ride::where('usuario_id', $validated['usuario_id'])
                         ->where('activo', 1)
                         ->with('vehiculo') // Importante para que JS pueda mostrar marca/modelo
                         ->get();

            return response()->json([
                'success' => true,
                'rides' => $rides
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => "Error: " . $e->getMessage()
            ]);
        }
    }
}
