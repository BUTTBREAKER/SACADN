<?php

require __DIR__ . '/../vendor/autoload.php';
// Incluir el archivo de conexión a la base de datos
/** @var mysqli */
$db = require_once __DIR__ . '/conexion_be.php';
include __DIR__ . '/partials/header.php';

/* Seleccionar todas las asignaciones y calificaciones del periodo activo */
$sqlAsignaciones = <<<SQL
  SELECT
    p.nombre AS nombre_profesor,
    p.apellido AS apellido_profesor,
    ma.nombre AS materia,
    ne.nombre AS nivel_estudio,
    s.nombre AS seccion,
    c.calificacion,
    u.usuario AS usuario_subida,
    c.fecha_registro,
    e.nombres AS nombre_estudiante,
    e.apellidos AS apellido_estudiante
  FROM asignaciones a
  JOIN profesores p ON a.id_profesor = p.id
  JOIN materias ma ON a.id_materia = ma.id
  JOIN secciones s ON a.id_seccion = s.id
  JOIN niveles_estudio ne ON a.id_nivel_estudio = ne.id
  JOIN calificaciones c ON c.id_materia = ma.id AND c.id_periodo = a.id_periodo
  JOIN usuarios u ON u.id = c.id_usuario
  JOIN boletines b ON c.id_boletin = b.id
  JOIN estudiantes e ON b.id_estudiante = e.id
  JOIN periodos pe ON a.id_periodo = pe.id
  WHERE pe.estado = 'activo'
SQL;



$stmtAsignaciones = $db->prepare($sqlAsignaciones);
$stmtAsignaciones->execute();
$resultAsignaciones = $stmtAsignaciones->get_result();

?>

<div class="container card card-body">
  <h2>Calificaciones de todos los Profesores</h2>
  <?php if ($resultAsignaciones->num_rows > 0) { ?>
    <div class="table-responsive"> <!-- Añadir clase para tabla responsive -->
      <table id="calificaciones-table" class="table table-striped table-bordered">
        <thead>
          <tr>
            <th>Profesor</th>
            <th>Materia</th>
            <th>Nivel de Estudio</th>
            <th>Sección</th>
            <th>Estudiante</th>
            <th>Calificación</th>
            <th>Subido por</th>
            <th>Fecha de Registro</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($asignacion = $resultAsignaciones->fetch_assoc()) { ?>
            <tr>
              <td><?= htmlspecialchars($asignacion['nombre_profesor'] . ' ' . $asignacion['apellido_profesor']) ?></td>
              <td><?= htmlspecialchars($asignacion['materia']) ?></td>
              <td><?= htmlspecialchars($asignacion['nivel_estudio']) ?></td>
              <td><?= htmlspecialchars($asignacion['seccion']) ?></td>
              <td><?= htmlspecialchars($asignacion['nombre_estudiante'] . ' ' . $asignacion['apellido_estudiante']) ?></td> <!-- Mostrar nombre del estudiante -->
              <td><?= htmlspecialchars($asignacion['calificacion']) ?></td>
              <td><?= htmlspecialchars($asignacion['usuario_subida']) ?></td>
              <td><?= htmlspecialchars($asignacion['fecha_registro']) ?></td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div> 
    <div>
      <a href="javascript:history.back()" class="btn btn-outline-secondary">Regresar</a>
    </div>
  <?php } else { ?>
    <p>No se encontraron calificaciones registradas.</p>
    <div>
      <a href="javascript:history.back()" class="btn btn-outline-secondary">Regresar</a>
    </div>
  <?php } ?>
</div>

<?php include __DIR__ . '/partials/footer.php' ?>

<!-- Integración de SimpleDatatables -->
<script src="../Assets/simple-datatables/simple-datatables.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    new simpleDatatables.DataTable('#calificaciones-table');
  });
</script>
