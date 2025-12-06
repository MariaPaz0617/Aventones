<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ride;

class EditarRideController extends Controller
{
    /**
     * Actualiza un Ride existente.
     */
    public function update(Request $request)
    {
        try {
            // Validar datos
            $validated = $request->validate([
                'id' => 'required|exists:rides,id',
                'usuario_id' => 'required|exists:usuarios,id',
                'vehiculo_id' => 'required|exists:vehiculos,id',
                'nombre' => 'required|string|max:150',
                'lugar_salida' => 'required|string|max:255',
                'lugar_llegada' => 'required|string|max:255',
                'fecha' => 'required|date',
                'hora' => 'required',
                'costo' => 'required|numeric',
                'cantidad_espacios' => 'required|integer|min:1',
            ]);

            // Buscar ride del usuario
            $ride = Ride::where('id', $validated['id'])
                        ->where('usuario_id', $validated['usuario_id'])
                        ->first();

            if (!$ride) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ride no encontrado o no pertenece al usuario.',
                ]);
            }

            // Actualizar el ride
            $ride->update([
                'vehiculo_id' => $validated['vehiculo_id'],
                'nombre' => $validated['nombre'],
                'lugar_salida' => $validated['lugar_salida'],
                'lugar_llegada' => $validated['lugar_llegada'],
                'fecha' => $validated['fecha'],
                'hora' => $validated['hora'],
                'costo' => $validated['costo'],
                'cantidad_espacios' => $validated['cantidad_espacios'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Ride actualizado exitosamente.'
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }
}
