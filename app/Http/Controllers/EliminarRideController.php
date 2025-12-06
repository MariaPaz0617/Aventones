<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Throwable;

class EliminarRideController extends Controller
{
    private $conn;

    public function __construct($conexion)
    {
        $this->conn = $conexion;
        header("Content-Type: application/json");
    }

    public function eliminarRide(Request $request)
    {
        try {
            $id = $request->input("id");

            if (!$id) {
                return response()->json([
                    "success" => false,
                    "message" => "ID de ride no recibido."
                ]);
            }

            // Marcar como inactivo
            $sql = "UPDATE rides SET activo = 0 WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();

            return response()->json([
                "success" => true,
                "message" => "Ride eliminado exitosamente."
            ]);

        } catch (Throwable $e) {
            return response()->json([
                "success" => false,
                "message" => "Error: " . $e->getMessage()
            ]);
        }
    }
}
