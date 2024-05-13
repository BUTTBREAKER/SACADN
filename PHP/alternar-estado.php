<?php

require_once __DIR__ . '/conexion_be.php';

// Comprobar si se ha enviado una solicitud para cambiar el estado del usuario
if (isset($_GET['toggle_estado'])) {
  // Obtener el ID del usuario y el nuevo estado
  $usuario_id = $_GET['usuario_id'];
  $nuevo_estado = $_GET['nuevo_estado'];

  // Actualizar el estado del usuario en la base de datos
  $update_sql = "UPDATE usuarios SET estado = '$nuevo_estado' WHERE ID = $usuario_id";
  $conexion->query($update_sql);
  header('Location: ./ModuloA.php');
  exit;
}
