<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\Ride;
use Illuminate\Http\Request;

class ReservaPasajeroController extends Controller
{
    public function reservar(Request $request)
    {
        $request->validate([
            'pasajero_id' => 'required|exists:usuarios,id',
            'ride_id' => 'required|exists:rides,id',
            'cantidad_asientos' => 'required|integer|min:1'
        ]);

        $ride = Ride::find($request->ride_id);

        // Calcular espacios disponibles correctamente
        $reservados = $ride->reservas()
            ->whereIn('estado', ['PENDIENTE', 'APROBADA'])
            ->sum('cantidad_asientos');

        $espaciosDisponibles = $ride->cantidad_espacios - $reservados;

        if ($request->cantidad_asientos > $espaciosDisponibles) {
            return response()->json([
                'success' => false,
                'message' => 'No hay suficientes espacios disponibles.'
            ]);
        }

        // Crear reserva PENDIENTE
        $reserva = Reserva::create([
            'pasajero_id' => $request->pasajero_id,
            'ride_id' => $request->ride_id,
            'cantidad_asientos' => $request->cantidad_asientos,
            'estado' => 'PENDIENTE',
            'fecha_solicitud' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Reserva creada con éxito.',
            'reserva' => $reserva
        ]);
    }

    //FUNCION RECHAZAR RESERVA USADA POR EL CHOFER
    public function aceptar(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:reservas,id'
        ]);

        $reserva = Reserva::find($request->id);

        if ($reserva->estado !== 'PENDIENTE') {
            return response()->json(['success' => false, 'message' => "La reserva ya fue procesada."]);
        }

        $reserva->estado = 'ACEPTADA';
        $reserva->actualizado_en = now();
        $reserva->save();

        return response()->json(['success' => true, 'message' => 'Reserva aceptada.']);
    }


    //FUNCION RECHAZAR RESERVA USADA POR EL CHOFER
    public function rechazar(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:reservas,id'
        ]);

        $reserva = Reserva::find($request->id);

        if ($reserva->estado !== 'PENDIENTE') {
            return response()->json(['success' => false, 'message' => "La reserva ya fue procesada."]);
        }

        // SE DEVUELVEN ESPACIOS AL RIDE
        $ride = $reserva->ride;
        $ride->espacios_disponibles += $reserva->cantidad_asientos;
        $ride->save();

        $reserva->estado = 'RECHAZADA';
        $reserva->actualizado_en = now();
        $reserva->save();

        return response()->json(['success' => true, 'message' => 'Reserva rechazada.']);
    }

    //FUNCION CANCELAR RESERVA USADA POR EL PASAJERO
    public function cancelar(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|exists:reservas,id'
            ]);

            $reserva = Reserva::find($request->id);

            if (!$reserva) {
                return response()->json([
                    'success' => false,
                    'message' => 'Reserva no encontrada'
                ]);
            }

            if ($reserva->estado !== "PENDIENTE") {
                return response()->json([
                    'success' => false,
                    'message' => 'Solo puedes cancelar reservas pendientes'
                ]);
            }

            $ride = $reserva->ride;

            if (!$ride) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ride no encontrado'
                ]);
            }

            // devolver espacios
            $ride->espacios_disponibles += $reserva->cantidad_asientos;
            $ride->save();

            $reserva->estado = "RECHAZADA";
            $reserva->save();

            return response()->json([
                'success' => true,
                'message' => 'Reserva cancelada correctamente'
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => 'ERROR: ' . $e->getMessage()
            ]);
        }
    }











}
