<?php
session_start();
require_once __DIR__ . '/../conexion_be.php';

if ($conexion === false) {
  echo json_encode(['error' => 'Error al conectar con la base de datos.']);
  exit;
}

$id_asignacion = $_GET['id_asignacion'] ?? null;
$id_estudiante = $_GET['id_estudiante'] ?? null;

if (!$id_asignacion || !$id_estudiante) {
  echo json_encode(['error' => 'Datos insuficientes para verificar la calificación.']);
  exit;
}

$stmt_verificar_calificacion = $conexion->prepare("
    SELECT calificaciones.calificacion
    FROM calificaciones
    INNER JOIN boletines ON calificaciones.id_boletin = boletines.id
    INNER JOIN inscripciones ON boletines.id_estudiante = inscripciones.id_estudiante
    WHERE inscripciones.id_seccion = (SELECT id_seccion FROM asignaciones WHERE id = ?)
    AND inscripciones.id_estudiante = ?
    AND calificaciones.id_materia = (SELECT id_materia FROM asignaciones WHERE id = ?)
");

if (!$stmt_verificar_calificacion) {
  echo json_encode(['error' => 'Error al preparar la consulta: ' . $conexion->error]);
  exit;
}

$stmt_verificar_calificacion->bind_param("iii", $id_asignacion, $id_estudiante, $id_asignacion);
if (!$stmt_verificar_calificacion->execute()) {
  echo json_encode(['error' => 'Error al ejecutar la consulta: ' . $stmt_verificar_calificacion->error]);
  exit;
}

$result_verificar_calificacion = $stmt_verificar_calificacion->get_result();
$stmt_verificar_calificacion->close();

if ($result_verificar_calificacion->num_rows > 0) {
  $calificacion = $result_verificar_calificacion->fetch_assoc()['calificacion'];
  echo json_encode(['existe' => true, 'calificacion' => $calificacion]);
} else {
  echo json_encode(['existe' => false]);
}
