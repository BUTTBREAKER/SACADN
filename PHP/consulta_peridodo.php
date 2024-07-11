<?php
// Incluir archivos necesarios
include __DIR__ . '/partials/header.php';
require __DIR__ . "/Middlewares/autorizacion.php";
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/conexion_be.php';

// Obtener los periodos de la base de datos
$stmt_periodos = $conexion->prepare("SELECT id, anio_inicio FROM periodos ORDER BY anio_inicio DESC");
$stmt_periodos->execute();
$result_periodos = $stmt_periodos->get_result();
$stmt_periodos->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $periodo_id = $_POST['periodo_id'] ?? null;

  if ($periodo_id) {
    // Consultar maestros y materias
    $stmt_maestros_materias = $conexion->prepare("
            SELECT
                p.nombre AS profesor_nombre,
                p.apellido AS profesor_apellido,
                m.nombre AS materia_nombre,
                n.nombre AS nivel_estudio,
                s.nombre AS seccion,
                per.anio_inicio AS periodo
            FROM
                asignaciones a
            JOIN
                profesores p ON a.id_profesor = p.id
            JOIN
                materias m ON a.id_materia = m.id
            JOIN
                niveles_estudio n ON a.id_nivel_estudio = n.id
            JOIN
                secciones s ON a.id_seccion = s.id
            JOIN
                periodos per ON a.id_periodo = per.id
            WHERE
                per.id = ?
        ");
    $stmt_maestros_materias->bind_param("i", $periodo_id);
    $stmt_maestros_materias->execute();
    $result_maestros_materias = $stmt_maestros_materias->get_result();

    // Consultar representantes y estudiantes con nivel de estudio y sección
    $stmt_representantes_estudiantes = $conexion->prepare("
            SELECT
                r.nombre AS representante_nombre,
                r.apellido AS representante_apellido,
                e.nombres AS estudiante_nombres,
                e.apellidos AS estudiante_apellidos,
                n.nombre AS nivel_estudio,
                s.nombre AS seccion,
                per.anio_inicio AS periodo
            FROM
                representantes r
            JOIN
                estudiantes e ON r.id = e.id_representante
            JOIN
                inscripciones i ON e.id = i.id_estudiante
            JOIN
                secciones s ON i.id_seccion = s.id
            JOIN
                periodos per ON s.id_periodo = per.id
            JOIN
                niveles_estudio n ON s.id_nivel_estudio = n.id
            WHERE
                per.id = ?
        ");
    $stmt_representantes_estudiantes->bind_param("i", $periodo_id);
    $stmt_representantes_estudiantes->execute();
    $result_representantes_estudiantes = $stmt_representantes_estudiantes->get_result();

    // Consultar secciones, estudiantes y calificaciones definitivas
    $stmt_secciones_estudiantes_calificaciones = $conexion->prepare("
            SELECT
                s.nombre AS seccion_nombre,
                e.nombres AS estudiante_nombres,
                e.apellidos AS estudiante_apellidos,
                m.nombre AS materia_nombre,
                AVG(c.calificacion) AS calificacion_definitiva,
                per.anio_inicio AS periodo
            FROM
                secciones s
            JOIN
                inscripciones i ON s.id = i.id_seccion
            JOIN
                estudiantes e ON i.id_estudiante = e.id
            JOIN
                boletines b ON e.id = b.id_estudiante
            JOIN
                calificaciones c ON b.id = c.id_boletin
            JOIN
                materias m ON c.id_materia = m.id
            JOIN
                periodos per ON s.id_periodo = per.id
            WHERE
                per.id = ?
            GROUP BY
                s.nombre, e.nombres, e.apellidos, m.nombre, per.anio_inicio
        ");
    $stmt_secciones_estudiantes_calificaciones->bind_param("i", $periodo_id);
    $stmt_secciones_estudiantes_calificaciones->execute();
    $result_secciones_estudiantes_calificaciones = $stmt_secciones_estudiantes_calificaciones->get_result();
  }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Consultar Datos de Periodos Anteriores</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css">
  <link rel="stylesheet" href="../Assets/sweetalert2/borderless.min.css">
  <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest"></script>
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
                <td><?= $row['profesor_nombre'] . ' ' . $row['profesor_apellido'] ?></td>
                <td><?= $row['materia_nombre'] ?></td>
                <td><?= $row['nivel_estudio'] ?></td>
                <td><?= $row['seccion'] ?></td>
                <td><?= $row['periodo'] ?></td>
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
                <td><?= $row['representante_nombre'] . ' ' . $row['representante_apellido'] ?></td>
                <td><?= $row['estudiante_nombres'] . ' ' . $row['estudiante_apellidos'] ?></td>
                <td><?= $row['nivel_estudio'] ?></td>
                <td><?= $row['seccion'] ?></td>
                <td><?= $row['periodo'] ?></td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
      <div class="card mb-4">
        <h3>Secciones, Estudiantes y Calificaciones Definitivas</h3>
        <table id="table-secciones-estudiantes-calificaciones" class="table table-bordered">
          <thead>
            <tr>
              <th>Sección</th>
              <th>Estudiante</th>
              <th>Materia</th>
              <th>Calificación Definitiva</th>
              <th>Periodo</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = $result_secciones_estudiantes_calificaciones->fetch_assoc()) : ?>
              <tr>
                <td><?= $row['seccion_nombre'] ?></td>
                <td><?= $row['estudiante_nombres'] . ' ' . $row['estudiante_apellidos'] ?></td>
                <td><?= $row['materia_nombre'] ?></td>
                <td><?= $row['calificacion_definitiva'] ?></td>
                <td><?= $row['periodo'] ?></td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>
    <script>
      // Initialize SimpleDataTables for each table
      new simpleDatatables.DataTable("#table-maestros-materias");
      new simpleDatatables.DataTable("#table-representantes-estudiantes");
      new simpleDatatables.DataTable("#table-secciones-estudiantes-calificaciones");
    </script>
  <?php endif; ?>
</body>

</html>

<?php include __DIR__ . '/partials/footer.php' ?>
