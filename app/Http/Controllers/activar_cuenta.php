<?php
require_once 'conexion.php';

if (isset($_GET['token'])) {
    $token = trim($_GET['token']);

    // Buscar usuario con ese token (sin importar si el estado está en mayúsculas o no)
    $query = "SELECT * FROM usuarios WHERE token = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();
        $id = $usuario['id'];

        $update = "UPDATE usuarios SET estado = 'ACTIVA', activo = 1, token = NULL WHERE id = ?";
        $stmtUpdate = $conn->prepare($update);
        $stmtUpdate->bind_param("i", $id);
        $stmtUpdate->execute();

        if ($stmtUpdate->affected_rows > 0) {
            echo "✅ Tu cuenta ha sido activada correctamente. Ahora puedes iniciar sesión.";
        } else {
            echo "⚠️ No se pudo actualizar el estado de la cuenta.";
        }
    } else {
        echo "❌ Token inválido o ya utilizado.";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "⚠️ Token no proporcionado.";
}
?>
