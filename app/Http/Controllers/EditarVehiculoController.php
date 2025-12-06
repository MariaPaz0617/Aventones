<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehiculo;

class EditarVehiculoController extends Controller
{
    /**
     * Actualiza un vehículo existente.
     */
    public function update(Request $request)
    {
        try {
            // Validación
            $validated = $request->validate([
                'id' => 'required|exists:vehiculos,id',
                'placa' => 'required|string|max:20',
                'color' => 'required|string|max:50',
                'marca' => 'required|string|max:100',
                'modelo' => 'required|string|max:100',
                'año' => 'required|integer|min:1900|max:'.(date('Y')+1),
                'capacidad_asientos' => 'required|integer|min:1',
                'color_modificado' => 'nullable|in:0,1',
                'foto_actual' => 'nullable|string',
                'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:4096'
            ]);

            // Buscar vehículo
            $vehiculo = Vehiculo::find($validated['id']);

            if (!$vehiculo) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vehículo no encontrado.'
                ]);
            }

            // Si cambia el color → debe subir nueva imagen
            if ($request->color_modificado == "1" && !$request->hasFile('foto')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Debes subir una nueva imagen si modificas el color.'
                ]);
            }

            // Procesar imagen
            $foto = $request->foto_actual; // mantener actual si no cambia

            if ($request->hasFile('foto')) {
                $nombreArchivo = uniqid("vehiculo_") . "." . $request->file('foto')->extension();
                $request->file('foto')->move(public_path('img'), $nombreArchivo);
                $foto = $nombreArchivo;
            }

            // Actualizar datos
            $vehiculo->update([
                'placa' => $validated['placa'],
                'color' => $validated['color'],
                'marca' => $validated['marca'],
                'modelo' => $validated['modelo'],
                'año' => $validated['año'],
                'capacidad_asientos' => $validated['capacidad_asientos'],
                'foto' => $foto
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Vehículo actualizado correctamente.'
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }
}
