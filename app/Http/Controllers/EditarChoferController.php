<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;

class EditarChoferController extends Controller
{
    /**
     * Actualiza los datos del chofer.
     */
    public function update(Request $request)
    {
        try {
            // Validación SIN email
            $validated = $request->validate([
                'id' => 'required|exists:usuarios,id',
                'nombre' => 'required|string|max:100',
                'apellido' => 'required|string|max:100',
                'telefono' => 'required|string|max:50',
            ]);

            $usuario = Usuario::find($validated['id']);

            $usuario->update([
                'nombre' => $validated['nombre'],
                'apellido' => $validated['apellido'],
                'telefono' => $validated['telefono'],
                'actualizado_en' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Datos actualizados correctamente.',
                'usuario' => $usuario
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }
}