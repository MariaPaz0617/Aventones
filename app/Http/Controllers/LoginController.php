<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        try {
            $email = trim($request->email ?? '');
            $password = trim($request->password ?? '');

            if (empty($email) || empty($password)) {
                return response()->json([
                    "success" => false,
                    "message" => "Por favor ingresa tu correo y contraseña."
                ]);
            }

            // Consulta equivalente a tu PHP nativo
            $user = DB::table('usuarios as u')
                ->join('roles as r', 'u.rol_id', '=', 'r.id')
                ->select(
                    'u.id',
                    'u.nombre',
                    'u.apellido',
                    'u.foto',
                    'u.password',
                    'u.estado',
                    'r.name as rol'
                )
                ->where('u.email', $email)
                ->first();

            if (!$user) {
                return response()->json([
                    "success" => false,
                    "message" => "El usuario no se encontró o la cuenta está inactiva."
                ]);
            }

            // Verificar estado
            if ($user->estado !== "ACTIVO") {
                return response()->json([
                    "success" => false,
                    "message" => "Tu cuenta aún no está activa. Revisa tu correo."
                ]);
            }

            // Validar contraseña
            if (!Hash::check($password, $user->password)) {
                return response()->json([
                    "success" => false,
                    "message" => "Contraseña incorrecta."
                ]);
            }

            // Respuesta final (equivalente al login.php original)
            return response()->json([
                "success" => true,
                "rol" => strtolower($user->rol),
                "id" => $user->id,
                "nombre" => $user->nombre,
                "apellido" => $user->apellido,
                "foto" => $user->foto,
                "email" => $email
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                "success" => false,
                "message" => "Error interno: " . $e->getMessage()
            ]);
        }
    }
}
