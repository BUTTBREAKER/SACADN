<?php
// Verificar que solo los Administradores pueden acceder
include __DIR__ . '/partials/header.php';
require __DIR__ . "/Middlewares/autorizacion.php";

// Incluir archivo de conexión a la base de datos
require_once __DIR__ . '/conexion_be.php';

if (!isset($conexion)) {
  die('Error: No se pudo establecer la conexión a la base de datos.');
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $anio_inicio = $_POST['anio_inicio'];
  $anio_cierre = $anio_inicio + 1;

  // Preparar la consulta para insertar el nuevo periodo
  $stmt = $conexion->prepare("INSERT INTO periodos (anio_inicio) VALUES (?)");
  $stmt->bind_param("i", $anio_inicio);

  if ($stmt->execute()) {
    echo "Nuevo periodo registrado exitosamente: $anio_inicio-$anio_cierre.";
  } else {
    echo "Error: " . $stmt->error;
  }

  $stmt->close();
  $conexion->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registrar Nuevo Periodo</title>
  <style>
    .contenedor {
      max-width: 1000px;
      margin: 0 auto;
      padding: 20px;
      background-color: aliceblue;
    }

    h2 {
      color: #333;
    }

    form {
      margin: 20px 0;
    }

    label {
      display: block;
      margin: 10px 0 5px;
    }

    input[type="number"] {
      padding: 10px;
      width: 100%;
      box-sizing: border-box;
      margin-bottom: 10px;
    }

    button {
      padding: 10px 20px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    button:hover {
      background-color: #45a049;
    }
  </style>
</head>
<body>
  <div class="contenedor">
  <h2>Registrar Nuevo Periodo</h2>
  <form method="post" action="nuevo_periodo.php">
    <label for="anio_inicio">Año de Inicio:</label>
    <input type="number" id="anio_inicio" name="anio_inicio" required>
    <button type="submit">Registrar</button>
  </form>
  </div>
  <?php include('partials/footer.php') ?>
</body>

</html>
