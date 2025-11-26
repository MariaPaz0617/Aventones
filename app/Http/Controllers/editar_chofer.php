<?php
header("Content-Type: application/json");
require_once "conexion.php";

try {
  if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["success" => false, "message" => "Método no permitido"]);
    exit;
  }

  $id = $_POST["id"] ?? null;
  $nombre = trim($_POST["nombre"] ?? '');
  $apellido = trim($_POST["apellido"] ?? '');
  $email = trim($_POST["email"] ?? '');
  $telefono = trim($_POST["telefono"] ?? '');

  if (!$id || !$nombre || !$apellido || !$email || !$telefono) {
    echo json_encode(["success" => false, "message" => "Faltan datos obligatorios"]);
    exit;
  }

  $sql = "UPDATE usuarios SET nombre=?, apellido=?, email=?, telefono=? WHERE id=?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ssssi", $nombre, $apellido, $email, $telefono, $id);
  $stmt->execute();

  echo json_encode(["success" => true, "message" => "Datos actualizadoooos correctamente"]);
} catch (Throwable $e) {
  echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
}
?>