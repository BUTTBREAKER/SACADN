<?php

require __DIR__ . '/../vendor/autoload.php';
// Incluir el archivo de conexión a la base de datos
/** @var mysqli */
$db = require_once __DIR__ . '/conexion_be.php';
include __DIR__ . '/partials/header.php';

/* Selecciona campo ci_est y cámbiale el nombre a cedula, ..., de la tabla estudiantes */
$sql = <<<SQL
  SELECT e.id, e.cedula, e.nombres, e.apellidos, e.fecha_nacimiento, e.estado_nacimiento, e.lugar_nacimiento,
  e.genero, e.estado, e.fecha_registro, r.id AS idRepresentante, r.nombre AS nombresRepresentante,
  r.apellido AS apellidosRepresentante
  FROM estudiantes e
  JOIN representantes r ON r.id = e.id_representante
SQL;


$result = $db->query($sql);

if (!$result) {
  echo "Error en la consulta: " . $db->error;
  exit;
}

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
        <th>Estado</th>
        <?php if ($role === 'A') : ?>
          <th>Opciones</th>
        <?php endif ?>
      </tr>
    </thead>
    <tbody>

      <?php while ($mostrar = $result->fetch_assoc()) { ?>
        <tr>
          <td><?= htmlspecialchars($mostrar['cedula']) ?></td>
          <td>
            <a href="detalles_estudiante.php?id=<?= $mostrar['id'] ?>">
              <?= htmlspecialchars($mostrar['nombres']) ?>
            </a>
          </td>
          <td><?= htmlspecialchars($mostrar['apellidos']) ?></td>
          <td><?= htmlspecialchars(formatearFecha($mostrar['fecha_nacimiento'])) ?></td>
          <td><?= htmlspecialchars(calcularEdad($mostrar['fecha_nacimiento'])) ?></td>
          <td><?= htmlspecialchars($mostrar['estado_nacimiento']) ?></td>
          <td><?= htmlspecialchars($mostrar['lugar_nacimiento']) ?></td>
          <td><?= htmlspecialchars($mostrar['genero']) ?></td>
          <td>
            <a href="detalles-representante.php?id=<?= htmlspecialchars($mostrar['idRepresentante']) ?>">
              <?= htmlspecialchars($mostrar['nombresRepresentante'] . ' ' . $mostrar['apellidosRepresentante']) ?>
            </a>
          </td>
          <td><?= htmlspecialchars($mostrar['estado']) ?></td>
          <?php if ($role === 'A') : ?>
            <td>
              <form method="post">
                <button data-bs-toggle="tooltip" title="Estado" class="btn btn-outline-dark fs-10 p-1">
                  <?php {
                    echo "<a data-action='toggle-status' data-studen-id='" . $mostrar['id'] . "' data-new-state='" . ($mostrar['estado'] == 'activo' ? 'inactivo' : 'activo') . "'  href='./alternar-estudiante.php?toggle_estado=true&estudiante_id=" . $mostrar['id'] . "&nuevo_estado=" . ($mostrar['estado'] == 'activo' ? 'inactivo' : 'activo') . "'>" . ($mostrar['estado'] === 'activo' ? 'Desactivar' : 'Activar')  . "</a>";
                  } ?>
                </button>
                <button data-bs-toggle="tooltip" title="Editar" class="btn btn-outline-dark fs-4 p-1" formaction="editar-estudiante.php?cedula=<?= htmlspecialchars($mostrar['cedula']) ?>">
                  <i class="ri-edit-box-line"></i>
                </button>
              </form>
            </td>
          <?php endif ?>
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
