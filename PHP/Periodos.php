<?php

require __DIR__ . '/../vendor/autoload.php';
// Incluir el archivo de conexión a la base de datos
/** @var mysqli */
$db = require_once __DIR__ . '/conexion_be.php';
include_once __DIR__ . '/../Assets/Menu/Menu.php';

$sql = <<<SQL
  SELECT ID_per AS id, nombre AS periodo, fech_per AS fecha_registro FROM periodos
SQL;

$result = $db->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Periodos registrados</title>
  <link rel="stylesheet" href="../Assets/simple-datatables/simple-datatables.css">
</head>
<body>
	<div>
  <h2>No se encuentra registrado ningun periodo actualmente</h2>
  <h5>¿Desea registrar un nuevo periodo?</h5>
   <div class="row">
          <a href="nuevo_periodo.php">
          <button type="submit">Nuevo periodo</button>
          </a>
         </div>
    <div style="overflow-x: auto;">
    <table id="tablaPeriodos" class="datatable">
      <thead>
        <tr>
          <th>ID</th> 
          <th>Periodo</th>
          <th>Fecha</th>
          <th>Opciones</th>
        </tr>
      </thead>
      <tbody>
      <?php while ($mostrar = $result->fetch_assoc()) { ?>
          <tr>
            <td><?= $mostrar['id'] ?></td>
            <td><?= $mostrar['periodo'] ?></td>
            <td><?= formatearFecha($mostrar['fecha_registro']) ?></td>
            <td>
              <form method="post">
                <button formaction="´periodos.php?=<?= $mostrar['id'] ?>">
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

	