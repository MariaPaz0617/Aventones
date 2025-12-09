<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
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

            $validated = $request->validate([
                'id' => 'required|exists:vehiculos,id',
                'placa' => 'required|string|max:20',
                'color' => 'required|string|max:50',
                'marca' => 'required|string|max:100',
                'modelo' => 'required|string|max:100',
                'año' => 'required|integer|min:1900|max:'.(date('Y')+1),
                'capacidad_asientos' => 'required|integer|min:1|max:7',
                'foto' => 'nullable|image|max:4096',
            ]);

            $vehiculo = Vehiculo::find($validated['id']);

            $rutaFoto = $vehiculo->foto;

            // Si se sube nueva foto → borrar la anterior
            if ($request->hasFile('foto')) {

                if ($rutaFoto && Storage::disk('public')->exists($rutaFoto)) {
                    Storage::disk('public')->delete($rutaFoto);
                }

                $rutaFoto = $request->file('foto')->store('vehiculos','public');
            }

            // Actualizar datos
            $vehiculo->update([
                'placa' => $validated['placa'],
                'color' => $validated['color'],
                'marca' => $validated['marca'],
                'modelo' => $validated['modelo'],
                'año' => $validated['año'],
                'capacidad_asientos' => $validated['capacidad_asientos'],
                'foto' => $rutaFoto
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Vehículo actualizado correctamente',
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);

        }
    }

}
