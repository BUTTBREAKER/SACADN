<?php

require_once __DIR__ . '/conexion_be.php';

// Comprobar si se ha enviado una solicitud para cambiar el estado del representante
if (isset($_GET['toggle_estado'])) {
  // Obtener el ID del representante y el nuevo estado
  $representante_id = $_GET['representante_id'];
  $nuevo_estado = $_GET['nuevo_estado'];

  // Actualizar el estado del representante en la base de datos
  $update_sql = "UPDATE representantes SET estado = '$nuevo_estado' WHERE id = $representante_id";
  $conexion->query($update_sql);
  header('Location: ./Representantes.php');
  exit;
}