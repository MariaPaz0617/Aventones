<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;

class EditarChoferController extends Controller
{
    /**
     * Actualiza los datos de un chofer (usuario).
     */
    public function update(Request $request)
    {
        try {
            // Validar los datos recibidos
            $validated = $request->validate([
                'id' => 'required|exists:usuarios,id',
                'nombre' => 'required|string|max:100',
                'apellido' => 'required|string|max:100',
                'email' => 'required|email|max:255',
                'telefono' => 'required|string|max:50',
            ]);

            // Buscar el usuario
            $usuario = Usuario::find($validated['id']);

            // Actualizar los datos
            $usuario->update([
                'nombre' => $validated['nombre'],
                'apellido' => $validated['apellido'],
                'email' => $validated['email'],
                'telefono' => $validated['telefono'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Datos actualizados correctamente.'
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }
}
