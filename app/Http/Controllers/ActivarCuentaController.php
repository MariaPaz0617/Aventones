<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActivarCuentaController extends Controller
{
    public function activar(Request $request)
    {
        $token = $request->query('token');

        if (!$token) {
            return "Token inválido.";
        }

        // Buscar usuario por token
        $usuario = DB::table('usuarios')->where('token', $token)->first();

        if (!$usuario) {
            return "Token no válido o usuario no encontrado.";
        }

        // Activar cuenta del usuario
        DB::table('usuarios')->where('id', $usuario->id)->update([
            'estado' => 'ACTIVO',
            'activo' => 1,
            'token' => null, // borrar token para que no pueda reutilizarse
            'actualizado_en' => now()
        ]);

        // Mostrar mensaje de éxito
        return view('cuenta_activada', [
            'mensaje' => 'Tu cuenta ha sido activada correctamente. Ya puedes iniciar sesión.'
        ]);
    }
}
