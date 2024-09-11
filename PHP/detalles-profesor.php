<?php

require __DIR__ . '/../vendor/autoload.php';
// Incluir el archivo de conexión a la base de datos
/** @var mysqli */
$db = require_once __DIR__ . '/conexion_be.php';
include __DIR__ . '/partials/header.php';

// Obtener el ID del profesor de la URL
$idProfesor = $_GET['id'] ?? null;

if (!$idProfesor) {
  echo "No se ha proporcionado un ID de profesor.";
  exit;
}

// Obtener el ID del período activo
$sqlPeriodoActivo = "SELECT id FROM periodos WHERE estado = 'activo'";
$resultPeriodoActivo = $db->query($sqlPeriodoActivo);

if ($resultPeriodoActivo->num_rows === 0) {
  echo "No se ha definido un período activo.";
  exit;
}

$periodoActivo = $resultPeriodoActivo->fetch_assoc();
$idPeriodoActivo = $periodoActivo['id'];

/* Seleccionar los detalles del profesor */
$sql = "SELECT * FROM profesores WHERE id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param('i', $idProfesor);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
  echo "No se encontraron detalles para este profesor.";
  exit;
}

$profesor = $result->fetch_assoc();

/* Seleccionar las asignaciones de materias para este profesor junto con el nivel de estudio y la sección */
$sqlAsignaciones = <<<SQL
  SELECT ma.nombre AS materia, ne.nombre AS nivel_estudio, s.nombre AS seccion
  FROM asignaciones a
  JOIN materias ma ON a.id_materia = ma.id
  JOIN secciones s ON a.id_seccion = s.id
  JOIN niveles_estudio ne ON a.id_nivel_estudio = ne.id
  WHERE a.id_profesor = ? AND a.id_periodo = ?
SQL;

$stmtAsignaciones = $db->prepare($sqlAsignaciones);
$stmtAsignaciones->bind_param('ii', $idProfesor, $idPeriodoActivo);
$stmtAsignaciones->execute();
$resultAsignaciones = $stmtAsignaciones->get_result();

?>

<div class="container card card-body">
  <h2>Detalles del Profesor</h2>
  <p><strong>Nombre:</strong> <?= htmlspecialchars($profesor['nombre']) ?></p>
  <p><strong>Apellido:</strong> <?= htmlspecialchars($profesor['apellido']) ?></p>
  <p><strong>Teléfono:</strong> <?= htmlspecialchars($profesor['telefono']) ?></p>
  <p><strong>Dirección:</strong> <?= htmlspecialchars($profesor['direccion']) ?></p>

  <h3>Asignaciones de Materias</h3>
  <?php if ($resultAsignaciones->num_rows > 0) { ?>
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Materia</th>
          <th>Nivel de Estudio</th>
          <th>Sección</th>
        </tr>
        <div>
          <buttontype= "button" class="btn-group btn-group-lg mx-3 mb-4"><a href="asignar-materias.php" class="btn btn-success mb-4">+ Nueva Materia</a></button>
        </div>
      </thead>
      <tbody>
        <?php while ($asignacion = $resultAsignaciones->fetch_assoc()) { ?>
          <tr>
            <td><?= htmlspecialchars($asignacion['materia']) ?></td>
            <td><?= htmlspecialchars($asignacion['nivel_estudio']) ?></td>
            <td><?= htmlspecialchars($asignacion['seccion']) ?></td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
    <div>
      <buttontype= "button" class="btn-group btn-group-lg mx-3 mb-4"><a href="javascript:history.back()" class="btn btn-outline-secondary">Regresar</a></button>
    </div>
  <?php } else { ?>
    <p>Este profesor no tiene asignadas materias en este periodo.</p>
    <buttontype= "button" class="btn-group btn-group-lg mx-3 mb-4"><a href="asignar-materias.php" class="btn btn-success mb-4">+ Nueva Materia</a></button>
  <?php } ?>
</div>

<?php include __DIR__ . '/partials/footer.php' ?>
