<?php
require_once __DIR__ . '/../conexion_be.php';

header('Content-Type: application/json');

$id_nivel_estudio = $_POST['id_nivel_estudio'] ?? null;
$id_seccion = $_POST['id_seccion'] ?? null;

if ($id_nivel_estudio && $id_seccion) {
  $stmt_estudiantes = $conexion->prepare("
        SELECT e.id, CONCAT(e.nombres, ' ', e.apellidos) as nombre
        FROM estudiantes e
        JOIN inscripciones i ON e.id = i.id_estudiante
        JOIN secciones s ON i.id_seccion = s.id
        WHERE s.id_nivel_estudio = ? AND s.id = ?
    ");
  $stmt_estudiantes->bind_param("ii", $id_nivel_estudio, $id_seccion);
  $stmt_estudiantes->execute();
  $result_estudiantes = $stmt_estudiantes->get_result();
  $estudiantes = $result_estudiantes->fetch_all(MYSQLI_ASSOC);
  $stmt_estudiantes->close();

  echo json_encode(['status' => 'success', 'data' => $estudiantes]);
} else {
  echo json_encode(['status' => 'error', 'message' => 'Datos incompletos.']);
}

$conexion->close();
