<?php
header("Content-Type: application/json");
require_once "conexion.php";

try {
    // Verifica que la solicitud sea POST
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        echo json_encode(["success" => false, "message" => "Método no permitido"]);
        exit;
    }

    // Recoge el cuerpo JSON
    $input = json_decode(file_get_contents("php://input"), true);
    $usuario_id = $input["usuario_id"] ?? null;

    if (!$usuario_id) {
        echo json_encode(["success" => false, "message" => "Usuario no identificado"]);
        exit;
    }

    // Consulta los vehículos del chofer
    $sql = "SELECT id, placa, color, marca, modelo, año, capacidad_asientos, foto
            FROM vehiculos
            WHERE usuario_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $vehiculos = [];
    while ($row = $result->fetch_assoc()) {
        $vehiculos[] = $row;
    }

    echo json_encode(["success" => true, "vehiculos" => $vehiculos]);
} catch (Throwable $e) {
    echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
}
?>