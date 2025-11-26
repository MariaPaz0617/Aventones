<?php
header("Content-Type: application/json");
require_once "conexion.php";

try {
  $usuario_id = $_POST["usuario_id"] ?? null;
  $vehiculo_id = $_POST["vehiculo_id"] ?? null;
  $nombre = $_POST["nombre"] ?? "";
  $lugar_salida = $_POST["lugar_salida"] ?? "";
  $lugar_llegada = $_POST["lugar_llegada"] ?? "";
  $fecha = $_POST["fecha"] ?? "";
  $hora = $_POST["hora"] ?? "";
  $costo = $_POST["costo"] ?? "";
  $cantidad_espacios = $_POST["cantidad_espacios"] ?? "";

  if (!$usuario_id || !$vehiculo_id || !$nombre || !$lugar_salida || !$lugar_llegada || !$fecha || !$hora || !$costo || !$cantidad_espacios) {
    echo json_encode(["success" => false, "message" => "Todos los campos son obligatorios."]);
    exit;
  }

  $sql = "INSERT INTO rides (usuario_id, vehiculo_id, nombre, lugar_salida, lugar_llegada, fecha, hora, costo, cantidad_espacios, activo)
          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 1)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("iisssssdi", $usuario_id, $vehiculo_id, $nombre, $lugar_salida, $lugar_llegada, $fecha, $hora, $costo, $cantidad_espacios);
  $stmt->execute();

  echo json_encode(["success" => true, "message" => "Ride registrado exitosamente."]);
} catch (Throwable $e) {
  echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
}
?>