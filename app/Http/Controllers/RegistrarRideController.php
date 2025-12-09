<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ride;

class RegistrarRideController extends Controller
{
    /**
     * Registrar un nuevo ride
     */
    public function store(Request $request)
    {
        try {
            // Validar datos
            $validated = $request->validate([
                'usuario_id' => 'required|exists:usuarios,id',
                'vehiculo_id' => 'required|exists:vehiculos,id',
                'nombre' => 'required|string|max:100',
                'lugar_salida' => 'required|string|max:255',
                'lugar_llegada' => 'required|string|max:255',
                'fecha' => 'required|date',
                'hora' => 'required',
                'costo' => 'required|numeric|min:0',
                'cantidad_espacios' => 'required|integer|min:1',
            ]);

            // Validar capacidad del vehículo
            $vehiculo = \App\Models\Vehiculo::find($validated['vehiculo_id']);

            if ($validated['cantidad_espacios'] > $vehiculo->capacidad_asientos) {
                return response()->json([
                    'success' => false,
                    'message' => 'La cantidad de espacios no puede superar la capacidad del vehículo.'
                ]);
            }

            // Crear Ride
            Ride::create([
                'usuario_id' => $validated['usuario_id'],
                'vehiculo_id' => $validated['vehiculo_id'],
                'nombre' => $validated['nombre'],
                'lugar_salida' => $validated['lugar_salida'],
                'lugar_llegada' => $validated['lugar_llegada'],
                'fecha' => $validated['fecha'],
                'hora' => $validated['hora'],
                'costo' => $validated['costo'],
                'cantidad_espacios' => $validated['cantidad_espacios'],
                'activo' => 1,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Ride registrado exitosamente.'
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

}
