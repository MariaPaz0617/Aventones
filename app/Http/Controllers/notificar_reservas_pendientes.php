<?php

$isCli = (PHP_SAPI === 'cli');

if (!$isCli) {
  //Si se ejecuta en navegador, usar texto plano para ver trazas legibles
  header("Content-Type: text/plain; charset=UTF-8");
}

echo "=== Notificar reservas pendientes (inicio) ===\n";

require_once __DIR__ . "/conexion.php";
require_once __DIR__ . "/enviar_correo_notificacion.php";

//Tiempo minimo de espera para notificar (reservas pendientes)
$INTERVALO = "1 MINUTE";   //"10 SECOND"


//Seleccionar reservas pendientes para notificar
$sql = "
  SELECT 
    rs.id                AS reserva_id,
    rs.fecha_solicitud,
    r.id                 AS ride_id,
    r.nombre             AS ride_nombre,
    r.lugar_salida,
    r.lugar_llegada,
    r.fecha,
    r.hora,
    uChofer.id           AS chofer_id,
    uChofer.nombre       AS chofer_nombre,
    uChofer.email        AS chofer_email,
    uPasajero.nombre     AS pasajero_nombre
  FROM reservas rs
  JOIN rides     r       ON rs.ride_id     = r.id
  JOIN usuarios  uChofer ON r.usuario_id   = uChofer.id
  JOIN usuarios  uPasajero ON rs.pasajero_id = uPasajero.id
  WHERE rs.estado = 'pendiente'
    AND rs.notificado = 0
    AND rs.fecha_solicitud <= (NOW() - INTERVAL $INTERVALO)
  ORDER BY uChofer.id, rs.id
";

echo "[SQL] Buscando reservas pendientes…\n";
$result = $conn->query($sql);

if (!$result) {
  echo "Error en SELECT: {$conn->error}\n";
  exit(1);
}

$pendientesPorChofer = [];

//Agrupar por chofer
while ($row = $result->fetch_assoc()) {
  $cid = (int)$row['chofer_id'];
  if (!isset($pendientesPorChofer[$cid])) {
    $pendientesPorChofer[$cid] = [
      'nombre'   => $row['chofer_nombre'],
      'email'    => $row['chofer_email'],
      'reservas' => []
    ];
  }
  $pendientesPorChofer[$cid]['reservas'][] = $row;
}

if (empty($pendientesPorChofer)) {
  echo "No hay reservas pendientes para notificar.\n";
  echo "=== Fin ===\n";
  exit(0);
}


//Enviar correo a cada chofer y marcar como notificado
$totalChoferesNotificados = 0;
$totalReservasMarcadas    = 0;

foreach ($pendientesPorChofer as $chofer_id => $info) {
  $reservas = $info['reservas'];

  //Construir lista HTML
  $itemsHtml = [];
  foreach ($reservas as $r) {
    $itemsHtml[] = sprintf(
      "<li><strong>%s</strong> — %s → %s — %s %s — Pasajero: %s</li>",
      htmlspecialchars($r['ride_nombre']),
      htmlspecialchars($r['lugar_salida']),
      htmlspecialchars($r['lugar_llegada']),
      htmlspecialchars($r['fecha']),
      htmlspecialchars($r['hora']),
      htmlspecialchars($r['pasajero_nombre'])
    );
  }

  $html = "
    <div style='font-family: Arial, sans-serif;'>
      <p>Hola {$info['nombre']},</p>
      <p>Tienes solicitudes de reserva <strong>pendientes</strong> por más de {$INTERVALO}:</p>
      <ul>" . implode('', $itemsHtml) . "</ul>
      <p>Ingresa a la plataforma para aceptarlas o rechazarlas.</p>
    </div>
  ";

  echo "Enviando a chofer #{$chofer_id} ({$info['email']})… ";

  $enviado = enviarCorreoAviso(
    $info['email'],
    $info['nombre'],
    "Tienes solicitudes de reserva pendientes",
    $html
  );

  if ($enviado) {
    //Marcar reservas de este chofer como notificado=1
    $ids = array_map(fn($r) => (int)$r['reserva_id'], $reservas);
    $idList = implode(',', $ids);

    $upd = "
      UPDATE reservas 
      SET notificado = 1, actualizado_en = NOW()
      WHERE id IN ($idList)
    ";
    if (!$conn->query($upd)) {
      echo "ERROR al actualizar notificado: {$conn->error}\n";
    } else {
      $totalChoferesNotificados++;
      $totalReservasMarcadas += count($ids);
      echo "OK (reservas: $idList)\n";
    }
  } else {
    echo "FALLO (no se marcará notificado)\n";
  }
}

echo "Choferes notificados: {$totalChoferesNotificados}\n";
echo "Reservas marcadas como notificado: {$totalReservasMarcadas}\n";
echo "=== Fin ===\n";
