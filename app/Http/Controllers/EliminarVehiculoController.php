<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EliminarVehiculoController extends Controller
{
    public function delete(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|integer|exists:vehiculos,id'
            ]);

            $vehiculo = Vehiculo::find($request->id);

            if ($vehiculo->foto && Storage::disk('public')->exists($vehiculo->foto)) {
                Storage::disk('public')->delete($vehiculo->foto);
            }

            $vehiculo->delete();

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
