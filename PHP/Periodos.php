<?php
// Verificar que solo los Administradores pueden acceder
include __DIR__ . '/partials/header.php';
require __DIR__ . "/Middlewares/autorizacion.php";

// Incluir archivo de conexión a la base de datos
require_once __DIR__ . '/conexion_be.php';

if (!isset($conexion)) {
  die('Error: No se pudo establecer la conexión a la base de datos.');
}

$sql = "SELECT id, anio_inicio AS periodo FROM periodos";
$resultado = $conexion->query($sql);

if (!$resultado) {
  die('Error en la consulta: ' . $conexion->error);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Periodos registrados</title>
  <link rel="stylesheet" href="../Assets/simple-datatables/simple-datatables.css">
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

    .row {
      margin: 10px 0;
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

    table {
      width: 100%;
      border-collapse: collapse;
      margin: 20px 0;
    }

    th,
    td {
      padding: 12px;
      text-align: left;
      border-bottom: 1px solid #ddd;
    }

    th {
      background-color: #f2f2f2;
    }

    tr:hover {
      background-color: #f5f5f5;
    }
  </style>
</head>

<body>
  <div class="contenedor">
    <?php if ($resultado->num_rows == 0) { ?>
      <h2>No se encuentra registrado ningún periodo actualmente</h2>
      <h5>¿Desea registrar un nuevo periodo?</h5>
    <?php } else { ?>
      <h2>Periodos registrados</h2>
    <?php } ?>

    <div class="row">
      <a href="nuevo_periodo.php">
        <button type="button">Nuevo periodo</button>
      </a>
    </div>

    <div style="overflow-x: auto;">
      <table id="tablaPeriodos" class="datatable">
        <thead>
          <tr>
            <th>ID</th>
            <th>Periodo</th>
            <th>Opciones</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($mostrar = $resultado->fetch_assoc()) { ?>
            <tr>
              <td><?= $mostrar['id'] ?></td>
              <td><?= $mostrar['periodo'] ?>-<?= $mostrar['periodo'] + 1 ?></td>
              <td>
                <form method="post">
                  <button formaction="periodos.php?id=<?= $mostrar['id'] ?>">
                    Ver periodo
                  </button>
                </form>
              </td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>

    <script src="../Assets/simple-datatables/simple-datatables.min.js"></script>
    <script>
      const tablaPeriodos = new simpleDatatables.DataTable("#tablaPeriodos");
    </script>
    <?php include('partials/footer.php') ?>
  </div>
</body>

</html>
