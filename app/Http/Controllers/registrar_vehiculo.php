<?php
header("Content-Type: application/json");
require_once "conexion.php";

try {
    //Verifica que se haya enviado el formulario
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        echo json_encode(["success" => false, "message" => "Método no permitido"]);
        exit;
    }

    // Verifica que el usuario esté autenticado
    session_start();
    $usuario_id = $_POST["usuario_id"] ?? null;

    if (!$usuario_id) {
        echo json_encode(["success" => false, "message" => "Usuario no identificado"]);
        exit;
    }

    //Recoge los datos del formulario
    $placa = trim($_POST["placa"] ?? '');
    $color = trim($_POST["color"] ?? '');
    $marca = trim($_POST["marca"] ?? '');
    $modelo = trim($_POST["modelo"] ?? '');
    $año = intval($_POST["año"] ?? 0);
    $capacidad = intval($_POST["capacidad_asientos"] ?? 4);

    //Manejo de imagen
    $foto = null;
    if (isset($_FILES["foto"]) && $_FILES["foto"]["error"] === UPLOAD_ERR_OK) {
        $nombreArchivo = uniqid("vehiculo_") . "_" . basename($_FILES["foto"]["name"]);
        $rutaDestino = "../img/" . $nombreArchivo;
        if (move_uploaded_file($_FILES["foto"]["tmp_name"], $rutaDestino)) {
            $foto = $nombreArchivo;
        }
    }

    //Inserta en la base de datos
    $sql = "INSERT INTO vehiculos (usuario_id, placa, color, marca, modelo, año, capacidad_asientos, foto)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issssiis", $usuario_id, $placa, $color, $marca, $modelo, $año, $capacidad, $foto);
    $stmt->execute();

    echo json_encode(["success" => true, "message" => "Vehículo registrado correctamente"]);
} catch (Throwable $e) {
    echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
}
?>