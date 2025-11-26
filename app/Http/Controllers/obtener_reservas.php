<?php
header("Content-Type: application/json");
require_once "conexion.php";

try {
  $input = json_decode(file_get_contents("php://input"), true);
  $pasajero_id = $input["usuario_id"] ?? null;

  if (!$pasajero_id) {
    echo json_encode(["success" => false, "message" => "ID de usuario no recibido."]);
    exit;
  }

  $sql = "SELECT rs.id AS reserva_id, r.nombre, r.lugar_salida, r.lugar_llegada, r.fecha, r.hora, rs.estado
          FROM reservas rs
          JOIN rides r ON rs.ride_id = r.id
          WHERE rs.pasajero_id = ?
          ORDER BY r.fecha DESC, r.hora DESC";

  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $pasajero_id);
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