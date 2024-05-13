<?php

require __DIR__ . '/../vendor/autoload.php';

/** @var mysqli */
$db = require_once __DIR__ . '/conexion_be.php';
include __DIR__ . '/../Assets/Menu/Menu.php';

$sql = 'SELECT ID_mater as id, codigo, nombre, fech_mater as fecha_registro FROM materias';

$result = $db->query($sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Lista de materias</title>

  <link rel="stylesheet" href="../Assets/simple-datatables/simple-datatables.css">
</head>

<body>
  <div style="overflow-x: auto;">
    <table id="tablaMaterias" class="datatable">
      <tr>
        <th>ID</th>
        <th>Código</th>
        <th>Nombre</th>
        <th>Fecha de registro</th>
      </tr>
      <?php while ($materia = $result->fetch_assoc()) { ?>
        <tr>
          <td><?= $materia['id'] ?></td>
          <td><?= substr(strtoupper($materia['nombre']), 0, 3) . '-' . $materia['codigo'] ?></td>
          <td><?= $materia['nombre'] ?></td>
          <td><?= formatearFecha($materia['fecha_registro']) ?></td>
        </tr>
      <?php } ?>
    </table>
  </div>

  <script src="../Assets/simple-datatables/simple-datatables.min.js"></script>
  <script>
    const tablaMaterias = new simpleDatatables.DataTable("#tablaMaterias");
  </script>
  <?php include __DIR__ . '/partials/footer.php' ?>
