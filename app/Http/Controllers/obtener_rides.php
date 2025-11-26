<?php
header("Content-Type: application/json");
require_once "conexion.php";

try {
  $input = json_decode(file_get_contents("php://input"), true);
  $usuario_id = $input["usuario_id"] ?? null;

  if (!$usuario_id) {
    echo json_encode(["success" => false, "message" => "ID de usuario no recibido"]);
    exit;
  }

  $sql = "SELECT r.*, v.marca AS vehiculo_marca, v.modelo AS vehiculo_modelo, v.año AS vehiculo_año
          FROM rides r
          JOIN vehiculos v ON r.vehiculo_id = v.id
          WHERE r.usuario_id = ? AND r.activo = 1
          ORDER BY r.fecha ASC, r.hora ASC";

  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $usuario_id);
  $stmt->execute();
  $result = $stmt->get_result();

  $rides = [];
  while ($row = $result->fetch_assoc()) {
    $rides[] = $row;
  }

  echo json_encode(["success" => true, "rides" => $rides]);
} catch (Throwable $e) {
  echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
}
?>