<?php

require_once __DIR__ . '/conexion_be.php';

// Comprobar si se ha enviado una solicitud para cambiar el estado del profesor
if (isset($_GET['toggle_estado'])) {
  // Obtener el ID del profesor y el nuevo estado
  $profesor_id = $_GET['profesor_id'];
  $nuevo_estado = $_GET['nuevo_estado'];

  // Actualizar el estado del profesor en la base de datos
  $update_sql = "UPDATE profesores SET estado = '$nuevo_estado' WHERE id = $profesor_id";
  $conexion->query($update_sql);
  header('Location: ./Profesores.php');
  exit;
}
