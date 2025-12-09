<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Storage;

class ActualizarFotoPasajeroController extends Controller
{
    public function update(Request $request)
    {
        try {
            $validated = $request->validate([
                'id' => 'required|exists:usuarios,id',
                'foto' => 'required|image|max:2048'
            ]);

            $usuario = Usuario::find($validated['id']);

            $ruta = $request->file('foto')->store('usuarios', 'public');

            $usuario->foto = $ruta;
            $usuario->save();

            return response()->json([
                'success' => true,
                'message' => 'Fotografía actualizada correctamente.',
                'foto' => $ruta
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }
}
