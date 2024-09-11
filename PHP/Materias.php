<?php require __DIR__ . '/../vendor/autoload.php'; ?>

<?php
/** @var mysqli */
$db = require_once __DIR__ . '/conexion_be.php';
include __DIR__ . '/partials/header.php';

// Obtener el periodo activo
$stmt_periodo_activo = $db->prepare("SELECT id FROM periodos WHERE estado = 'activo' LIMIT 1");
$stmt_periodo_activo->execute();
$periodo_activo = $stmt_periodo_activo->get_result()->fetch_assoc();
$stmt_periodo_activo->close();

$periodo_id = $periodo_activo['id'];

// Consulta para obtener la lista de materias del periodo activo
$sql = "SELECT m.id, m.nombre, m.fecha_registro
        FROM materias m
        WHERE m.id_periodo = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param("i", $periodo_id);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
?>

<div class="container card card-body table-responsive">
  <div class="container" style="overflow-x: auto;">
    <!-- Botón para registrar nueva materia -->
    <a href="nueva_materia.php" class="btn btn-primary">Registrar Nueva Materia</a>

    <!-- Tabla para mostrar las materias existentes -->
    <table id="tablaMaterias" class="datatable">
      <thead>
        <tr>
          <th>Nombre</th>
          <th>Fecha de Registro</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($materia = $result->fetch_assoc()) { ?>
          <tr>
            <td><?= htmlspecialchars($materia['nombre']) ?></td>
            <td><?= htmlspecialchars($materia['fecha_registro']) ?></td>
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

<?php include __DIR__ . '/partials/footer.php'; ?>
