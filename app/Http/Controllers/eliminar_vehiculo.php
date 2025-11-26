<?php
header("Content-Type: application/json");
require_once "conexion.php";

try {
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        echo json_encode(["success" => false, "message" => "Método no permitido"]);
        exit;
    }

    $input = json_decode(file_get_contents("php://input"), true);
    $vehiculo_id = $input["id"] ?? null;

    if (!$vehiculo_id) {
        echo json_encode(["success" => false, "message" => "ID de vehículo no recibido"]);
        exit;
    }

    $sql = "DELETE FROM vehiculos WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $vehiculo_id);
    $stmt->execute();

    echo json_encode(["success" => true, "message" => "Vehículo eliminado correctamente"]);
} catch (Throwable $e) {
    echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
}
?>