<?php

require __DIR__ . '/../vendor/autoload.php';
// Incluir el archivo de conexión a la base de datos
/** @var mysqli */
$db = require_once __DIR__ . '/conexion_be.php';
include_once __DIR__ . '/../Assets/Menu/Menu.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nuevo Periodo</title>
  <link rel="stylesheet" href="../Assets/simple-datatables/simple-datatables.css">
</head>
<body>

	<div method="post" action="./guardar_periodo.php" autocomplete="off">
  <h2>Ingresar Nuevo Periodo</h2>
	<div class="input-contenedor">
		<label for="nombre">Periodo</label> 
		<input type="text" id="registernombre" name="nombre" required>
     <a href="guardar_periodo.php">
     		<button>Guardar</button>
		</a>
	</div>
	</div>

</body>
</html>