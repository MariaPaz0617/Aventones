<?php
header("Content-Type: application/json");
require_once "conexion.php";

try {
  $sql = "SELECT r.id, r.nombre, r.lugar_salida, r.lugar_llegada, r.fecha, r.hora, r.costo, r.cantidad_espacios,
                 v.marca AS vehiculo_marca, v.modelo AS vehiculo_modelo, v.año AS vehiculo_año
          FROM rides r
          JOIN vehiculos v ON r.vehiculo_id = v.id
          WHERE r.activo = 1 AND r.fecha >= CURDATE()
          ORDER BY r.fecha ASC, r.hora ASC";

  $result = $conn->query($sql);
  $rides = [];

  while ($row = $result->fetch_assoc()) {
    $rides[] = $row;
  }

  echo json_encode(["success" => true, "rides" => $rides]);
} catch (Throwable $e) {
  echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
}
?>