<?php
require __DIR__ . '/../vendor/autoload.php';
include __DIR__ . '/partials/header.php';
// Obtener el ID del nivel de estudio y seccion de la URL
$id_nivel_estudio = $_GET['id'] ?? null;

// Realizar la conexión a la base de datos (requiere el archivo de conexión)
$db = require_once __DIR__ . '/conexion_be.php';
// Consultar los detalles del estudiante

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Estudiante</title>
    <!-- Agrega los estilos de Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h1 class="mt-5 mb-4"></h1>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Cedula</th>
                                <th>Nombres</th>
                                <th>Apellidos</th>
                                <th>Nivel de estudio</th>
                                <th>Seccion</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($asignaciones_estudiantes = $result_asignaciones_estudiantes->fetch_assoc()) { ?>
                                <tr>
                                    <td><?= htmlspecialchars($asignaciones_estudiantes['cedulaEstudiante']) ?></td>
                                    <td><?= htmlspecialchars($asignaciones_estudiantes['nombreEstudiante']) ?></td>
                                    <td><?= htmlspecialchars($asignaciones_estudiantes['apellidoEstudiante']) ?></td>
                                    <td><?= htmlspecialchars($asignaciones_estudiantes['nivel_estudio']) ?></td>
                                    <td><?= htmlspecialchars($asignaciones_estudiantes['seccion']) ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
            </div>
        </div>
    </div>
</body>

<?php include __DIR__ . '/partials/footer.php' ?>
$sql_estudiantes = 
  "SELECT e.id, e.cedula, e.nombre, e.apellido, r.id AS idRepresentante, r.nombre AS nombresRepresentante,
  r.apellido AS apellidosRepresentante
  FROM estudiantes e
  JOIN representantes r ON r.id = e.id_representante";
          
$stmt_estudiantes = $db->prepare($sql_estudiantes);
$stmt_estudiantes->bind_param('i', $estudiante_id);
$stmt_estudiantes->execute();
$result_estudiantes = $stmt_estudiantes->get_result();


$sql_nivel = "SELECT id, nombre FROM niveles_estudio ";
$result_nivel = $db->query($sql_nivel);
            while ($nivel = $result_nivel->fetch_assoc());

$sql_secciones = "SELECT id, nombre FROM secciones";
            $result_secciones = $db->query($sql_secciones);
            while ($seccion = $result_secciones->fetch_assoc());
$stmt = $db->prepare($sql_secciones);
      $stmt->bind_param('i', $idSeccion);
      $stmt->execute();
      $result = $stmt->get_result();

//////
 <div class="col-md-4">
          <label for="id_periodo" class="form-label">Selecciona el periodo:</label>
          <select name="id_periodo" id="id_periodo" class="form-select" required>
            <option value="" disabled selected>Selecciona un periodo</option>
            <?php
            // Realizar la conexión a la base de datos (requiere el archivo de conexión)
            $db = require_once __DIR__ . '/conexion_be.php';

            // Consultar los periodos disponibles
            $sql_periodos = "SELECT id, anio_inicio FROM periodos ORDER BY anio_inicio DESC";
            $result_periodos = $db->query($sql_periodos);
            while ($periodo = $result_periodos->fetch_assoc()) {
              echo '<option value="' . $periodo['id'] . '">' . $periodo['anio_inicio'] . '</option>';
            }
            ?>
          </select>
        </div>
     && isset($_GET['id_periodo'])
     $idPeriodo = $_GET['id_periodo'];
