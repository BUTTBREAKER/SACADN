<?php

require_once __DIR__ . '/conexion_be.php';

// Comprobar si se ha enviado una solicitud para cambiar el estado del estudiante 
if (isset($_GET['toggle_estado'])) {
  // Obtener el ID del estudiante  y el nuevo estado
  $estudiante_id = $_GET['estudiante_id'];
  $nuevo_estado = $_GET['nuevo_estado'];

  // Actualizar el estado del estudiante en la base de datos
  $update_sql = "UPDATE  estudiantes SET estado = '$nuevo_estado' WHERE id = $estudiante_id";
  $conexion->query($update_sql);
  header('Location: ./Estudiantes.php');
  exit;
}
