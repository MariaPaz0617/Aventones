<?php
header("Content-Type: application/json; charset=utf-8");
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "conexion.php";
require_once "enviar_correo.php";

try {
    $nombre = trim($_POST["nombre"] ?? '');
    $apellido = trim($_POST["apellido"] ?? '');
    $cedula = trim($_POST["cedula"] ?? '');
    $fecha_nacimiento = trim($_POST["fecha_nacimiento"] ?? '');
    $email = trim($_POST["email"] ?? '');
    $telefono = trim($_POST["telefono"] ?? '');
    $password = trim($_POST["password"] ?? '');
    $rol = strtolower(trim($_POST["rol"] ?? ''));

    if (empty($nombre) || empty($apellido) || empty($cedula) || empty($fecha_nacimiento) ||
        empty($email) || empty($telefono) || empty($password) || empty($rol)) {
        echo json_encode(["success" => false, "message" => "Faltan datos obligatorios."]);
        exit;
    }

    // Verificar si el correo ya existe
    $sql = "SELECT id FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows > 0) {
        echo json_encode(["success" => false, "message" => "El correo ya está registrado."]);
        exit;
    }
    $stmt->close();

    // Obtener el ID del rol
    $sql = "SELECT id FROM roles WHERE name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $rol);
    $stmt->execute();
    $result = $stmt->get_result();
    if (!$result || $result->num_rows === 0) {
        echo json_encode(["success" => false, "message" => "Rol no válido."]);
        exit;
    }
    $rol_id = $result->fetch_assoc()["id"];
    $stmt->close();

    // Guardar la foto
    if (isset($_FILES["foto"]) && $_FILES["foto"]["error"] == 0) {
        $foto_nombre = uniqid() . "_" . basename($_FILES["foto"]["name"]);
        $ruta_foto = "../uploads/" . $foto_nombre;

        if (!is_dir("../uploads")) {
            mkdir("../uploads", 0777, true);
        }

        if (!move_uploaded_file($_FILES["foto"]["tmp_name"], $ruta_foto)) {
            throw new Exception("No se pudo guardar la foto en el servidor.");
        }
    } else {
        $ruta_foto = null;
    }

    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    $token = bin2hex(random_bytes(16));

    $sql = "INSERT INTO usuarios (rol_id, nombre, apellido, cedula, fecha_nacimiento, email, telefono, foto, password, estado, activo, token)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'PENDIENTE', 0, ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        throw new Exception("Error en prepare (insert): " . $conn->error);
    }

    $stmt->bind_param("isssssssss", $rol_id, $nombre, $apellido, $cedula, $fecha_nacimiento, $email, $telefono, $ruta_foto, $hashedPassword, $token);


    if ($stmt->execute()) {
        // Para detectar el dominio actual
        $server = $_SERVER['HTTP_HOST'];  
        //$link_activacion = "http://$server/ProyectoUber/php/activar_cuenta.php?token=" . urlencode($token);
        $link_activacion = "http://isw613.utn.ac.cr:8080/ProyectoUber/php/activar_cuenta.php?token=" . urlencode($token);
        enviarCorreoActivacion($email, $nombre, $link_activacion);

        echo json_encode(["success" => true, "message" => "Cuenta creada correctamente. Se ha enviado un correo para activar tu cuenta."]);
    } else {
        throw new Exception("Error al ejecutar inserción: " . $stmt->error);
    }

    $stmt->close();
    $conn->close();

} catch (Throwable $e) {
    echo json_encode(["success" => false, "message" => "Error interno: " . $e->getMessage()]);
}
?>
