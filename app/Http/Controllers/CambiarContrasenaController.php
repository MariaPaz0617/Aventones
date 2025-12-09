<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;

class CambiarContrasenaController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:usuarios,id',
            'actual' => 'required',
            'nueva' => 'required|min:6',
            'confirmar' => 'required|same:nueva',
        ]);

        $usuario = Usuario::find($request->id);

        if (!Hash::check($request->actual, $usuario->password)) {
            return response()->json([
                'success' => false,
                'message' => 'La contraseña actual es incorrecta.'
            ]);
        }

        $usuario->password = Hash::make($request->nueva);
        $usuario->save();

        return response()->json([
            'success' => true,
            'message' => 'Contraseña actualizada. Debes iniciar sesión nuevamente.'
        ]);
    }
}
