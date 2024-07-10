<?php
require_once __DIR__ . '/../conexion_be.php';

if ($conexion === false) {
  die("Error al conectar con la base de datos.");
}

$id_nivel_estudio = $_GET['id_nivel_estudio'] ?? null;

if ($id_nivel_estudio) {
  $stmt = $conexion->prepare("SELECT m.id, m.nombre, CONCAT(p.nombre, ' ', p.apellido) AS nombre_profesor
                                FROM materias m
                                JOIN asignaciones a ON m.id = a.id_materia
                                JOIN profesores p ON a.id_profesor = p.id
                                WHERE a.id_nivel_estudio = ?");
  $stmt->bind_param("i", $id_nivel_estudio);
  $stmt->execute();
  $result = $stmt->get_result();
  $materias = $result->fetch_all(MYSQLI_ASSOC);
  $stmt->close();

  echo json_encode($materias);
} else {
  error_log("Falta parámetro: id_nivel_estudio.");
}
