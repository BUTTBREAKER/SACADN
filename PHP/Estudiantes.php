<?php

require __DIR__ . '/../vendor/autoload.php';
// Incluir el archivo de conexión a la base de datos
/** @var mysqli */
$db = require_once __DIR__ . '/conexion_be.php';
include __DIR__ . '/partials/header.php';

/* Selecciona campo ci_est y cambiale el nombre a cedula, ..., de la tabla estudiantes */
$sql = <<<SQL
  SELECT id, cedula, nombre, apellido, fecha_nacimiento, estado_nacimiento, lugar_nacimiento,
  genero, fecha_registro id_representante FROM estudiantes
SQL;

$result = $db->query($sql);

?>

<div class="container" style="overflow-x: auto;">
  <table id="tablaEstudiantes" class="datatable">
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
        <th>Fecha</th>
        <th>Representante</th>
        <th>Opciones</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($mostrar = $result->fetch_assoc()) { ?>
        <tr>
          <td><?= $mostrar['cedula'] ?></td>
          <td><?= $mostrar['nombre'] ?></td>
          <td><?= $mostrar['apellido'] ?></td>
          <td><?= formatearFecha($mostrar['fecha_nacimiento']) ?></td>
          <td><?= calcularEdad($mostrar['fecha_nacimiento']) ?></td>
          <td><?= $mostrar['estado_nacimiento'] ?></td>
          <td><?= $mostrar['lugar_nacimiento'] ?></td>
          <td><?= $mostrar['genero'] ?></td>
          <td><?= formatearFecha($mostrar['fecha_registro']) ?></td>
          <td><?= $mostrar['id_representante'] ?></td>
          <td>
            <form method="post">
              <button formaction="eliminar-estudiante.php?cedula=<?= $mostrar['cedula'] ?>">
                Eliminar
              </button>
              <button formaction="editar-estudiante.php?cedula=<?= $mostrar['cedula'] ?>">
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
  const tablaEstudiantes = new simpleDatatables.DataTable("#tablaEstudiantes");
</script>

<?php include __DIR__ . '/partials/footer.php' ?>
