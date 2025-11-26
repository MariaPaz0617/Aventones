<?php
header("Content-Type: application/json");
require_once "conexion.php";

try {
  $usuario_id = $_POST["usuario_id"] ?? null;
  $nombre     = trim($_POST["nombre"] ?? "");
  $correo     = trim($_POST["correo"] ?? "");
  $telefono   = trim($_POST["telefono"] ?? "");
  $contrasena = $_POST["contrasena"] ?? "";
  $foto       = $_FILES["foto"] ?? null;

  if (!$usuario_id || !$nombre || !$correo) {
    echo json_encode(["success" => false, "message" => "Nombre y correo son obligatorios."]);
    exit;
  }

  if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["success" => false, "message" => "El correo no tiene un formato válido."]);
    exit;
  }

  // Verificar si el correo ya existe en otro usuario
  $sqlCheck = "SELECT id FROM usuarios WHERE correo = ? AND id != ?";
  $stmtCheck = $conn->prepare($sqlCheck);
  $stmtCheck->bind_param("si", $correo, $usuario_id);
  $stmtCheck->execute();
  $resultCheck = $stmtCheck->get_result();

  if ($resultCheck->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "Ese correo ya está registrado por otro usuario."]);
    exit;
  }

  // Procesar foto si se envió
  $fotoNombre = null;
  if ($foto && $foto["error"] === UPLOAD_ERR_OK) {
    $ext = pathinfo($foto["name"], PATHINFO_EXTENSION);
    $fotoNombre = "foto_" . time() . "_" . rand(1000,9999) . "." . $ext;
    move_uploaded_file($foto["tmp_name"], "../img/usuarios/" . $fotoNombre);
  }

  // Construir consulta dinámica
  $campos = "nombre = ?, correo = ?, telefono = ?";
  $tipos  = "sss";
  $valores = [$nombre, $correo, $telefono];

  if ($contrasena) {
    $campos .= ", contrasena = ?";
    $tipos  .= "s";
    $valores[] = password_hash($contrasena, PASSWORD_DEFAULT);
  }

  if ($fotoNombre) {
    $campos .= ", foto = ?";
    $tipos  .= "s";
    $valores[] = $fotoNombre;
  }

  $sql = "UPDATE usuarios SET $campos WHERE id = ?";
  $tipos .= "i";
  $valores[] = $usuario_id;

  $stmt = $conn->prepare($sql);
  if (!$stmt) {
    echo json_encode(["success" => false, "message" => "Error al preparar la consulta."]);
    exit;
  }

  $stmt->bind_param($tipos, ...$valores);
  $stmt->execute();

  echo json_encode(["success" => true, "message" => "Perfil actualizado exitooosamente."]);
} catch (Throwable $e) {
  echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
}
?>