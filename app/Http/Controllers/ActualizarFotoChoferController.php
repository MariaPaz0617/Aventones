<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Storage;

class ActualizarFotoChoferController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:usuarios,id',
            'foto' => 'required|image|max:4096', // 4MB
        ]);

        $usuario = Usuario::find($request->id);

        // Eliminar foto anterior si existe
        if ($usuario->foto && Storage::disk('public')->exists($usuario->foto)) {
            Storage::disk('public')->delete($usuario->foto);
        }

        // Guardar nueva foto
        $ruta = $request->file('foto')->store('usuarios', 'public');

        // Actualizar BD
        $usuario->update([
            'foto' => $ruta
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Fotografía actualizada correctamente.',
            'foto' => $ruta
        ]);
    }
}
