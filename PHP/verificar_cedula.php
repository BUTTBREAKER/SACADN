<?php

require __DIR__ . '/../vendor/autoload.php';

// Incluir el archivo de conexión a la base de datos
/** @var mysqli */
$db = require_once __DIR__ . '/conexion_be.php';

header('Content-Type: application/json');

try {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['cedula'])) {
        $cedula = $data['cedula'];

        // Validar que la cédula sea un número
        if (!is_numeric($cedula)) {
            echo json_encode(['exists' => false, 'error' => 'Cédula no válida']);
            exit;
        }

        // Consulta para verificar si la cédula existe en la base de datos
        $query = $db->prepare("SELECT COUNT(*) as count FROM estudiantes WHERE cedula = ?");
        $query->bind_param('i', $cedula);
        $query->execute();
        $result = $query->get_result();
        $row = $result->fetch_assoc();

        $response = ['exists' => $row['count'] > 0];
        echo json_encode($response);
    } else {
        echo json_encode(['exists' => false, 'error' => 'Cédula no proporcionada']);
    }
} catch (Exception $e) {
    echo json_encode(['exists' => false, 'error' => 'Error en el servidor: ' . $e->getMessage()]);
}
