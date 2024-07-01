<?php
require __DIR__ . '/../vendor/autoload.php';
include __DIR__ . '/partials/header.php';
?>


<body>
  <div class="container card card-body table-responsive">
    <h1 class="mt-5 mb-4">Consulta de Notas por Momentos y Sección</h1>

   <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="get" class="mb-4">
      <div class="row">
        <div class="col-md-4">
          <label for="id_momento" class="form-label">Selecciona el Momento:</label>
          <select name="id_momento" id="id_momento" class="form-select" required>
            <option value="" disabled selected>Selecciona un Momento</option>
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

$sql = 
  "SELECT m.id AS id_momento, m.numero_momento AS momento, e.id AS id_estudiante, 
  e.cedula AS cedulaEstudiante, e.nombre AS nombreEstudiante,e.apellido AS apellidoEstudiante
   FROM boletines b
   JOIN momentos m ON m.id = b.id_momento
   JOIN estudiantes e ON e.id = b.id_estudiante
  ";
$result = $db->query($sql);
}
?>
<body>
    <div class="container card card-body table-responsive">
        <h3 value="<?= $nivel['nombre'] ?>"></h3>
         <h3 value="<?= $seccion['nombre'] ?>"></h3>
            <div class="table-responsive">
                  <table id="tablaEstudiantesPorSeciones" class="table table-striped datatable">
                        <thead>
                            <tr>
                                <th>Cedula</th>
                                <th>Nombres</th>
                                <th>Apellidos</th>
                                <th>Representante</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($estudiante = $result_estudiante->fetch_assoc(MYSQLI_ASSOC)) { ?>
                                <tr>
                                    <td><?= htmlspecialchars($estudiante['cedulaEstudiante']) ?></td>
                                    <td><?= htmlspecialchars($estudiante['nombreEstudiante']) ?></td>
                                    <td><?= htmlspecialchars($estudiante['apellidoEstudiante']) ?></td>
                                    <td>
                                       <a href="detalles-representante.php?id=<?= htmlspecialchars($estudiante['idRepresentante']) ?>">
                                          <?= htmlspecialchars($estudiante['nombresRepresentante'] . ' ' . $estudiante['apellidosRepresentante']) ?>
                                        </a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
            </div>
    </div>
</body>



  <script src="../Assets/simple-datatables/simple-datatables.min.js"></script>

  <!-- Inicializar Simple-DataTables -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const tablaEstudiantes= new simpleDatatables.DataTable("#tablaEstudiantes");
    });
  </script>
</body>


<?php include __DIR__ . '/partials/footer.php' 
///$sql = <<<SQL
  // SELECT m.id AS id_momento, m.numero_momento AS momento, e.id As id_estudiante, e.cedula AS cedulaEstudiante,e.nombre AS nombreEstudiante,
//e.apellido As apellidoEstudiante, s.id AS id_seccion, s.nombre AS nombreSeccion, s.id_nivel_estudio AS nombreNivel FROM inscripciones i
//JOIN momentos m ON m.id = i.id_momento
//JOIN estudiantes e ON e.id = i.id_estudiante
//JOIN secciones s ON s.id = i.id_seccion
//SQL;

//$result = $db->query($sql);///
?>

