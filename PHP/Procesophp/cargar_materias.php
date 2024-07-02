<?php
require_once __DIR__ . '/../conexion_be.php';

if ($conexion === false) {
    die("Error al conectar con la base de datos.");
}

$id_momento = $_GET['id_momento'] ?? null;
$id_nivel_estudio = $_GET['id_nivel_estudio'] ?? null;
$id_seccion = $_GET['id_seccion'] ?? null;

// Verificar si todas las variables tienen valores
if ($id_momento !== null && $id_nivel_estudio !== null && $id_seccion !== null) {
    // Ejecutar la consulta SQL
    $stmt = $conexion->prepare("SELECT a.id, m.nombre, p.nombre as profesor
                                FROM asignaciones a
                                JOIN materias m ON a.id_materia = m.id
                                JOIN profesores p ON a.id_profesor = p.id
                                WHERE a.id_momento = ? AND a.id_nivel_estudio = ? AND a.id_seccion = ?");
    $stmt->bind_param("iii", $id_momento, $id_nivel_estudio, $id_seccion);
    $stmt->execute();
    $result = $stmt->get_result();
    $materias = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    // Devolver los datos en formato JSON
    echo json_encode($materias);
}

?>
