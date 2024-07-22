<?php
// Incluir el archivo de conexión a la base de datos
/** @var mysqli */
$db = require_once __DIR__ . '/conexion_be.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$cedula = $data['cedula'];

$query = $db->prepare("SELECT * FROM estudiantes WHERE cedula = ?");
$query->bind_param('i', $cedula);
$query->execute();
$result = $query->get_result();
$estudiante = $result->fetch_assoc();

if ($estudiante) {
    echo json_encode(['exists' => true]);
} else {
    echo json_encode(['exists' => false, 'error' => 'Estudiante no encontrado']);
}
?>
