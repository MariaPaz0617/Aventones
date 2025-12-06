<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehiculo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RegistrarVehiculoController extends Controller
{
    /**
     * Registrar un nuevo vehículo
     */
    public function store(Request $request)
    {
        try {
            // Validación de datos
            $validated = $request->validate([
                'usuario_id' => 'required|exists:usuarios,id',
                'placa' => 'required|string|max:20',
                'color' => 'required|string|max:50',
                'marca' => 'required|string|max:50',
                'modelo' => 'required|string|max:50',
                'año' => 'required|integer|min:1900|max:2099',
                'capacidad_asientos' => 'required|integer|min:1|max:50',
                'foto' => 'nullable|image|max:2048', // 2MB
            ]);

            // Manejo de imagen
            $nombreFoto = null;
            if ($request->hasFile('foto')) {
                $nombreFoto = $request->file('foto')->store('vehiculos', 'public');
            }

            // Crear vehículo
            Vehiculo::create([
                'usuario_id' => $validated['usuario_id'],
                'placa' => $validated['placa'],
                'color' => $validated['color'],
                'marca' => $validated['marca'],
                'modelo' => $validated['modelo'],
                'año' => $validated['año'],
                'capacidad_asientos' => $validated['capacidad_asientos'],
                'foto' => $nombreFoto,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Vehículo registrado correctamente'
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }
}
