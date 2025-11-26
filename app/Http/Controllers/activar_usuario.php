<?php
header("Content-Type: application/json");
require_once "conexion.php";

try {
  $input = json_decode(file_get_contents("php://input"), true);
  $id = $input["id"] ?? null;

  if (!$id) {
    echo json_encode(["success" => false, "message" => "ID no recibido."]);
    exit;
  }

  $sql = "UPDATE usuarios SET estado = 'ACTIVA' WHERE id = ? AND rol_id != 1";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $id);
  $stmt->execute();

  echo json_encode(["success" => true, "message" => "Usuario activado."]);
} catch (Throwable $e) {
  echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
}
?>