<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index() {
        return view('menu_admin');
    }

    public function obtenerUsuarios(Request $request) {
        $tipo = $request->query('tipo'); // chofer o pasajero

        $usuarios = DB::table('usuarios as u')
            ->join('roles as r', 'u.rol_id', '=', 'r.id')
            ->select('u.id', 'u.nombre', 'u.apellido', 'u.email', 'u.estado', 'r.name as rol')
            ->where('r.name', $tipo)
            ->get();

        return response()->json([
            'success' => true,
            'usuarios' => $usuarios
        ]);
    }

    public function activar(Request $request) {
        DB::table('usuarios')->where('id', $request->id)->update([
            'estado' => 'ACTIVO',
            'activo' => 1,
            'actualizado_en' => now()
        ]);

        return response()->json(['success' => true, 'message' => 'Usuario activado.']);
    }

    public function desactivar(Request $request) {
        DB::table('usuarios')->where('id', $request->id)->update([
            'estado' => 'INACTIVO',
            'activo' => 0,
            'actualizado_en' => now()
        ]);

        return response()->json(['success' => true, 'message' => 'Usuario desactivado.']);
    }
}
