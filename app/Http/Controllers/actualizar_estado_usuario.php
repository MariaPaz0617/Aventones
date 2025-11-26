<?php
header("Content-Type: application/json");
require_once "conexion.php";

try {
  $input = json_decode(file_get_contents("php://input"), true);
  $id = $input["id"] ?? null;
  $activo = $input["activo"] ?? null;

  if ($id === null || $activo === null) {
    echo json_encode(["success" => false, "message" => "Datos inválidos"]);
    exit;
  }

  $stmt = $conn->prepare("UPDATE usuarios SET activo = ?, actualizado_en = NOW() WHERE id = ?");
  $stmt->bind_param("ii", $activo, $id);
  $stmt->execute();

  $accion = $activo ? "activado" : "desactivado";
  echo json_encode(["success" => true, "message" => "Usuario $accion correctamente."]);
} catch (Throwable $e) {
  echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
}
?>
