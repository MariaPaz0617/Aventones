<?php
header("Content-Type: application/json");
require_once "conexion.php";

try {
  $input = json_decode(file_get_contents("php://input"), true);
  $id = $input["id"] ?? null;
  $estado = $input["estado"] ?? null;

  if (!$id || !in_array($estado, ["aceptada", "rechazada"])) {
    echo json_encode(["success" => false, "message" => "Datos inválidos."]);
    exit;
  }

  $sql = "UPDATE reservas SET estado = ?, actualizado_en = NOW() WHERE id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("si", $estado, $id);
  $stmt->execute();

  echo json_encode(["success" => true, "message" => "Reserva actualizada a '$estado'."]);
} catch (Throwable $e) {
  echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
}
?>