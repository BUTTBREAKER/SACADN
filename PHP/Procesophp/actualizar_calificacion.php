<?php
session_start();
require_once __DIR__ . '/../conexion_be.php';

if ($conexion === false) {
    $_SESSION['mensaje'] = 'Error al conectar con la base de datos.';
    header('Location: ../cargar_notas.php');
    exit;
}

$id_asignacion = $_POST['id_asignacion'] ?? null;
$id_estudiante = $_POST['id_estudiante'] ?? null;
$nueva_calificacion = $_POST['nueva_calificacion'] ?? null;
$id_usuario_actualizacion = $_SESSION['usuario_id'];
$id_periodo = $_POST['id_periodo'] ?? null;

if (!$id_asignacion || !$id_estudiante || !$nueva_calificacion || !$id_periodo) {
    $_SESSION['mensaje'] = 'Datos insuficientes para actualizar la calificación.';
    header('Location: ../calificaciones.php');
    exit;
}

// Verificar si ya existe un boletín para este estudiante y momento
$id_momento = $_POST['id_momento'] ?? null;
$stmt_verificar_boletin = $conexion->prepare("SELECT id FROM boletines WHERE id_estudiante = ? AND id_momento = ? AND id_periodo = ?");
$stmt_verificar_boletin->bind_param("iii", $id_estudiante, $id_momento, $id_periodo);
$stmt_verificar_boletin->execute();
$result_verificar_boletin = $stmt_verificar_boletin->get_result();
$stmt_verificar_boletin->close();

if ($result_verificar_boletin->num_rows === 0) {
    // Si no existe un boletín, crear uno nuevo
    $stmt_insertar_boletin = $conexion->prepare("INSERT INTO boletines (id_estudiante, id_momento, id_periodo) VALUES (?, ?, ?)");
    $stmt_insertar_boletin->bind_param("iii", $id_estudiante, $id_momento, $id_periodo);
    $stmt_insertar_boletin->execute();
    $stmt_insertar_boletin->close();
}

// Obtener id_boletin
$stmt_obtener_boletin = $conexion->prepare("SELECT id FROM boletines WHERE id_estudiante = ? AND id_momento = ? AND id_periodo = ?");
$stmt_obtener_boletin->bind_param("iii", $id_estudiante, $id_momento, $id_periodo);
$stmt_obtener_boletin->execute();
$result_boletin = $stmt_obtener_boletin->get_result();
$id_boletin = $result_boletin->fetch_assoc()['id'];
$stmt_obtener_boletin->close();

// Obtener id_materia
$stmt_obtener_materia = $conexion->prepare("SELECT id_materia FROM asignaciones WHERE id = ?");
$stmt_obtener_materia->bind_param("i", $id_asignacion);
$stmt_obtener_materia->execute();
$result_materia = $stmt_obtener_materia->get_result();
$id_materia = $result_materia->fetch_assoc()['id_materia'];
$stmt_obtener_materia->close();

// Verificar si ya existe una calificación registrada para esta asignación y estudiante
$stmt_verificar_calificacion = $conexion->prepare("SELECT id FROM calificaciones WHERE id_boletin = ? AND id_materia = ?");
$stmt_verificar_calificacion->bind_param("ii", $id_boletin, $id_materia);
$stmt_verificar_calificacion->execute();
$result_verificar_calificacion = $stmt_verificar_calificacion->get_result();
$stmt_verificar_calificacion->close();

if ($result_verificar_calificacion->num_rows > 0) {
    // Actualizar la calificación existente
    $stmt_actualizar_calificacion = $conexion->prepare("UPDATE calificaciones SET calificacion = ?, id_usuario = ?, fecha_registro = CURRENT_TIMESTAMP WHERE id_boletin = ? AND id_materia = ?");
    $stmt_actualizar_calificacion->bind_param("diii", $nueva_calificacion, $id_usuario_actualizacion, $id_boletin, $id_materia);
    $stmt_actualizar_calificacion->execute();
    $stmt_actualizar_calificacion->close();
    $_SESSION['mensaje'] = 'Calificación actualizada correctamente.';
} else {
    // Insertar una nueva calificación
    $stmt_insertar_calificacion = $conexion->prepare("INSERT INTO calificaciones (id_materia, id_boletin, id_usuario, calificacion, id_periodo) VALUES (?, ?, ?, ?, ?)");
    $stmt_insertar_calificacion->bind_param("iiiii", $id_materia, $id_boletin, $id_usuario_actualizacion, $nueva_calificacion, $id_periodo);
    $stmt_insertar_calificacion->execute();
    $stmt_insertar_calificacion->close();
    $_SESSION['mensaje'] = 'Calificación asignada correctamente.';
}

header('Location: ../calificaciones.php');
?>
