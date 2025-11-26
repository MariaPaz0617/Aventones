<?php
header("Content-Type: application/json");
require_once "conexion.php";

try {
    $sql = "SELECT u.id, u.nombre, u.apellido, u.email, r.name AS rol, u.rol_id, u.estado
            FROM usuarios u
            JOIN roles r ON u.rol_id = r.id
            ORDER BY r.id, u.nombre";
  $result = $conn->query($sql);

  $usuarios = [];
  while ($row = $result->fetch_assoc()) {
    $usuarios[] = [
    "id" => $row["id"],
    "nombre" => $row["nombre"] . " " . $row["apellido"],
    "email" => $row["email"],
    "rol" => $row["rol"],
    "rol_id" => $row["rol_id"],
    "estado" => $row["estado"]
    ];
  }

  echo json_encode(["success" => true, "usuarios" => $usuarios]);
} catch (Throwable $e) {
  echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
}
?>