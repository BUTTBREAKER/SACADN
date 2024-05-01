<?php

require __DIR__ . '/../vendor/autoload.php';
// Incluir el archivo de conexión a la base de datos
/** @var mysqli */
$db = require_once __DIR__ . '/conexion_be.php';
include_once __DIR__ . '/../Assets/Menu/Menu.php';

/* Selecciona campo ci_prof y cambiale el nombre a cedula, ..., de la tabla profesores */
$sql = <<<SQL
  SELECT ci_prof AS cedula, nombre_completo AS nombres, apellido AS apellidos,
  fecha_nac AS fecha_nacimiento, estado as estado_nacimiento, lugar AS lugar_nacimiento,
  genero AS sexo, telefono, direccion, fech_prof AS fecha_registro FROM profesores
SQL;

$result = $db->query($sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lista de Profesores</title>
  <link rel="stylesheet" href="../Assets/simple-datatables/simple-datatables.css">
</head>

<body>

  <div style="overflow-x: auto;">
    <table id="tablaProfesores" class="datatable">
      <thead>
        <tr>
          <!-- <td>ID</td> -->
          <th>Cédula</th>
          <th>Nombres</th>
          <th>Apellidos</th>
          <th>Fecha de nacimiento</th>
          <th>Edad</th>
          <th>Estado de nacimiento</th>
          <th>Lugar de nacimiento</th>
          <th>Sexo</th>
          <th>Teléfono</th>
          <th>Dirección</th>
          <th>Fecha</th>
          <th>Opciones</th>
        </tr>
      </thead>
      <tbody>
      <?php while ($mostrar = $result->fetch_assoc()) { ?>
          <tr>
            <td><?= $mostrar['cedula'] ?></td>
            <td><?= $mostrar['nombres'] ?></td>
            <td><?= $mostrar['apellidos'] ?></td>
            <td><?= formatearFecha($mostrar['fecha_nacimiento']) ?></td>
            <td><?= calcularEdad($mostrar['fecha_nacimiento']) ?></td>
            <td><?= $mostrar['estado_nacimiento'] ?></td>
            <td><?= $mostrar['lugar_nacimiento'] ?></td>
            <td><?= $mostrar['sexo'] ?></td>
            <td><?= $mostrar['telefono'] ?></td>
            <td><?= $mostrar['direccion'] ?></td>
            <td><?= formatearFecha($mostrar['fecha_registro']) ?></td>
            <td>
              <form method="post">
                <button formaction="eliminar-profesor.php?cedula=<?= $mostrar['cedula'] ?>">
                  Eliminar
                </button>
                <button formaction="editar-profesor.php?cedula=<?= $mostrar['cedula'] ?>">
                  Editar
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
    const tablaProfesores = new simpleDatatables.DataTable("#tablaProfesores");
  </script>
  <?php include('partials/footer.php') ?>
