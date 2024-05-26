<?php

require __DIR__ . '/../vendor/autoload.php';

/** @var mysqli */
$db = require_once __DIR__ . '/conexion_be.php';
include __DIR__ . '/partials/header.php';

$sql = <<<SQL
  SELECT cedula, nombre, apellido, fecha_nacimiento, estado_nacimiento,
  lugar_nacimiento, genero, telefono, direccion, fecha_registro FROM profesores
 SQL;

$result = $db->query($sql);

?>

<div class="container card card-body table-responsive">
  <table id="tablaProfesores" class="table datatable">
    <thead>
      <tr>
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
        <th>Opciones</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($mostrar = $result->fetch_assoc()) : ?>
        <tr>
          <td><?= $mostrar['cedula'] ?></td>
          <td><?= $mostrar['nombre'] ?></td>
          <td><?= $mostrar['apellido'] ?></td>
          <td><?= formatearFecha($mostrar['fecha_nacimiento']) ?></td>
          <td><?= calcularEdad($mostrar['fecha_nacimiento']) ?></td>
          <td><?= $mostrar['estado_nacimiento'] ?></td>
          <td><?= $mostrar['lugar_nacimiento'] ?></td>
          <td><?= $mostrar['genero'] ?></td>
          <td><?= $mostrar['telefono'] ?></td>
          <td><?= $mostrar['direccion'] ?></td>
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
      <?php endwhile ?>
    </tbody>
  </table>
</div>

<script src="../Assets/simple-datatables/simple-datatables.min.js"></script>
<script>
  const tablaProfesores = new simpleDatatables.DataTable("#tablaProfesores");
</script>

<?php include __DIR__ . '/partials/footer.php' ?>
