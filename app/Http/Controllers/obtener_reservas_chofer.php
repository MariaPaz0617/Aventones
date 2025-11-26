<?php
header("Content-Type: application/json");
require_once "conexion.php";

try {
  $input = json_decode(file_get_contents("php://input"), true);
  $chofer_id = $input["chofer_id"] ?? null;

  if (!$chofer_id) {
    echo json_encode(["success" => false, "message" => "ID de chofer no recibido."]);
    exit;
  }

  //obtener_reservas_chofer.php
  $sql = "SELECT 
            rs.id AS reserva_id,
            u.nombre AS pasajero_nombre,
            r.lugar_salida,
            r.lugar_llegada,
            r.fecha,
            r.hora,
            rs.estado
          FROM reservas rs
          JOIN rides r ON rs.ride_id = r.id
          JOIN usuarios u ON rs.pasajero_id = u.id
          WHERE r.usuario_id = ?
          ORDER BY r.fecha DESC, r.hora DESC";


  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $chofer_id);
  $stmt->execute();
  $result = $stmt->get_result();

  $reservas = [];
  while ($row = $result->fetch_assoc()) {
    $reservas[] = $row;
  }

  echo json_encode(["success" => true, "reservas" => $reservas]);
} catch (Throwable $e) {
  echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
}
?>