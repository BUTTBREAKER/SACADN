<?php
// Incluye las cabeceras y la conexión
include __DIR__ . '/partials/header.php';
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/conexion_be.php';

// Función para ejecutar consultas preparadas
function getData($conexion, $query, $param) {
  $stmt = $conexion->prepare($query);
  $stmt->bind_param("i", $param);
  $stmt->execute();
  $result = $stmt->get_result();
  $stmt->close();
  return $result;
}

try {
  // Obtén todos los periodos disponibles
  $stmt_periodos = $conexion->prepare("SELECT id, anio_inicio FROM periodos ORDER BY anio_inicio DESC");
  $stmt_periodos->execute();
  $result_periodos = $stmt_periodos->get_result();
  $stmt_periodos->close();

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $periodo_id = $_POST['periodo_id'] ?? null;

    if ($periodo_id) {
      // Consulta para maestros y materias
      $maestros_materias_query = "
                SELECT p.nombre AS profesor_nombre, p.apellido AS profesor_apellido,
                       m.nombre AS materia_nombre, n.nombre AS nivel_estudio,
                       s.nombre AS seccion, per.anio_inicio AS periodo
                FROM asignaciones a
                JOIN profesores p ON a.id_profesor = p.id
                JOIN materias m ON a.id_materia = m.id
                JOIN niveles_estudio n ON a.id_nivel_estudio = n.id
                JOIN secciones s ON a.id_seccion = s.id
                JOIN periodos per ON a.id_periodo = per.id
                WHERE per.id = ?";
      $result_maestros_materias = getData($conexion, $maestros_materias_query, $periodo_id);

      // Consulta para representantes y estudiantes
      $representantes_estudiantes_query = "
                SELECT r.nombre AS representante_nombre, r.apellido AS representante_apellido,
                       e.nombres AS estudiante_nombres, e.apellidos AS estudiante_apellidos,
                       n.nombre AS nivel_estudio, s.nombre AS seccion, per.anio_inicio AS periodo
                FROM representantes r
                JOIN estudiantes e ON r.id = e.id_representante
                JOIN inscripciones i ON e.id = i.id_estudiante
                JOIN secciones s ON i.id_seccion = s.id
                JOIN periodos per ON s.id_periodo = per.id
                JOIN niveles_estudio n ON s.id_nivel_estudio = n.id
                WHERE per.id = ?";
      $result_representantes_estudiantes = getData($conexion, $representantes_estudiantes_query, $periodo_id);

      // Consulta actualizada para secciones, estudiantes y calificaciones definitivas
      $secciones_estudiantes_calificaciones_query = "
                SELECT s.nombre AS seccion_nombre, e.nombres AS estudiante_nombres,
                       e.apellidos AS estudiante_apellidos, m.nombre AS materia_nombre,
                       IFNULL(ROUND(AVG(c.calificacion), 2), 'Sin Calificación') AS calificacion_definitiva,
                       n.nombre AS nivel_estudio, per.anio_inicio AS periodo
                FROM secciones s
                JOIN inscripciones i ON s.id = i.id_seccion
                JOIN estudiantes e ON i.id_estudiante = e.id
                JOIN asignaciones a ON s.id = a.id_seccion
                JOIN materias m ON a.id_materia = m.id
                LEFT JOIN boletines b ON e.id = b.id_estudiante
                LEFT JOIN calificaciones c ON b.id = c.id_boletin AND c.id_materia = m.id
                JOIN periodos per ON s.id_periodo = per.id
                JOIN niveles_estudio n ON s.id_nivel_estudio = n.id
                WHERE per.id = ?
                GROUP BY s.nombre, e.nombres, e.apellidos, m.nombre, n.nombre, per.anio_inicio
                ORDER BY s.nombre, e.apellidos, e.nombres, m.nombre";
      $result_secciones_estudiantes_calificaciones = getData($conexion, $secciones_estudiantes_calificaciones_query, $periodo_id);
    }
  }
} catch (Exception $e) {
  echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Consultar Datos de Periodos Anteriores</title>
  <link rel="stylesheet" href="../Assets/sweetalert2/borderless.min.css">
  <link rel="stylesheet" href="../Assets/simple-datatables/simple-datatables.css" />
  <script src="../Assets/simple-datatables/simple-datatables.min.js"></script>
  <script src="../Assets/sweetalert2/sweetalert2.min.js"></script>
</head>

<body>
  <div class="row mx-0 justify-content-center pb-5">
    <form class="card col-md-5 py-4" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
      <h2 class="card-title h3 text-center">Consultar Datos de Periodos Anteriores</h2>
      <div class="col-md-12 form-floating mb-3">
        <select name="periodo_id" class="form-select" id="periodo" required>
          <option selected disabled>Seleccione un periodo</option>
          <?php while ($row = $result_periodos->fetch_assoc()) : ?>
            <option value="<?= $row['id'] ?>"><?= $row['anio_inicio'] ?></option>
          <?php endwhile; ?>
        </select>
        <label for="periodo">Periodo:</label>
      </div>
      <div class="btn-group btn-group-lg mx-3">
        <button class="btn btn-success w-75" type="submit">Consultar</button>
      </div>
    </form>
  </div>
  <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && $periodo_id) : ?>
    <div class="container">
      <div class="card mb-4">
        <h3>Maestros y Materias</h3>
        <table id="table-maestros-materias" class="table table-bordered">
          <thead>
            <tr>
              <th>Profesor</th>
              <th>Materia</th>
              <th>Nivel de Estudio</th>
              <th>Sección</th>
              <th>Periodo</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = $result_maestros_materias->fetch_assoc()) : ?>
              <tr>
                <td><?= htmlspecialchars($row['profesor_nombre'] . ' ' . $row['profesor_apellido']) ?></td>
                <td><?= htmlspecialchars($row['materia_nombre']) ?></td>
                <td><?= htmlspecialchars($row['nivel_estudio']) ?></td>
                <td><?= htmlspecialchars($row['seccion']) ?></td>
                <td><?= htmlspecialchars($row['periodo']) ?></td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
      <div class="card mb-4">
        <h3>Representantes y Estudiantes</h3>
        <table id="table-representantes-estudiantes" class="table table-bordered">
          <thead>
            <tr>
              <th>Representante</th>
              <th>Estudiante</th>
              <th>Nivel de Estudio</th>
              <th>Sección</th>
              <th>Periodo</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = $result_representantes_estudiantes->fetch_assoc()) : ?>
              <tr>
                <td><?= htmlspecialchars($row['representante_nombre'] . ' ' . $row['representante_apellido']) ?></td>
                <td><?= htmlspecialchars($row['estudiante_nombres'] . ' ' . $row['estudiante_apellidos']) ?></td>
                <td><?= htmlspecialchars($row['nivel_estudio']) ?></td>
                <td><?= htmlspecialchars($row['seccion']) ?></td>
                <td><?= htmlspecialchars($row['periodo']) ?></td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
      <div class="card mb-4">
        <h3>Secciones, Estudiantes y Calificaciones Definitivas</h3>
        <?php if ($result_secciones_estudiantes_calificaciones->num_rows > 0): ?>
          <table id="table-secciones-estudiantes-calificaciones" class="table table-bordered">
            <thead>
              <tr>
                <th>Sección</th>
                <th>Estudiante</th>
                <th>Materia</th>
                <th>Calificación Definitiva</th>
                <th>Nivel de Estudio</th>
                <th>Periodo</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($row = $result_secciones_estudiantes_calificaciones->fetch_assoc()) : ?>
                <tr>
                  <td><?= htmlspecialchars($row['seccion_nombre']) ?></td>
                  <td><?= htmlspecialchars($row['estudiante_nombres'] . ' ' . $row['estudiante_apellidos']) ?></td>
                  <td><?= htmlspecialchars($row['materia_nombre']) ?></td>
                  <td><?= htmlspecialchars($row['calificacion_definitiva']) ?></td>
                  <td><?= htmlspecialchars($row['nivel_estudio']) ?></td>
                  <td><?= htmlspecialchars($row['periodo']) ?></td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        <?php else: ?>
          <p class="alert alert-info">No hay calificaciones cargadas para este periodo. Se mostrarán los estudiantes y materias sin calificaciones.</p>
        <?php endif; ?>
      </div>
    </div>
    <script>
      // Inicializa SimpleDataTables para cada tabla
      new simpleDatatables.DataTable("#table-maestros-materias");
      new simpleDatatables.DataTable("#table-representantes-estudiantes");
      new simpleDatatables.DataTable("#table-secciones-estudiantes-calificaciones");
    </script>
  <?php endif; ?>
</body>

</html>

<?php include __DIR__ . '/partials/footer.php' ?>
