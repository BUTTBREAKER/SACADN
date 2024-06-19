<?php
require __DIR__ . '/../vendor/autoload.php';
include __DIR__ . '/partials/header.php';
?>
<body>
  <div class="container card card-body table-responsive">
    <h1 class="mt-5 mb-4">Consulta  Año y Sección</h1>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="get" class="mb-4">
      <div class="row">
    
        <div class="col-md-4">
          <label for="id_seccion" class="form-label">Selecciona Año-Seccion:</label>
          <select name="id_seccion" id="id_seccion" class="form-select" required>
            <option value="" disabled selected>Selecciona Año-Seccion</option>
            <?php
            $db = require_once __DIR__ . '/conexion_be.php';

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
          <button type="submit" class="btn btn-primary mt-md-4" href="estudiantes_seccion.php">Ver Año Y Seccion</button>
        </div>
      </div>
    </form>

<?php

    // Verificar si se ha enviado el formulario
    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id_seccion'])) {
      // Obtener los valores de los parámetros del formulario
      
      $idSeccion = $_GET['id_seccion'];

// Consultar el nivel de estudio y la sección actual del estudiante
$sql = "SELECT e.id AS id_estudiante, e.cedula As cedulaEstudiante, e.nombre AS nombreEstudiante, e.apellido As apellidoEstudiante,
                  n.id AS id_nivel_estudio,  n.nombre AS nivel_estudio, s.id AS id_Seccion, s.nombre AS seccion
                   FROM asignaciones_estudiantes ae
                   JOIN estudiantes e ON ae.id_estudiante = e.id
                   JOIN niveles_estudio n ON ae.id_nivel_estudio = n.id
                   JOIN secciones s ON ae.id_seccion = s.id
                    WHERE ae.id_seccion = ? AND ae.id_nivel_estudio = (
                        SELECT id_nivel_estudio FROM secciones WHERE id = ?
                    )";
        $stmt = $db->prepare($sql);
        $stmt->bind_param('ii',  $idSeccion,  $idSeccion);
        $stmt->execute();
        $result = $stmt->get_result();

      // Organizar los datos por estudiante
      $asignacionPorEstudiante = [];
      while ($row = $result->fetch_assoc()) {
        $asignacionPorEstudiante[$row['id_estudiante']] ['cedulaEstudiante'] = $row['cedulaEstudiante'];
        $asignacionPorEstudiante[$row['id_estudiante']] ['nivel_estudio'][] = [
          'seccion' => $row['seccion'],
          'nivel_estudio' => $row['nivel_estudio']
        ];
      }
  // Mostrar las secciones en una tabla si hay resultados
      if (!empty($asignacionesPorEstudiante)) {
        echo '<div class="container card card-body table-responsive">
                        <table id="asignacion-table" class="table table-striped datatable">
                            <thead>
                                <tr>
                                    <th>Estudiante</th>';
  // Mostrar las cabeceras de las materias
        $seccion = array_unique(array_column(array_merge(...array_values(array_column($asignacionPorEstudiante, 'nivel_estudio'))), 'seccion'));
        {
          echo '<th>' . htmlspecialchars($seccion) . '</th>';
        }
}  

  }          
?>

   <body>
    <div class="container card card-body table-responsive">
        <h3 value="<?=  $asignacion['nivel_estudio'] ?>"></h3>
         <h3 value="<?= $asignacion['seccion'] ?>"></h3>
            <div class="table-responsive">
                  <table id="tablaEstudiantesPorSeciones" class="table table-striped datatable">
                        <thead>
                            <tr>
                                <th>Cedula</th>
                                <th>Nombres</th>
                                <th>Apellidos</th>
                                <th>Nivel de estudio</th>
                                <th>Seccion</th>
                            </tr>
                        </thead>
                        <tbody> <?php while ( $row = $result->fetch_assoc()){?>
                                <tr>
                                    <td><?= htmlspecialchars($row['cedulaEstudiante']) ?></td>
                                    <td><?= htmlspecialchars($row['nombreEstudiante']) ?></td>
                                    <td><?= htmlspecialchars($row['apellidoEstudiante']) ?></td>
                                    <td><?= htmlspecialchars($row['nivel_estudio']) ?></td>
                                    <td><?= htmlspecialchars($row['seccion']) ?></td>

                                </tr>
                            <?php }?>
                        </tbody>
                    </table>
            </div>
    </div>
</body>

    <?php include __DIR__ . '/partials/footer.php' ?>
