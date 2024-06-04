<?php

require __DIR__ . '/../vendor/autoload.php';
// Incluir el archivo de conexión a la base de datos
/** @var mysqli */
$db = require_once __DIR__ . '/conexion_be.php';
include __DIR__ . '/partials/header.php';

/* Selecciona campo ci_est y cambiale el nombre a cedula, ..., de la tabla estudiantes */
$sql = <<<SQL
  SELECT  e.cedula, e.nombre, e.apellido, e.fecha_nacimiento, e.estado_nacimiento, e.lugar_nacimiento,
  e.genero, e.fecha_registro, r.nombre as nombresRepresentante,
  r.apellido as apellidosRepresentante FROM estudiantes e
  JOIN representantes r ON r.id = e.id_representante
SQL;


$result = $db->query($sql);


?>

<div class="container card card-body table-responsive">
  <table id="tablaEstudiantes" class="table table-striped datatable">
    <thead>
      <tr>
        <th>Cédula</th>
        <th>Nombres</th>
        <th>Apellidos</th>
        <th>Fecha de nacimiento</th>
        <th>Edad</th>
        <th>Estado de nacimiento</th>
        <th>Municipio de nacimiento</th>
        <th>Sexo</th>
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
          <td><?= $mostrar['nombresRepresentante'] . ' ' . $mostrar['apellidosRepresentante'] ?></td>
          <td>
            <form method="post">
              <button data-bs-toggle= "tooltip" title="Eliminar" class="btn btn-outline-danger fs-4 p-1" formaction="eliminar-estudiante.php?cedula=<?= $mostrar['cedula'] ?>">
                <i class="ri-delete-bin-line"></i>
              </button>
              <button data-bs-toggle= "tooltip" title="Editar" class="btn btn-outline-dark fs-4 p-1" formaction="editar-estudiante.php?cedula=<?= $mostrar['cedula'] ?>">
                <i class="ri-edit-box-line "></i>
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
