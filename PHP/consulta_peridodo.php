<?php
// Incluye las cabeceras y la conexión
include __DIR__ . '/partials/header.php';
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/conexion_be.php';

// Función para ejecutar consultas preparadas
function getData($conexion, $query, $params = []) {
  $stmt = $conexion->prepare($query);
  if (!empty($params)) {
    $stmt->bind_param(str_repeat('i', count($params)), ...$params);
  }
  $stmt->execute();
  $result = $stmt->get_result();
  $stmt->close();
  return $result;
}

try {
  // Obtén todos los periodos disponibles
  $query_periodos = "SELECT id, anio_inicio FROM periodos ORDER BY anio_inicio DESC";
  $result_periodos = getData($conexion, $query_periodos);

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $periodo_id = $_POST['periodo_id'] ?? null;
    $tipo_consulta = $_POST['tipo_consulta'] ?? null;
    $id_seccion = $_POST['id_seccion'] ?? null;

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
      $result_maestros_materias = getData($conexion, $maestros_materias_query, [$periodo_id]);

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
      $result_representantes_estudiantes = getData($conexion, $representantes_estudiantes_query, [$periodo_id]);

      // Consulta para obtener las secciones disponibles
      $secciones_query = "SELECT s.id, s.nombre AS seccion, n.nombre AS nivel
                                FROM secciones s
                                JOIN niveles_estudio n ON s.id_nivel_estudio = n.id
                                JOIN inscripciones i ON i.id_seccion = s.id
                                WHERE i.id_periodo = ?
                                GROUP BY s.id
                                ORDER BY n.nombre, s.nombre";
      $result_secciones = getData($conexion, $secciones_query, [$periodo_id]);

      // Si se seleccionó un tipo de consulta y una sección
      if ($tipo_consulta && $id_seccion) {
        if ($tipo_consulta == 'definitiva') {
          $notas_query = "SELECT e.id AS estudiante_id, CONCAT(e.nombres, ' ', e.apellidos) AS estudiante,
                                       ma.nombre AS materia, AVG(c.calificacion) AS calificacion_definitiva
                                    FROM calificaciones c
                                    JOIN boletines b ON c.id_boletin = b.id
                                    JOIN estudiantes e ON b.id_estudiante = e.id
                                    JOIN materias ma ON c.id_materia = ma.id
                                    JOIN inscripciones i ON i.id_estudiante = e.id
                                    WHERE i.id_seccion = ? AND b.id_periodo = ?
                                    GROUP BY e.id, ma.id";
          $result_notas = getData($conexion, $notas_query, [$id_seccion, $periodo_id]);

          // Organizar los datos por estudiante
          $calificacionesPorEstudiante = [];
          while ($row = $result_notas->fetch_assoc()) {
            $calificacionesPorEstudiante[$row['estudiante_id']]['estudiante'] = $row['estudiante'];
            $calificacionesPorEstudiante[$row['estudiante_id']]['calificaciones'][$row['materia']] = number_format($row['calificacion_definitiva'], 2);
          }
        }
      }
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
          <?php while ($row = $result_periodos->fetch_assoc()) :
            $anio_fin = $row['anio_inicio'] + 1;
            $periodo_texto = $row['anio_inicio'] . '-' . $anio_fin;
          ?>
            <option value="<?= $row['id'] ?>"><?= $periodo_texto ?></option>
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

      <div class="row mx-0 justify-content-center pb-5">
        <form class="card col-md-5 py-4" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
          <h2 class="card-title h3 text-center">Consultar Notas</h2>
          <input type="hidden" name="periodo_id" value="<?= $periodo_id ?>">
          <div class="col-md-12 form-floating mb-3">
            <select name="tipo_consulta" class="form-select" id="tipo_consulta" required>
              <option selected disabled>Seleccione tipo de consulta</option>
              <option value="definitiva">Notas Definitivas</option>
            </select>
            <label for="tipo_consulta">Tipo de Consulta:</label>
          </div>
          <div class="col-md-12 form-floating mb-3">
            <select name="id_seccion" class="form-select" id="id_seccion" required>
              <option selected disabled>Seleccione Año-Sección</option>
              <?php if (isset($result_secciones)) :
                while ($seccion = $result_secciones->fetch_assoc()) : ?>
                  <option value="<?= $seccion['id'] ?>"><?= $seccion['nivel'] . ' - ' . $seccion['seccion'] ?></option>
              <?php endwhile;
              endif; ?>
            </select>
            <label for="id_seccion">Año-Sección:</label>
          </div>
          <div class="btn-group btn-group-lg mx-3">
            <button class="btn btn-success w-75" type="submit">Consultar Notas</button>
          </div>
        </form>
      </div>

      <?php if (isset($calificacionesPorEstudiante)) : ?>
        <div class="card">
          <h3>Notas Definitivas</h3>
          <div class="table-responsive">
            <table id="table-notas-definitivas" class="table table-bordered">
              <thead>
                <tr>
                  <th>Estudiante</th>
                  <?php
                  // Obtener las materias de la primera fila de calificaciones para los encabezados
                  $materias = array();
                  foreach ($calificacionesPorEstudiante as $info) {
                    foreach ($info['calificaciones'] as $materia => $calificacion) {
                      if (!in_array($materia, $materias)) {
                        $materias[] = $materia;
                      }
                    }
                  }
                  foreach ($materias as $materia) {
                    echo "<th>" . htmlspecialchars($materia) . "</th>";
                  }
                  ?>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($calificacionesPorEstudiante as $estudiante_id => $info) : ?>
                  <tr>
                    <td><?= htmlspecialchars($info['estudiante']) ?></td>
                    <?php foreach ($materias as $materia) : ?>
                      <td><?= htmlspecialchars($info['calificaciones'][$materia] ?? '01') ?></td>
                    <?php endforeach; ?>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      <?php endif; ?>
    </div>
</body>

</html>

<script>
  // Inicializa SimpleDataTables para cada tabla
  new simpleDatatables.DataTable("#table-maestros-materias");
  new simpleDatatables.DataTable("#table-representantes-estudiantes");
  new simpleDatatables.DataTable("#table-notas-definitivas");
</script>
<?php endif; ?>
</body>

</html>

<?php include __DIR__ . '/partials/footer.php' ?>
