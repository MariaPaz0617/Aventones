<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ride;

class EliminarRideController extends Controller
{
    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:rides,id'
        ]);

        $ride = Ride::find($request->id);

        $ride->activo = 0; // Desactivar
        $ride->save();

        return response()->json([
            "success" => true,
            "message" => "Ride eliminado correctamente"
        ]);
    }
}
