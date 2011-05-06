<?php

require __DIR__ . '/../vendor/autoload.php';
// Incluir el archivo de conexión a la base de datos
/** @var mysqli */
$db = require_once __DIR__ . '/conexion_be.php';
include __DIR__ . '/partials/header.php';

/* Seleccionar representantes con el conteo de estudiantes */
$sql = <<<SQL
  SELECT r.id, r.cedula, r.nombre, r.apellido, r.fecha_nacimiento, r.lugar_nacimiento,
  r.genero, r.telefono, r.direccion, r.fecha_registro, COUNT(e.id) AS num_estudiantes
  FROM representantes r
  LEFT JOIN estudiantes e ON r.id = e.id_representante
  GROUP BY r.id
SQL;


$result = $db->query($sql);


?>

<div class="container card card-body table-responsive">
  <table id="tablaRepresentantes" class="table datatable">
    <thead>
      <tr>
        <th>Cédula</th>
        <th>Nombres</th>
        <th>Apellidos</th>
        <th>Fecha de nacimiento</th>
        <th>Edad</th>
        <th>Lugar de nacimiento</th>
        <th>Sexo</th>
        <th>Teléfono</th>
        <th>Dirección</th>
        <th>Num.Representados</th>
        <th>Opciones</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($mostrar = $result->fetch_assoc()) : ?>
        <tr>
          <td><?= htmlspecialchars($mostrar['cedula']) ?></td>
          <td>
            <a href="detalles-representante.php?id=<?= $mostrar['id'] ?>">
              <?= htmlspecialchars($mostrar['nombre']) ?>
            </a>
          </td>
          <td><?= htmlspecialchars($mostrar['apellido']) ?></td>
          <td><?= formatearFecha($mostrar['fecha_nacimiento']) ?></td>
          <td><?= calcularEdad($mostrar['fecha_nacimiento']) ?></td>
          <td><?= htmlspecialchars($mostrar['lugar_nacimiento']) ?></td>
          <td><?= htmlspecialchars($mostrar['genero']) ?></td>
          <td><?= htmlspecialchars($mostrar['telefono']) ?></td>
          <td><?= htmlspecialchars($mostrar['direccion']) ?></td>
          <td><?= htmlspecialchars($mostrar['num_estudiantes']) ?></td>
          <td>
            <form method="post">
              <button data-bs-toggle="tooltip" title="Eliminar" class="btn btn-outline-danger fs-4 p-1" formaction="eliminar-representante.php?cedula=<?= $mostrar['cedula'] ?>">
                <i class="ri-delete-bin-line"></i>
              </button>
              <button data-bs-toggle="tooltip" title="Editar" class="btn btn-outline-dark fs-4 p-1" formaction="editar-representante.php?cedula=<?= $mostrar['cedula'] ?>">
                <i class="ri-edit-box-line"></i>
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
  const tablaRepresentantes = new simpleDatatables.DataTable('#tablaRepresentantes');
</script>

<?php include __DIR__ . '/partials/footer.php' ?>
