<?php
header("Content-Type: application/json");
require_once "conexion.php";

try {
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        echo json_encode(["success" => false, "message" => "Método no permitido"]);
        exit;
    }

    $vehiculo_id = isset($_POST["id"]) ? (int)$_POST["id"] : null;
    if (!$vehiculo_id) {
        echo json_encode(["success" => false, "message" => "ID de vehículo no recibido"]);
        exit;
    }

    $placa = trim($_POST["placa"] ?? '');
    $color = trim($_POST["color"] ?? '');
    $marca = trim($_POST["marca"] ?? '');
    $modelo = trim($_POST["modelo"] ?? '');
    $año = (int)($_POST["año"] ?? 0);
    $capacidad = (int)($_POST["capacidad_asientos"] ?? 4);

    $colorModificado = isset($_POST["color_modificado"]) && $_POST["color_modificado"] === "1";
    $nuevaImagenSubida = isset($_FILES["foto"]) && is_uploaded_file($_FILES["foto"]["tmp_name"]);
    if ($colorModificado && !$nuevaImagenSubida) {
        echo json_encode(["success" => false, "message" => "Debes subir una nueva imagen si modificas el color."]);
        exit;
    }

    //Mantener foto actual si no se sube una nueva
    $foto = $_POST["foto_actual"] ?? null;

    if ($nuevaImagenSubida) {
        $nombreArchivo = uniqid("vehiculo_") . "_" . basename($_FILES["foto"]["name"]);
        $rutaDestino = __DIR__ . "/../img/" . $nombreArchivo; 
        if (move_uploaded_file($_FILES["foto"]["tmp_name"], $rutaDestino)) {
            $foto = $nombreArchivo;
        } else {
            echo json_encode(["success" => false, "message" => "No se pudo mover la imagen al destino."]);
            exit;
        }
    }

    $sql = "UPDATE vehiculos 
            SET placa=?, color=?, marca=?, modelo=?, año=?, capacidad_asientos=?, foto=? 
            WHERE id=?";
    $stmt = $conn->prepare($sql);

    //Orden correcto de tipos: s s s s i i s i
    $stmt->bind_param("ssssiisi", $placa, $color, $marca, $modelo, $año, $capacidad, $foto, $vehiculo_id);

    $stmt->execute();
    echo json_encode(["success" => true, "message" => "Vehículo actualizado correctamente"]);
} catch (Throwable $e) {
    echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
}
?>