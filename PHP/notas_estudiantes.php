<?php
require __DIR__ . '/../vendor/autoload.php';
include __DIR__ . '/partials/header.php';

// Conexión a la base de datos
$db = require_once __DIR__ . '/conexion_be.php';

// Obtener el periodo activo
$stmt_periodo_activo = $db->prepare("SELECT id FROM periodos WHERE estado = 'activo' LIMIT 1");
$stmt_periodo_activo->execute();
$periodo_activo = $stmt_periodo_activo->get_result()->fetch_assoc();
$stmt_periodo_activo->close();

$periodo_id = $periodo_activo['id'];
?>

<body>
  <div class="container card card-body table-responsive">
    <h1 class="mt-5 mb-4">Consulta de Notas por Lapso y Sección</h1>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="get" class="mb-4">
      <div class="row">
        <div class="col-md-4">
          <label for="id_momento" class="form-label">Selecciona el Lapso:</label>
          <select name="id_momento" id="id_momento" class="form-select" required>
            <option value="" disabled selected>Selecciona un Lapso</option>
            <?php
            // Consultar los momentos disponibles solo para el periodo activo
            $sql_momentos = "SELECT m.id, CONCAT('Lapso ', m.numero_momento) AS momento
                             FROM momentos m
                             JOIN boletines b ON m.id = b.id_momento
                             WHERE b.id_periodo = ?
                             GROUP BY m.id
                             ORDER BY m.numero_momento DESC";
            $stmt_momentos = $db->prepare($sql_momentos);
            $stmt_momentos->bind_param('i', $periodo_id);
            $stmt_momentos->execute();
            $result_momentos = $stmt_momentos->get_result();
            while ($momento = $result_momentos->fetch_assoc()) {
              echo '<option value="' . $momento['id'] . '">' . $momento['momento'] . '</option>';
            }
            $stmt_momentos->close();
            ?>
          </select>
        </div>
        <div class="col-md-4">
          <label for="id_seccion" class="form-label">Selecciona Año-Sección:</label>
          <select name="id_seccion" id="id_seccion" class="form-select" required>
            <option value="" disabled selected>Selecciona Año-Sección</option>
            <?php
            // Consultar las secciones disponibles solo para el periodo activo
            $sql_secciones = "SELECT s.id, s.nombre AS seccion, n.nombre AS nivel
                              FROM secciones s
                              JOIN niveles_estudio n ON s.id_nivel_estudio = n.id
                              JOIN inscripciones i ON i.id_seccion = s.id
                              WHERE i.id_periodo = ?
                              GROUP BY s.id
                              ORDER BY n.nombre, s.nombre";
            $stmt_secciones = $db->prepare($sql_secciones);
            $stmt_secciones->bind_param('i', $periodo_id);
            $stmt_secciones->execute();
            $result_secciones = $stmt_secciones->get_result();
            while ($seccion = $result_secciones->fetch_assoc()) {
              echo '<option value="' . $seccion['id'] . '">' . $seccion['nivel'] . ' - ' . $seccion['seccion'] . '</option>';
            }
            $stmt_secciones->close();
            ?>
          </select>
        </div>
        <div class="col-md-4 d-grid">
          <button type="submit" class="btn btn-success mt-md-4">Consultar Notas</button>
        </div>
      </div>
    </form>

    <?php
    // Verificar si se ha enviado el formulario
    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id_momento']) && isset($_GET['id_seccion'])) {
      // Obtener los valores del formulario
      $idMomento = $_GET['id_momento'];
      $idSeccion = $_GET['id_seccion'];

      // Consulta para obtener las notas basadas en el momento, la sección seleccionados y el periodo activo
      $sql = "SELECT e.id AS estudiante_id, CONCAT(e.nombres, ' ', e.apellidos) AS estudiante, ma.nombre AS materia, c.calificacion
              FROM calificaciones c
              JOIN boletines b ON c.id_boletin = b.id
              JOIN estudiantes e ON b.id_estudiante = e.id
              JOIN momentos m ON b.id_momento = m.id
              JOIN materias ma ON c.id_materia = ma.id
              JOIN inscripciones i ON i.id_estudiante = e.id
              WHERE m.id = ? AND i.id_seccion = ? AND b.id_periodo = ?";

      $stmt = $db->prepare($sql);
      $stmt->bind_param('iii', $idMomento, $idSeccion, $periodo_id);
      $stmt->execute();
      $result = $stmt->get_result();

      // Organizar los datos por estudiante
      $calificacionesPorEstudiante = [];
      while ($row = $result->fetch_assoc()) {
        $calificacionesPorEstudiante[$row['estudiante_id']]['estudiante'] = $row['estudiante'];
        $calificacionesPorEstudiante[$row['estudiante_id']]['calificaciones'][] = [
          'materia' => $row['materia'],
          'calificacion' => $row['calificacion']
        ];
      }

      // Mostrar las notas en una tabla si hay resultados
      if (!empty($calificacionesPorEstudiante)) {
        echo '<div class="container card card-body table-responsive">
                <table id="notas-table" class="table table-striped datatable">
                    <thead>
                        <tr>
                            <th>Estudiante</th>';
        // Mostrar las cabeceras de las materias
        $materias = array_unique(array_column(array_merge(...array_values(array_column($calificacionesPorEstudiante, 'calificaciones'))), 'materia'));
        foreach ($materias as $materia) {
          echo '<th>' . htmlspecialchars($materia) . '</th>';
        }
        echo '          <th>Detalles</th>
                        </tr>
                    </thead>
                    <tbody>';
        // Mostrar las calificaciones por estudiante
        foreach ($calificacionesPorEstudiante as $estudiante_id => $data) {
          echo '<tr>
                    <td>' . htmlspecialchars($data['estudiante']) . '</td>';
          foreach ($materias as $materia) {
            $calificacion = '';
            foreach ($data['calificaciones'] as $calif) {
              if ($calif['materia'] === $materia) {
                $calificacion = htmlspecialchars($calif['calificacion']);
                break;
              }
            }
            echo '<td>' . $calificacion . '</td>';
          }
          echo '      <td><a href="detalles_estudiante.php?id=' . htmlspecialchars($estudiante_id) . '" class="btn btn-info">Ver Detalles</a></td>
                    </tr>';
        }
        echo '      </tbody>
                </table>
            </div>';
      } else {
        echo "<p class='mt-4'>No se encontraron notas para el lapso y la sección seleccionados.</p>";
      }
    }
    ?>

  </div>

  <script src="../Assets/simple-datatables/simple-datatables.min.js"></script>

  <!-- Inicializar Simple-DataTables -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const tablaNotas = new simpleDatatables.DataTable("#notas-table");
    });
  </script>
</body>

<?php include __DIR__ . '/partials/footer.php'; ?>
