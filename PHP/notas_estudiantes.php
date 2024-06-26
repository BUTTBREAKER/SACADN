<?php
require __DIR__ . '/../vendor/autoload.php';
include __DIR__ . '/partials/header.php';
?>


<body>
  <div class="container card card-body table-responsive">
    <h1 class="mt-5 mb-4">Consulta de Notas por lapso y Sección</h1>

   <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="get" class="mb-4">
      <div class="row">
        <div class="col-md-4">
          <label for="id_momento" class="form-label">Selecciona el Lapso:</label>
          <select name="id_momento" id="id_momento" class="form-select" required>
            <option value="" disabled selected>Selecciona un Lapso</option>
            <?php
            // Realizar la conexión a la base de datos (requiere el archivo de conexión)
            $db = require_once __DIR__ . '/conexion_be.php';

            // Consultar los momentos disponibles
            $sql_momentos = "SELECT m.id, CONCAT('Momento ', m.numero_momento, ' - ', p.anio_inicio) AS momento
                             FROM momentos m
                             JOIN periodos p ON m.id_periodo = p.id
                             ORDER BY p.anio_inicio DESC, m.numero_momento DESC";
            $result_momentos = $db->query($sql_momentos);
            while ($momento = $result_momentos->fetch_assoc()) {
              echo '<option value="' . $momento['id'] . '">' . $momento['momento'] . '</option>';
            }
            ?>
          </select>
        </div>
        <div class="col-md-4">
          <label for="id_seccion" class="form-label">Selecciona Año-Seccion:</label>
          <select name="id_seccion" id="id_seccion" class="form-select" required>
            <option value="" disabled selected>Selecciona Año-Seccion</option>
            <?php
            // Consultar las secciones disponibles
            $sql_secciones = "SELECT s.id, s.nombre AS seccion, n.nombre AS nivel
                              FROM secciones s
                              JOIN niveles_estudio n ON s.id_nivel_estudio = n.id
                              ORDER BY n.nombre, s.nombre";
            $result_secciones = $db->query($sql_secciones);
            while ($seccion = $result_secciones->fetch_assoc()) {
              echo '<option value="' . $seccion['id'] . '">' . $seccion['nivel'] . ' - ' . $seccion['seccion'] . '</option>';
            }
            ?>
          </select>
        </div>
        <div class="col-md-4 d-grid">
          <button type="submit" class="btn btn-primary mt-md-4">Consultar Notas</button>
        </div>
      </div>
    </form>

   <?php
    // Verificar si se ha enviado el formulario
    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id_momento']) && isset($_GET['id_seccion'])) {
      // Obtener los valores de los parámetros del formulario
      $idMomento = $_GET['id_momento'];
      $idSeccion = $_GET['id_seccion'];

      // Consultar las notas en base al momento y la sección seleccionados
      $sql = "SELECT e.id AS estudiante_id, e.nombre AS estudiante, ma.nombre AS materia, c.calificacion
              FROM calificaciones c
              JOIN boletines b ON c.id_boletin = b.id
              JOIN estudiantes e ON b.id_estudiante = e.id
              JOIN momentos m ON b.id_momento = m.id
              JOIN materias ma ON c.id_materia = ma.id
              JOIN asignaciones_estudiantes ae ON ae.id_estudiante = e.id
              WHERE m.id = ? AND ae.id_seccion = ? AND ae.id_nivel_estudio = (
                  SELECT id_nivel_estudio FROM secciones WHERE id = ?
              )";
      $stmt = $db->prepare($sql);
      $stmt->bind_param('iii', $idMomento, $idSeccion, $idSeccion);
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
      }
       else {
        echo "<p class='mt-4'>No se encontraron notas para el momento y la sección seleccionados.</p>";
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

<?php include __DIR__ . '/partials/footer.php' ?>
