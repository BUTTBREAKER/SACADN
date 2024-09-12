<?php
require_once __DIR__ . '/conexion_be.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

error_log('Recibida solicitud para alternar estado de periodo');

if (isset($_POST['id_periodo']) && isset($_POST['estado'])) {
    $periodo_id = intval($_POST['id_periodo']);
    $nuevo_estado = $_POST['estado'];

    error_log("Intentando actualizar periodo ID: $periodo_id a estado: $nuevo_estado");

    $sql = "UPDATE periodos SET estado = ? WHERE id = ?";
    $stmt = $conexion->prepare($sql);

    if ($stmt) {
        $stmt->bind_param('si', $nuevo_estado, $periodo_id);
        $result = $stmt->execute();

        if ($result) {
            if ($stmt->affected_rows > 0) {
                $response['success'] = true;
                $response['message'] = 'Estado actualizado correctamente';
                error_log('Estado actualizado correctamente');
            } else {
                $response['message'] = 'No se actualizó ningún registro. El ID puede no existir.';
                error_log('No se actualizó ningún registro. El ID puede no existir.');
            }
        } else {
            $response['message'] = 'Error al actualizar el estado del período: ' . $stmt->error;
            error_log('Error al actualizar el estado del período: ' . $stmt->error);
        }
        $stmt->close();
    } else {
        $response['message'] = 'Error en la preparación de la consulta: ' . $conexion->error;
        error_log('Error en la preparación de la consulta: ' . $conexion->error);
    }
} else {
    $response['message'] = 'Datos no recibidos';
    error_log('Datos no recibidos para actualizar el estado del periodo');
}

error_log('Respuesta enviada: ' . json_encode($response));
echo json_encode($response);
?>
