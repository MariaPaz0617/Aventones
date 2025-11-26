<?php
header("Content-Type: application/json");
require_once "conexion.php";

try {
  $input = json_decode(file_get_contents("php://input"), true);
  $id = $input["id"] ?? null;

  if (!$id) {
    echo json_encode(["success" => false, "message" => "ID de ride no recibido."]);
    exit;
  }

  // Marcar como inactivo en lugar de eliminar
  $sql = "UPDATE rides SET activo = 0 WHERE id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $id);
  $stmt->execute();

  echo json_encode(["success" => true, "message" => "Ride eliminado exitosamente."]);
} catch (Throwable $e) {
  echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
}
?>