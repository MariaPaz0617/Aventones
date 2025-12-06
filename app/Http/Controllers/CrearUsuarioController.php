<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Helpers\Mailer; 

class CrearUsuarioController extends Controller
{
    public function store(Request $request)
    {
            
        try {
            $validated = $request->validate([
                'nombre'            => 'required|string',
                'apellido'          => 'required|string',
                'cedula'            => 'required|string',
                'fecha_nacimiento'  => 'required|date',
                'email'             => 'required|email',
                'telefono'          => 'required|string',
                'password'          => 'required|string|min:6',
                'rol'               => 'required|string',
                'foto'              => 'nullable|image|max:2048'
            ]);

            $rol = strtolower($validated['rol']);

            // Verificar email duplicado
            if (DB::table('usuarios')->where('email', $validated['email'])->exists()) {
                return response()->json([
                    "success" => false,
                    "message" => "El correo ya está registrado."
                ]);
            }

            // Buscar rol
            $rolRecord = DB::table('roles')->where('name', $rol)->first();

            if (!$rolRecord) {
                return response()->json([
                    "success" => false,
                    "message" => "Rol no válido."
                ]);
            }

            // Guardar foto
            $fotoPath = null;
            if ($request->hasFile('foto')) {
                $fotoPath = $request->file('foto')->store('usuarios', 'public');
            }

            // Generar token
            $token = bin2hex(random_bytes(16));

            // Insertar usuario
            DB::table('usuarios')->insert([
                'rol_id'            => $rolRecord->id,
                'nombre'            => $validated['nombre'],
                'apellido'          => $validated['apellido'],
                'cedula'            => $validated['cedula'],
                'fecha_nacimiento'  => $validated['fecha_nacimiento'],
                'email'             => $validated['email'],
                'telefono'          => $validated['telefono'],
                'foto'              => $fotoPath,
                'password'          => Hash::make($validated['password']),
                'estado'            => 'PENDIENTE',
                'activo'            => 0,
                'token'             => $token, 
                'creado_en'         => now(),
                'actualizado_en'    => now()
            ]);

            // Crear link dinámico
             $link_activacion = url('/activar-cuenta?token=' . urlencode($token));

            // Enviar correo por Mailer
            Mailer::enviarCorreoActivacion(
                $validated['email'],
                $validated['nombre'],
                $link_activacion
            );

            return response()->json([
                "success" => true,
                "message" => "Cuenta creada correctamente. Revisa tu correo para activarla."
            ],200);

        } catch (\Throwable $e) {
            return response()->json([
                "success" => false,
                "message" => "Error: " . $e->getMessage()
            ],500);
        }
    }
}
