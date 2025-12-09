<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;

class EditarPasajeroController extends Controller
{
    public function update(Request $request)
    {
        try {

            $validator = validator($request->all(), [
                'id' => 'required|exists:usuarios,id',
                'nombre' => 'required|string|max:100',
                'apellido' => 'required|string|max:100',
                'telefono' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ]);
            }

            $validated = $validator->validated();

            $usuario = Usuario::find($validated['id']);

            $usuario->update([
                'nombre' => $validated['nombre'],
                'apellido' => $validated['apellido'],
                'telefono' => $validated['telefono']
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
