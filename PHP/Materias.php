<?php require __DIR__ . '/../vendor/autoload.php'; ?>

<?php
/** @var mysqli */
$db = require_once __DIR__ . '/conexion_be.php';
include __DIR__ . '/partials/header.php';

// Consulta para obtener la lista de materias
$sql = 'SELECT id, nombre, fecha_registro FROM materias';
$result = $db->query($sql);

?>

<div class="container card card-body table-responsive">
  <div class="container" style="overflow-x: auto;">
    <!-- Botón para registrar nueva materia -->
    <a href="nueva_materia.php" class="btn btn-primary">Registrar Nueva Materia</a>

    <!-- Tabla para mostrar las materias existentes -->
    <table id="tablaMaterias" class="datatable">
      <thead>
        <tr>
          <th>ID</th>
          <th>Nombre</th>
          <th>Fecha de Registro</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($materia = $result->fetch_assoc()) { ?>
          <tr>
            <td><?= $materia['id'] ?></td>
            <td><?= $materia['nombre'] ?></td>
            <td><?= $materia['fecha_registro'] ?></td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</div>
<script src="../Assets/simple-datatables/simple-datatables.min.js"></script>
<script>
  const tablaMaterias = new simpleDatatables.DataTable("#tablaMaterias");
</script>

<?php include __DIR__ . '/partials/footer.php' ?>
