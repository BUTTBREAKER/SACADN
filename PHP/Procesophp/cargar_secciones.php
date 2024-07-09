<?php
require_once __DIR__ . '/../conexion_be.php';

if ($conexion === false) {
    die("Error al conectar con la base de datos.");
}

$id_nivel_estudio = $_GET['id_nivel_estudio'] ?? null;

if ($id_nivel_estudio) {
    $stmt = $conexion->prepare("SELECT DISTINCT s.id, s.nombre
                                FROM secciones s
                                JOIN asignaciones_estudiantes ae ON s.id = ae.id_seccion
                                WHERE ae.id_nivel_estudio = ?");
    $stmt->bind_param("i", $id_nivel_estudio);
    $stmt->execute();
    $result = $stmt->get_result();
    $secciones = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    echo json_encode($secciones);
} else {
    error_log("Falta el parámetro: id_nivel_estudio.");
}
?>
