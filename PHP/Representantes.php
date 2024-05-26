<?php

require __DIR__ . '/../vendor/autoload.php';
// Incluir el archivo de conexión a la base de datos
/** @var mysqli */
$db = require_once __DIR__ . '/conexion_be.php';
include __DIR__ . '/partials/header.php';

$sql = <<<SQL
  SELECT id, cedula, nombre, apellido, fecha_nacimiento, lugar_nacimiento,
  genero, telefono, direccion, fecha_registro FROM representantes
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
          <td><?= $mostrar['lugar_nacimiento'] ?></td>
          <td><?= $mostrar['genero'] ?></td>
          <td><?= $mostrar['telefono'] ?></td>
          <td><?= $mostrar['direccion'] ?></td>
          <td>
            <form method="post" class="botones-tabla">
              <button class="btn btn-outline-danger" formaction="eliminar-representante.php?cedula=<?= $mostrar['cedula'] ?>">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                  <path fill="#ffffff" d="M7 21q-.825 0-1.412-.587T5 19V6q-.425 0-.712-.288T4 5t.288-.712T5 4h4q0-.425.288-.712T10 3h4q.425 0 .713.288T15 4h4q.425 0 .713.288T20 5t-.288.713T19 6v13q0 .825-.587 1.413T17 21zM17 6H7v13h10zM7 6v13zm5 7.9l1.9 1.9q.275.275.7.275t.7-.275t.275-.7t-.275-.7l-1.9-1.9l1.9-1.9q.275-.275.275-.7t-.275-.7t-.7-.275t-.7.275L12 11.1l-1.9-1.9q-.275-.275-.7-.275t-.7.275t-.275.7t.275.7l1.9 1.9l-1.9 1.9q-.275.275-.275.7t.275.7t.7.275t.7-.275z" />
                </svg>
              </button>
              <button class="btn btn-outline-dark" formaction="editar-representante.php?cedula=<?= $mostrar['cedula'] ?>">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 14 14">
                  <g fill="none" stroke="#ffffff" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m7.5 9l-3 .54L5 6.5L10.73.79a1 1 0 0 1 1.42 0l1.06 1.06a1 1 0 0 1 0 1.42Z" />
                    <path d="M12 9.5v3a1 1 0 0 1-1 1H1.5a1 1 0 0 1-1-1V3a1 1 0 0 1 1-1h3" />
                  </g>
                </svg>
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
