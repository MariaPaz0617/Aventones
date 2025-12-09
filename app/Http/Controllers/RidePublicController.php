<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ride;

class RidePublicController extends Controller
{
    public function listar(Request $request)
    {
        try {
            // Leer filtros enviados desde el frontend
            $origen = $request->origen;
            $destino = $request->destino;
            $ordenarPor = $request->ordenar_por ?? 'fecha';
            $direccion = $request->direccion ?? 'asc';

            // Base de la consulta: solo rides activos
            $query = Ride::with('vehiculo')
                        ->where('activo', 1);

            // Filtro por origen
            if (!empty($origen)) {
                $query->where('lugar_salida', 'LIKE', "%$origen%");
            }

            // Filtro por destino
            if (!empty($destino)) {
                $query->where('lugar_llegada', 'LIKE', "%$destino%");
            }

            // Ordenamiento
            $query->orderBy($ordenarPor, $direccion);

            // Obtener los rides
            $rides = $query->get();

            // Calcular asientos disponibles para cada ride
            $rides = $rides->map(function ($r) {
                $reservados = $r->reservas() ->whereIn('estado', ['PENDIENTE', 'APROBADA']) ->sum('cantidad_asientos');

                $r->espacios_disponibles = $r->cantidad_espacios - $reservados;

                if ($r->espacios_disponibles < 0) {
                    $r->espacios_disponibles = 0;
                }

                return $r;
            });

            return response()->json([
                'success' => true,
                'rides' => $rides
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }
}
