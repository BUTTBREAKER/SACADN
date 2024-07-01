<?php

require __DIR__ . '/../vendor/autoload.php';
// Incluir el archivo de conexión a la base de datos
/** @var mysqli */
$db = require_once __DIR__ . '/conexion_be.php';
include __DIR__ . '/partials/header.php';

// Obtener el ID del representante de la URL
$idRepresentante = $_GET['id'] ?? null;

if (!$idRepresentante) {
    echo "No se ha proporcionado un ID de representante.";
    exit;
}

/* Seleccionar los detalles del representante */
$sql = "SELECT * FROM representantes WHERE id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param('i', $idRepresentante);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "No se encontraron detalles para este representante.";
    exit;
}


$representante = $result->fetch_assoc();

/* Seleccionar los estudiantes representados por este representante junto con su nivel de estudio y sección */
$sqlEstudiantes = <<<SQL
  SELECT e.cedula, e.nombre, e.apellido, e.fecha_nacimiento, ne.nombre AS nivel_estudio, s.nombre AS seccion,
  ae.fecha_registro AS fecha
  FROM estudiantes e
  JOIN asignaciones_estudiantes ae ON e.id = ae.id_estudiante
  JOIN niveles_estudio ne ON ae.id_nivel_estudio = ne.id
  JOIN secciones s ON ae.id_seccion = s.id
  WHERE e.id_representante = ?
SQL;

$stmtEstudiantes = $db->prepare($sqlEstudiantes);
$stmtEstudiantes->bind_param('i', $idRepresentante);
$stmtEstudiantes->execute();
$resultEstudiantes = $stmtEstudiantes->get_result();

?>

<div class="container card card-body">
  <h2>Detalles del Representante</h2>
  <p><strong>Nombre:</strong> <?= htmlspecialchars($representante['nombre']) ?></p>
  <p><strong>Apellido:</strong> <?= htmlspecialchars($representante['apellido']) ?></p>
  <p><strong>Teléfono:</strong> <?= htmlspecialchars($representante['telefono']) ?></p>
  <p><strong>Dirección:</strong> <?= htmlspecialchars($representante['direccion']) ?></p>

  <h3>Estudiantes Representados</h3>
  <?php if ($resultEstudiantes->num_rows > 0) { ?>
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Cédula</th>
          <th>Nombre</th>
          <th>Apellido</th>
          <th>Fecha de Nacimiento</th>
          <th>Nivel de Estudio</th>
          <th>Sección</th>
          <th>Fecha de Inscripción/Hora</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($estudiante = $resultEstudiantes->fetch_assoc()) { ?>
          <tr>
            <td><?= htmlspecialchars($estudiante['cedula']) ?></td>
            <td><?= htmlspecialchars($estudiante['nombre']) ?></td>
            <td><?= htmlspecialchars($estudiante['apellido']) ?></td>
            <td><?= htmlspecialchars(formatearFecha($estudiante['fecha_nacimiento'])) ?></td>
            <td><?= htmlspecialchars($estudiante['nivel_estudio']) ?></td>
            <td><?= htmlspecialchars($estudiante['seccion']) ?></td>
            <td><?= htmlspecialchars($estudiante['fecha']) ?></td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  <?php } else { ?>
    <p>Este representante no tiene estudiantes asignados.</p>
  <?php } ?>
  <div >
        <buttontype= "button" class="btn-group btn-group-lg mx-3"><a href="javascript:history.back()" class="btn btn-outline-secondary">Regresar</a></button>
     </div>
</div>

<?php include __DIR__ . '/partials/footer.php' ?>
