<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EliminarVehiculoController extends Controller
{
    public function delete(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|integer'
            ]);

            $vehiculo_id = $request->input('id');

            DB::table('vehiculos')->where('id', $vehiculo_id)->delete();

            return response()->json([
                "success" => true,
                "message" => "Vehículo eliminado correctamente"
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                "success" => false,
                "message" => "Error: " . $e->getMessage()
            ]);
        }
    }
}
