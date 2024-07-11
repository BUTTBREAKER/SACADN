<?php
// Incluir cabecera
include __DIR__ . '/partials/header.php';

// Verificar autorización del usuario (profesor)
require __DIR__ . "/Middlewares/autorizacion.php";
require_once __DIR__ . '/../vendor/autoload.php';

/** @var mysqli */
require_once __DIR__ . '/conexion_be.php';

if ($conexion === false) {
  die("Error al conectar con la base de datos.");
}

$id_usuario = $_SESSION['usuario_id'];

// Obtener el periodo activo
$stmt_periodo_activo = $conexion->prepare("SELECT id, anio_inicio FROM periodos WHERE estado = 'activo' LIMIT 1");
$stmt_periodo_activo->execute();
$result_periodo_activo = $stmt_periodo_activo->get_result();
$periodo_activo = $result_periodo_activo->fetch_assoc();
$stmt_periodo_activo->close();

// Variables para mantener los valores seleccionados
$id_momento = $_POST['id_momento'] ?? null;
$id_nivel_estudio = $_POST['id_nivel_estudio'] ?? null;
$id_seccion = $_POST['id_seccion'] ?? null;
$id_estudiante = $_POST['id_estudiante'] ?? null;

// Consultar todos los momentos, niveles de estudio y secciones
$momentos = $conexion->query("SELECT id, CONCAT('Lapso ', numero_momento) AS momento FROM momentos")->fetch_all(MYSQLI_ASSOC);
$niveles_estudio = $conexion->query("SELECT id, nombre FROM niveles_estudio")->fetch_all(MYSQLI_ASSOC);
$secciones = $conexion->query("SELECT id, nombre FROM secciones")->fetch_all(MYSQLI_ASSOC);

// Obtener el período activo
$stmt_periodo_activo = $conexion->prepare("
    SELECT id, anio_inicio
    FROM periodos
    WHERE estado = 'activo'
    LIMIT 1
");
$stmt_periodo_activo->execute();
$stmt_periodo_activo->bind_result($id_periodo, $anio_inicio);
$stmt_periodo_activo->fetch();
$stmt_periodo_activo->close();

// Obtener los estudiantes según el nivel de estudio y la sección seleccionados
$estudiantes = [];
if ($id_nivel_estudio && $id_seccion) {
  $stmt_estudiantes = $conexion->prepare("
        SELECT e.id, CONCAT(e.nombres, ' ', e.apellidos) as nombre
        FROM estudiantes e
        JOIN inscripciones i ON e.id = i.id_estudiante
        JOIN secciones s ON i.id_seccion = s.id
        WHERE s.id_nivel_estudio = ? AND s.id = ?
    ");
  $stmt_estudiantes->bind_param("ii", $id_nivel_estudio, $id_seccion);
  $stmt_estudiantes->execute();
  $result_estudiantes = $stmt_estudiantes->get_result();
  $estudiantes = $result_estudiantes->fetch_all(MYSQLI_ASSOC);
  $stmt_estudiantes->close();
}

// Obtener las asignaciones del estudiante si se ha seleccionado un estudiante
$asignaciones = [];
if ($id_estudiante) {
  $stmt_asignaciones = $conexion->prepare("
        SELECT a.id, m.nombre as nombre_materia, p.nombre as nombre_profesor, c.calificacion
        FROM asignaciones a
        JOIN materias m ON a.id_materia = m.id
        JOIN profesores p ON a.id_profesor = p.id
        LEFT JOIN boletines b ON a.id_periodo = b.id_periodo AND b.id_momento = ? AND b.id_estudiante = ?
        LEFT JOIN calificaciones c ON b.id = c.id_boletin AND c.id_materia = a.id_materia
        WHERE a.id_nivel_estudio = ? AND a.id_seccion = ?
    ");
  $stmt_asignaciones->bind_param("iiii", $id_momento, $id_estudiante, $id_nivel_estudio, $id_seccion);
  $stmt_asignaciones->execute();
  $result_asignaciones = $stmt_asignaciones->get_result();
  $asignaciones = $result_asignaciones->fetch_all(MYSQLI_ASSOC);
  $stmt_asignaciones->close();
}

// Procesar formulario de actualización de calificaciones si se envía
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['id_asignacion'], $_POST['id_estudiante'], $_POST['calificacion'])) {
  $id_asignacion = $_POST['id_asignacion'];
  $id_estudiante = $_POST['id_estudiante'];
  $calificacion = $_POST['calificacion'];

  // Verificar si el boletín ya existe para el estudiante y el momento seleccionados
  $stmt_verificar_boletin = $conexion->prepare("
        SELECT id FROM boletines
        WHERE id_momento = ? AND id_estudiante = ? AND id_periodo = ?
    ");
  $stmt_verificar_boletin->bind_param("iii", $id_momento, $id_estudiante, $id_periodo);
  $stmt_verificar_boletin->execute();
  $stmt_verificar_boletin->store_result();

  // Si no existe el boletín, crearlo
  if ($stmt_verificar_boletin->num_rows == 0) {
    $stmt_insertar_boletin = $conexion->prepare("
            INSERT INTO boletines (id_momento, id_estudiante, id_periodo)
            VALUES (?, ?, ?)
        ");
    $stmt_insertar_boletin->bind_param("iii", $id_momento, $id_estudiante, $id_periodo);
    $stmt_insertar_boletin->execute();

    if ($stmt_insertar_boletin === false) {
      die('Error al insertar boletín: ' . $conexion->error);
    }

    $stmt_insertar_boletin->close();
  }

  $stmt_verificar_boletin->close();

  // Validar y guardar la calificación en la base de datos
  $stmt_guardar_calificacion = $conexion->prepare("
        INSERT INTO calificaciones (id_materia, id_boletin, id_usuario, calificacion, id_periodo)
        SELECT a.id_materia, b.id, ?, ?, a.id_periodo
        FROM asignaciones a
        JOIN boletines b ON a.id_periodo = b.id_periodo AND b.id_momento = ?
        WHERE a.id = ?
        ON DUPLICATE KEY UPDATE calificacion = VALUES(calificacion)
    ");

  // Verificar si hay error en la preparación de la consulta
  if ($stmt_guardar_calificacion === false) {
    die('Error en la preparación de la consulta: ' . $conexion->error);
  }

  // Bind parameters
  $stmt_guardar_calificacion->bind_param("iiii", $id_usuario, $calificacion, $id_momento, $id_asignacion,);
  $stmt_guardar_calificacion->execute();

  // Verificar si hay error en la ejecución de la consulta
  if ($stmt_guardar_calificacion === false) {
    die('Error al ejecutar la consulta: ' . $stmt_guardar_calificacion->error);
  }

  $stmt_guardar_calificacion->close();

  // Redirigir o mostrar mensaje de éxito
  $_SESSION['mensaje'] = "Calificación guardada correctamente.";
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cargar Notas</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-default@4/default.min.css" />
</head>

<body>

  <div class="row mx-0 justify-content-center pb-5">
    <form class="card col-md-5 py-4" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
      <h2 class="card-title h3 text-center">Cargar Notas</h2>

      <?php if (isset($_SESSION['mensaje'])) : ?>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
          Swal.fire({
            title: 'Resultado',
            text: '<?= $_SESSION['mensaje'] ?>',
            icon: '<?= strpos($_SESSION['mensaje'], "Error") === false ? "success" : "error" ?>',
            confirmButtonText: 'OK'
          });
        </script>
        <?php unset($_SESSION['mensaje']); ?>
      <?php endif; ?>
      <div class="col-md-12 form-floating mb-3">
        <input type="hidden" name="periodo_id" value="<?= @$periodo_activo['id'] ?>">
        <input class="form-control" type="text" value="<?= @$periodo_activo['anio_inicio'] ?>" readonly>
        <label for="periodo_id">Período Activo:</label>
      </div>
      <div class="col-md-12 form-floating mb-3">
        <select name="id_momento" class="form-select" id="id_momento">
          <option value="">Seleccione el lapso</option>
          <?php foreach ($momentos as $momento) : ?>
            <option value="<?php echo $momento['id']; ?>" <?php echo ($momento['id'] == $id_momento) ? 'selected' : ''; ?>><?php echo $momento['momento']; ?></option>
          <?php endforeach; ?>
        </select>
        <label for="id_momento">Lapso</label>
      </div>
      <div class="col-md-12 form-floating mb-3">
        <select name="id_nivel_estudio" class="form-select" id="id_nivel_estudio">
          <option value="">Seleccione el nivel de estudio</option>
          <?php foreach ($niveles_estudio as $nivel_estudio) : ?>
            <option value="<?php echo $nivel_estudio['id']; ?>" <?php echo ($nivel_estudio['id'] == $id_nivel_estudio) ? 'selected' : ''; ?>><?php echo $nivel_estudio['nombre']; ?></option>
          <?php endforeach; ?>
        </select>
        <label for="id_nivel_estudio">Nivel de Estudio</label>
      </div>
      <div class="col-md-12 form-floating mb-3">
        <select name="id_seccion" class="form-select" id="id_seccion">
          <option value="">Seleccione la sección</option>
          <?php foreach ($secciones as $seccion) : ?>
            <option value="<?php echo $seccion['id']; ?>" <?php echo ($seccion['id'] == $id_seccion) ? 'selected' : ''; ?>><?php echo $seccion['nombre']; ?></option>
          <?php endforeach; ?>
        </select>
        <label for="id_seccion">Sección</label>
      </div>

      <div class="col-md-12 form-floating mb-3">
        <select name="id_estudiante" class="form-select" id="id_estudiante">
          <option value="">Seleccione el estudiante</option>
          <?php foreach ($estudiantes as $estudiante) : ?>
            <option value="<?php echo $estudiante['id']; ?>" <?php echo ($estudiante['id'] == $id_estudiante) ? 'selected' : ''; ?>><?php echo $estudiante['nombre']; ?></option>
          <?php endforeach; ?>
        </select>
        <label for="id_estudiante">Estudiante</label>
      </div>

      <div class="col-md-12 form-floating mb-3">
        <button type="submit" class="btn btn-primary">Buscar Asignaciones</button>
      </div>
    </form>
  </div>

  <?php if (!empty($asignaciones)) : ?>
    <div class="row mx-0 justify-content-center">
      <div class="card col-md-8">
        <div class="card-body">
          <h3 class="card-title h4 text-center">Asignaciones</h3>
          <table class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>Materia</th>
                <th>Profesor</th>
                <th>Calificación</th>
                <th>Acción</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($asignaciones as $asignacion) : ?>
                <tr>
                  <td><?php echo $asignacion['nombre_materia']; ?></td>
                  <td><?php echo $asignacion['nombre_profesor']; ?></td>
                  <td>
                    <?php if ($asignacion['calificacion'] !== null) : ?>
                      <?php echo $asignacion['calificacion']; ?>
                    <?php else : ?>
                      <em>Sin calificar</em>
                    <?php endif; ?>
                  </td>
                  <td>
                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modal_<?php echo $asignacion['id']; ?>">Calificar</button>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <?php foreach ($asignaciones as $asignacion) : ?>
      <div class="modal fade" id="modal_<?php echo $asignacion['id']; ?>" tabindex="-1" aria-labelledby="modal_<?php echo $asignacion['id']; ?>_label" aria-hidden="true">
        <div class="modal-dialog">
          <form class="modal-content" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="modal-header">
              <h5 class="modal-title" id="modal_<?php echo $asignacion['id']; ?>_label">Calificar a <?php echo $estudiantes[array_search($id_estudiante, array_column($estudiantes, 'id'))]['nombre']; ?></h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <input type="hidden" name="id_asignacion" value="<?php echo $asignacion['id']; ?>">
              <input type="hidden" name="id_estudiante" value="<?php echo $id_estudiante; ?>">
              <input type="hidden" name="id_momento" value="<?php echo $id_momento; ?>">
              <div class="form-floating mb-3">
                <input type="number" name="calificacion" class="form-control" id="calificacion_<?php echo $asignacion['id']; ?>" step="0.1" min="1" max="20" required>
                <label for="calificacion_<?php echo $asignacion['id']; ?>">Calificación (1-20)</label>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
              <button type="submit" class="btn btn-primary">Guardar Calificación</button>
            </div>
          </form>
        </div>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>

  <!-- Incluir scripts al final del body -->
  <script src="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-default@4/default.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <?php
// Incluir pie de página
include __DIR__ . '/partials/footer.php';
?>
</body>
</html>


