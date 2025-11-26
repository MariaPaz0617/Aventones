<?php
header("Content-Type: application/json");
require_once "conexion.php";

try {
  $input = json_decode(file_get_contents("php://input"), true);
  $reserva_id = $input["id"] ?? null;
  $pasajero_id = $input["pasajero_id"] ?? null;

  if (!$reserva_id || !$pasajero_id) {
    echo json_encode(["success" => false, "message" => "Faltan datos para cancelar la reserva."]);
    exit;
  }

  // Verificar que la reserva pertenezca al usuario y esté activa o pendiente
  $verificar = $conn->prepare("SELECT id FROM reservas WHERE id = ? AND pasajero_id = ? AND estado IN ('pendiente', 'aceptada')");
  $verificar->bind_param("ii", $reserva_id, $pasajero_id);
  $verificar->execute();
  $verificar->store_result();

  if ($verificar->num_rows === 0) {
    echo json_encode(["success" => false, "message" => "No se puede cancelar esta reserva."]);
    exit;
  }

  // Marcar la reserva como cancelada
  $sql = $sql = "UPDATE reservas SET estado = 'cancelada', actualizado_en = NOW() WHERE id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $reserva_id);
  $stmt->execute();

  echo json_encode(["success" => true, "message" => "Reserva cancelada exitosamente."]);
} catch (Throwable $e) {
  echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
}
?>