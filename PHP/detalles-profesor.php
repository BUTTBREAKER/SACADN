<?php
require __DIR__ . '/../vendor/autoload.php';
/** @var mysqli */
$db = require_once __DIR__ . '/conexion_be.php';
include __DIR__ . '/partials/header.php';

$idProfesor = $_GET['id'] ?? null;

if (!$idProfesor) {
  die("No se ha proporcionado un ID de profesor.");
}

$idPeriodoActivo = $db->query("SELECT id FROM periodos WHERE estado = 'activo'")->fetch_assoc()['id'] ?? null;

if (!$idPeriodoActivo) {
  die("No se ha definido un período activo.");
}

$stmt = $db->prepare("SELECT * FROM profesores WHERE id = ?");
$stmt->bind_param('i', $idProfesor);
$stmt->execute();
$profesor = $stmt->get_result()->fetch_assoc();

if (!$profesor) {
  die("No se encontraron detalles para este profesor.");
}

$sqlAsignaciones = "
    SELECT ma.nombre AS materia, ne.nombre AS nivel_estudio, s.nombre AS seccion
    FROM asignaciones a
    JOIN materias ma ON a.id_materia = ma.id
    JOIN secciones s ON a.id_seccion = s.id
    JOIN niveles_estudio ne ON a.id_nivel_estudio = ne.id
    WHERE a.id_profesor = ? AND a.id_periodo = ?
";

$stmtAsignaciones = $db->prepare($sqlAsignaciones);
$stmtAsignaciones->bind_param('ii', $idProfesor, $idPeriodoActivo);
$stmtAsignaciones->execute();
$asignaciones = $stmtAsignaciones->get_result();
?>

<div class="container card card-body">
  <h2>Detalles del Profesor</h2>
  <?php foreach (['nombre', 'apellido', 'telefono', 'direccion'] as $campo): ?>
    <p><strong><?= ucfirst($campo) ?>:</strong> <?= htmlspecialchars($profesor[$campo]) ?></p>
  <?php endforeach; ?>

  <h3>Asignaciones de Materias</h3>
  <?php if ($asignaciones->num_rows > 0): ?>
    <?php if ($role === 'A') : ?>
      <div>
        <a href="asignar-materias.php" class="btn btn-success mb-4">+ Nueva Materia</a>
      </div>
    <?php endif ?>
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Materia</th>
          <th>Nivel de Estudio</th>
          <th>Sección</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($asignacion = $asignaciones->fetch_assoc()): ?>
          <tr>
            <?php foreach ($asignacion as $valor): ?>
              <td><?= htmlspecialchars($valor) ?></td>
            <?php endforeach; ?>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p>Este profesor no tiene asignadas materias en este periodo.</p>
  <?php endif; ?>
  <div>
    <a href="javascript:history.back()" class="btn btn-outline-secondary mb-4">Regresar</a>
  </div>
</div>

<?php include __DIR__ . '/partials/footer.php' ?>
