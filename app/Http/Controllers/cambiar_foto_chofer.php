<?php
header("Content-Type: application/json");
require_once "conexion.php";

try {
  if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["success" => false, "message" => "Método no permitido"]);
    exit;
  }

  $id = $_POST["id"] ?? null;
  if (!$id || !isset($_FILES["foto"]) || $_FILES["foto"]["error"] !== UPLOAD_ERR_OK) {
    echo json_encode(["success" => false, "message" => "Datos incompletos o imagen no válida"]);
    exit;
  }

  $nombreArchivo = uniqid("chofer_") . "_" . basename($_FILES["foto"]["name"]);
  $rutaDestino = "../img/" . $nombreArchivo;

  if (move_uploaded_file($_FILES["foto"]["tmp_name"], $rutaDestino)) {
    $stmt = $conn->prepare("UPDATE usuarios SET foto=? WHERE id=?");
    $stmt->bind_param("si", $nombreArchivo, $id);
    $stmt->execute();

    echo json_encode(["success" => true, "message" => "Fotografía actualizada", "foto" => $nombreArchivo]);
  } else {
    echo json_encode(["success" => false, "message" => "Error al guardar la imagen"]);
  }
} catch (Throwable $e) {
  echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
}
?>