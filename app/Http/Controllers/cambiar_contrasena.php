<?php
header("Content-Type: application/json");
require_once "conexion.php";

try {
  if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["success" => false, "message" => "Método no permitido"]);
    exit;
  }

  $id = $_POST["id"] ?? null;
  $actual = $_POST["actual"] ?? '';
  $nueva = $_POST["nueva"] ?? '';

  if (!$id || !$actual || !$nueva) {
    echo json_encode(["success" => false, "message" => "Faltan datos"]);
    exit;
  }

  // Obtener contraseña actual
  $stmt = $conn->prepare("SELECT password FROM usuarios WHERE id=?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $stmt->bind_result($hashActual);
  $stmt->fetch();
  $stmt->close();

  if (!$hashActual) {
    echo json_encode(["success" => false, "message" => "Usuario no encontrado"]);
    exit;
  }

  if (!password_verify($actual, $hashActual)) {
    echo json_encode(["success" => false, "message" => "La contraseña actual es incorrecta"]);
    exit;
  }

  // Actualizar con nueva contraseña
  $nuevaHash = password_hash($nueva, PASSWORD_DEFAULT);
  $stmt = $conn->prepare("UPDATE usuarios SET password=? WHERE id=?");
  $stmt->bind_param("si", $nuevaHash, $id);
  $stmt->execute();
  $stmt->close();

  echo json_encode(["success" => true, "message" => "Contraseña actualizada correctamente. Debe iniciar sesión nuevamente."]);
  } catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Error del servidor"]);
  }
?>