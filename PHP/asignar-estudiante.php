<?php
// Verifica que solo pueden entrar los Administradores
include __DIR__ . '/partials/header.php';
require __DIR__ . "/Middlewares/autorizacion.php";
require_once __DIR__ . '/../vendor/autoload.php';

/** @var mysqli */
require_once __DIR__ . '/conexion_be.php';

if ($conexion === false) {
  die("Error al conectar con la base de datos.");
}

// Obtener el periodo activo
$stmt_periodo_activo = $conexion->prepare("SELECT id, anio_inicio FROM periodos WHERE estado = 'activo' LIMIT 1");
$stmt_periodo_activo->execute();
$result_periodo_activo = $stmt_periodo_activo->get_result();
$periodo_activo = $result_periodo_activo->fetch_assoc();
$stmt_periodo_activo->close();

// Obtener los estudiantes y niveles de estudio
$estudiantes_result = $conexion->query('SELECT id, cedula, nombres, apellidos FROM estudiantes');
$estudiantes = $estudiantes_result->fetch_all(MYSQLI_ASSOC);

$niveles_result = $conexion->query('SELECT id, nombre FROM niveles_estudio ORDER BY id');
$niveles = $niveles_result->fetch_all(MYSQLI_ASSOC);

// Procesar la solicitud AJAX para cargar las secciones
if (isset($_POST['nivel_id'])) {
  $nivel_id = intval($_POST['nivel_id']);
  $periodo_id = intval($periodo_activo['id']); // Obtener el ID del periodo activo

  $secciones_result = $conexion->prepare("
    SELECT id, nombre
    FROM secciones
    WHERE id_nivel_estudio = ? AND id_periodo = ?
  ");
  $secciones_result->bind_param("ii", $nivel_id, $periodo_id);
  $secciones_result->execute();
  $secciones = $secciones_result->get_result()->fetch_all(MYSQLI_ASSOC);

  // Retornar las opciones de secciones
  foreach ($secciones as $seccion) {
    echo '<option value="' . $seccion['id'] . '">' . $seccion['nombre'] . '</option>';
  }
  exit;
}

// Inicializar $id_momento para evitar errores si no se envía por POST
$id_momento = $_POST['id_momento'] ?? '';

// Manejar la inscripción de estudiantes
if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['nivel_id'])) {
  $id_estudiante = $_POST['id_estudiante'];
  $id_seccion = $_POST['id_seccion'];
  $fecha_registro = date("Y-m-d H:i:s");
  $periodo_id = $_POST['periodo_id'];

  // Verificar si el estudiante ya está inscrito
  $stmt_verificar = $conexion->prepare("SELECT COUNT(*) as inscripcion_existente FROM inscripciones WHERE id_estudiante = ? AND id_periodo = ?");
  $stmt_verificar->bind_param("ii", $id_estudiante, $periodo_id);
  $stmt_verificar->execute();
  $result_verificar = $stmt_verificar->get_result()->fetch_assoc();

  if ($result_verificar['inscripcion_existente'] > 0) {
    $mensaje = "Error: El estudiante ya está inscrito en otra sección.";
  } else {
    // Verificar cupos
    $stmt_cupos = $conexion->prepare("
            SELECT s.numero_matriculas - COUNT(i.id) AS cupos_disponibles
            FROM secciones s
            LEFT JOIN inscripciones i ON i.id_seccion = s.id AND i.id_periodo = ?
            WHERE s.id = ?
        ");
    $stmt_cupos->bind_param("ii", $periodo_id, $id_seccion);
    $stmt_cupos->execute();
    $result_cupos = $stmt_cupos->get_result()->fetch_assoc();

    if ($result_cupos['cupos_disponibles'] <= 0) {
      $mensaje = "Error: No hay cupos disponibles en esta sección.";
    } else {
      // Proceder con la inscripción
      $stmt_inscripcion = $conexion->prepare("INSERT INTO inscripciones (id_periodo, id_estudiante, id_seccion, fecha_registro) VALUES (?, ?, ?, ?)");
      $stmt_inscripcion->bind_param("iiis", $periodo_id, $id_estudiante, $id_seccion, $fecha_registro);
      try {
        $stmt_inscripcion->execute();
        $mensaje = "Estudiante inscrito correctamente.";
      } catch (mysqli_sql_exception $e) {
        $mensaje = "Error: " . $e->getMessage();
      }
      $stmt_inscripcion->close();
    }
    $stmt_cupos->close();
  }
  $stmt_verificar->close();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inscribir Estudiante</title>
  <link rel="stylesheet" href="../Assets/sweetalert2/borderless.min.css" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
  <div class="row mx-0 justify-content-center pb-5">
    <form class="card col-md-5 py-4" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
      <h2 class="card-title h3 text-center">Inscribir Estudiante</h2>
      <?php if (isset($mensaje)) : ?>
        <script src="../Assets/sweetalert2/sweetalert2.min.js"></script>
        <script>
          Swal.fire({
            title: '<?= strpos($mensaje, "Error") !== false ? "Error" : "Resultado" ?>',
            text: '<?= $mensaje ?>',
            icon: '<?= strpos($mensaje, "Error") !== false ? "error" : "success" ?>',
            confirmButtonText: 'OK'
          });
        </script>
      <?php endif; ?>

      <div class="col-md-12 form-floating mb-3">
        <input type="hidden" name="periodo_id" value="<?= @$periodo_activo['id'] ?>">
        <input class="form-control" type="text" value="<?= htmlspecialchars("{$periodo_activo ['anio_inicio']}-" . (@$periodo_activo['anio_inicio'] + 1)) ?>" readonly>
        <label for="periodo_id">Período Activo:</label>
      </div>

      <!-- Campo de búsqueda de cédula -->
      <div class="col-md-12 form-floating mb-3">
        <input type="text" id="buscador_cedula" class="form-control" placeholder="Buscar por cédula">
        <label for="buscador_cedula">Buscar por cédula:</label>
      </div>

      <!-- Select de estudiantes -->
      <div class="col-md-12 form-floating mb-3">
        <select name="id_estudiante" class="form-select" id="id_estudiante" required>
          <option value="" selected disabled>Seleccione un estudiante</option>
          <?php foreach ($estudiantes as $estudiante) : ?>
            <option value="<?= $estudiante['id'] ?>" data-cedula="<?= $estudiante['cedula'] ?>">
              <?= htmlspecialchars($estudiante['nombres'] . " " . $estudiante['apellidos'] . " - " . $estudiante['cedula']) ?>
            </option>
          <?php endforeach; ?>
        </select>
        <label for="id_estudiante">Estudiante:</label>
      </div>

      <div class="col-md-12 form-floating mb-3">
        <select name="id_nivel" class="form-select" id="id_nivel" required>
          <option value="" selected disabled>Seleccione un nivel</option>
          <?php foreach ($niveles as $nivel) : ?>
            <option value="<?= $nivel['id'] ?>"><?= $nivel['nombre'] ?></option>
          <?php endforeach; ?>
        </select>
        <label for="id_nivel">Nivel de Estudio:</label>
      </div>

      <div class="col-md-12 form-floating mb-3">
        <select name="id_seccion" class="form-select" id="id_seccion" required>
          <option value="" selected disabled>Seleccione una sección</option>
        </select>
        <label for="id_seccion">Sección:</label>
      </div>

      <div class="btn-group btn-group-lg mx-6">
        <button class="btn btn-success w-75">Inscribir Estudiante</button>
        <a href="javascript:history.back()" class="btn btn-outline-secondary">Regresar</a>
      </div>
    </form>
  </div>

  <script>
    $(document).ready(function() {
      // Filtrar estudiantes por cédula
      $('#buscador_cedula').on('input', function() {
        var cedula = $(this).val().toLowerCase();
        $('#id_estudiante option').each(function() {
          if ($(this).val() !== "") { // Excluye la opción por defecto
            var estudianteCedula = $(this).data('cedula').toString().toLowerCase();
            if (estudianteCedula.includes(cedula)) {
              $(this).show();
            } else {
              $(this).hide();
            }
          }
        });

        // Resetear la selección si la opción seleccionada está oculta
        if ($('#id_estudiante option:selected').is(':hidden')) {
          $('#id_estudiante').val('');
        }
      });

      // Cargar secciones dinámicamente al seleccionar el nivel
      $('#id_nivel').change(function() {
        var nivel_id = $(this).val();
        if (nivel_id) {
          $.post('<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>', {
            nivel_id: nivel_id
          }, function(data) {
            $('#id_seccion').html(data);
          });
        }
      });
    });
  </script>
</body>

</html>
<?php include __DIR__ . '/partials/footer.php'; ?>
