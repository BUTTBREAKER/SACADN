<?php
require_once __DIR__ . '/../conexion_be.php';

if ($conexion === false) {
    die("Error al conectar con la base de datos.");
}

$id_nivel_estudio = $_GET['id_nivel_estudio'] ?? null;
$id_seccion = $_GET['id_seccion'] ?? null;

if ($id_nivel_estudio && $id_seccion) {
    $stmt = $conexion->prepare("SELECT e.id, e.nombre, e.apellido
                                FROM estudiantes e
                                JOIN asignaciones_estudiantes ae ON e.id = ae.id_estudiante
                                WHERE ae.id_nivel_estudio = ? AND ae.id_seccion = ?");
    $stmt->bind_param("ii", $id_nivel_estudio, $id_seccion);
    $stmt->execute();
    $result = $stmt->get_result();
    $estudiantes = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    echo json_encode($estudiantes);
} else {
    error_log("Faltan parámetros: id_nivel_estudio o id_seccion.");
}
?>
