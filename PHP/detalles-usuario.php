<?php

require __DIR__ . '/../vendor/autoload.php';
// Incluir el archivo de conexión a la base de datos
/** @var mysqli */
$db = require_once __DIR__ . '/conexion_be.php';
include __DIR__ . '/partials/header.php';

// Obtener el ID del representante de la URL
$idUsuario = $_GET['ID'] ?? null;

/* Seleccionar las asignaciones de materias para este profesor junto con el nivel de estudio y la sección */
$sql = <<<SQL
  SELECT ID, nombre, apellido, Cedula, Usuario, rol, estado FROM usuarios
SQL;

$stmt = $db->prepare($sql);
$stmt->execute();
$stmt->bind_param('i', $idUsuario);
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();


?>

<div class="container card card-body">
  <h2>Detalles del Usuario</h2>
  <p><strong>ID:</strong> <?php $usuario['ID'] ?></a></p>
  <p><strong>Nombre:</strong> <?php $usuario['nombre'] ?></a></p>
  <p><strong>Apellido:</strong> <?php $usuario['apellido'] ?></p>
  <p><strong>Cedula:</strong> <?php $usuario['cedula'] ?></p>
  <p><strong>Rol:</strong> <?php $usuario['rol'] ?></p>
  <p><strong>Usuario:</strong> <?php $usuario['usuario'] ?></p>
  <p><strong>Contraseña:</strong> <?php $usuario['clave'] ?></p>
      <div >
        <buttontype= "button" class="btn-group btn-group-lg mx-3 mb-4"><a href="javascript:history.back()" class="btn btn-outline-secondary">Regresar</a></button>
     </div>

<?php include __DIR__ . '/partials/footer.php' ?>
