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

        $usuario = DB::table('usuarios')->where('token_activacion', $token)->first();

        if (!$usuario) {
            return "Token no válido o usuario no encontrado.";
        }

        // Activar cuenta
        DB::table('usuarios')->where('id', $usuario->id)->update([
            'estado' => 'ACTIVO',
            'activo' => 1,
            'token_activacion' => null,
            'updated_at' => now()
        ]);

        return view('cuenta_activada', ['mensaje' => 'Tu cuenta ha sido activada correctamente. Ya puedes iniciar sesión.']);
    }
}
