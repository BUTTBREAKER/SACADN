<?php

require __DIR__ . '/../vendor/autoload.php';

/** @var mysqli */
$db = require_once __DIR__ . '/conexion_be.php';
include __DIR__ . '/partials/header.php';

$sql = <<<SQL
  SELECT id, cedula, nombre, apellido, fecha_nacimiento, estado_nacimiento,
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
          <td><a href="detalles-profesor.php?id=<?= $mostrar['id'] ?>"><?= $mostrar['nombre'] ?></a></td>
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
              <button data-bs-toggle="tooltip" title="Eliminar" class="btn btn-outline-danger fs-4 p-1" formaction="eliminar-profesor.php?cedula=<?= $mostrar['cedula'] ?>">
                <i class="ri-delete-bin-line"></i>
              </button>
              <button data-bs-toggle="tooltip" title="Editar" class="btn btn-outline-dark fs-4 p-1" formaction="editar-profesor.php?cedula=<?= $mostrar['cedula'] ?>">
                <i class="ri-edit-box-line "></i>
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
