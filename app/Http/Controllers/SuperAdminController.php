<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class SuperAdminController extends Controller
{
    /** 
     * Vista principal del menú de SuperAdministrador 
     */
    public function index()
    {
        return view('menu_superadmin');
    }

    /**
     * Obtener usuarios filtrados por tipo (rol)
     */
    public function obtenerUsuarios(Request $request)
    {
        $tipo = $request->query('tipo'); // "admin", "chofer", "pasajero", "superadmin"

        $query = DB::table('usuarios as u')
            ->join('roles as r', 'u.rol_id', '=', 'r.id')
            ->select('u.id', 'u.nombre', 'u.apellido', 'u.email', 'u.estado', 'r.name as rol');

        if ($tipo !== 'todos') {
            $query->where('r.name', $tipo); // Filtrar por rol
        }

        $usuarios = $query->get();

        return response()->json([
            'success' => true,
            'usuarios' => $usuarios
        ]);
    }

    /**
     * Crear un nuevo administrador
     */
    public function crearAdministrador(Request $request)
    {
        try {

            $admin = Usuario::create([
                'nombre'            => $request->nombre,
                'apellido'          => $request->apellido,
                'email'             => $request->email,
                'password'          => Hash::make($request->password),

                // Datos generados automáticamente
                'cedula'            => 'ADMIN-' . strtoupper(uniqid()),
                'fecha_nacimiento'  => now()->subYears(18), // fecha genérica válida
                'telefono'          => '00000000',          // placeholder
                'foto'              => null,                // sin foto
                'estado'            => 'ACTIVO',
                'activo'            => 1,

                // Rol del administrador 
                'rol_id'            => 3
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Administrador creado.',
                'data'    => $admin
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }


    /**
     * Activar usuario
     */
    public function activarUsuario(Request $request)
    {
        DB::table('usuarios')->where('id', $request->id)->update([
            'estado' => 'ACTIVO',
            'activo' => 1,
            'actualizado_en' => now() // ← ESTA ES LA COLUMNA CORRECTA
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Usuario activado.'
        ]);
    }


    /**
     * Desactivar usuario
     */
    public function desactivarUsuario(Request $request)
    {
        DB::table('usuarios')->where('id', $request->id)->update([
            'estado' => 'INACTIVO',
            'activo' => 0,
            'actualizado_en' => now() 
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Usuario desactivado.'
        ]);
    }

}
