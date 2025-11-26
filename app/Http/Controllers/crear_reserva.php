<?php
header("Content-Type: application/json");
require_once "conexion.php";

try {
  $ride_id = $_POST["ride_id"] ?? null;
  $usuario_id = $_POST["usuario_id"] ?? null;

  if (!$ride_id || !$usuario_id) {
    echo json_encode(["success" => false, "message" => "Faltan datos para crear la reserva."]);
    exit;
  }

  // Verificar si ya existe una reserva pendiente o activa para este ride y usuario
  $verificar = $conn->prepare("SELECT id FROM reservas WHERE ride_id = ? AND pasajero_id = ? AND estado IN ('pendiente', 'aceptada')");
  $verificar->bind_param("ii", $ride_id, $usuario_id);
  $verificar->execute();
  $verificar->store_result();

  if ($verificar->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "Ya tienes una reserva activa o pendiente para este ride."]);
    exit;
  }

  // Crear la reserva
  $sql = "INSERT INTO reservas (ride_id, pasajero_id, estado, fecha_solicitud) VALUES (?, ?, 'pendiente', NOW())";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ii", $ride_id, $usuario_id);
  $stmt->execute();

  echo json_encode(["success" => true, "message" => "Reserva creada exitosamente."]);
} catch (Throwable $e) {
  echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
}
?>