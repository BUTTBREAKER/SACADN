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

// Obtener los estudiantes, niveles de estudio, momentos y secciones
$estudiantes_result = $conexion->query('SELECT id, cedula, nombres, apellidos FROM estudiantes');
$estudiantes = $estudiantes_result->fetch_all(MYSQLI_ASSOC);

$niveles_result = $conexion->query('SELECT id, nombre FROM niveles_estudio ORDER BY id');
$niveles = $niveles_result->fetch_all(MYSQLI_ASSOC);

$secciones_result = $conexion->query('SELECT id, nombre FROM secciones');
$secciones = $secciones_result->fetch_all(MYSQLI_ASSOC);

$momentos_result = $conexion->query('SELECT id, numero_momento FROM momentos');
$momentos = $momentos_result->fetch_all(MYSQLI_ASSOC);

// Inicializar $id_momento para evitar errores si no se envía por POST
$id_momento = $_POST['id_momento'] ?? '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_estudiante = $_POST['id_estudiante'];
    $id_momento = $_POST['id_momento'];
    $id_seccion = $_POST['id_seccion'];
    $fecha_registro = date("Y-m-d H:i:s");

    // Actualizar la consulta para la nueva tabla y campos
    $stmt_inscripcion = $conexion->prepare("INSERT INTO inscripciones (id_momento, id_estudiante, id_seccion, fecha_registro) VALUES (?, ?, ?, ?)");
    $stmt_inscripcion->bind_param("iiis", $id_momento, $id_estudiante, $id_seccion, $fecha_registro);

    try {
        $stmt_inscripcion->execute();
        $mensaje = "Estudiante inscrito correctamente.";
    } catch (mysqli_sql_exception $e) {
        $mensaje = "Error: " . $e->getMessage();
    }

    $stmt_inscripcion->close();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inscribir Estudiante</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-default@4/default.min.css" />
</head>

<body>
  <div class="row mx-0 justify-content-center pb-5">
    <form class="card col-md-5 py-4" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
      <h2 class="card-title h3 text-center">Inscribir Estudiante</h2>
      <?php if (isset($mensaje)) : ?>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
          Swal.fire({
            title: 'Resultado',
            text: '<?= $mensaje ?>',
            icon: '<?= strpos($mensaje, "Error") === false ? "success" : "error" ?>',
            confirmButtonText: 'OK'
          });
        </script>
      <?php endif; ?>
      <div class="col-md-12 form-floating mb-3">
        <input type="hidden" name="periodo_id" value="<?= @$periodo_activo['id'] ?>">
        <input class="form-control" type="text" value="<?= @$periodo_activo['anio_inicio'] ?>" readonly>
        <label for="periodo_id">Período Activo:</label>
      </div>

      <div class="col-md-12 form-floating mb-3">
        <select name="id_estudiante" class="form-select" id="id_estudiante" required>
          <option value="" selected disabled>Seleccione un estudiante</option>
          <?php foreach ($estudiantes as $estudiante) : ?>
            <option value="<?= $estudiante['id'] ?>"><?= $estudiante['nombres'] . " " . $estudiante['apellidos'] ?></option>
          <?php endforeach; ?>
        </select>
        <label>Estudiante:</label>
      </div>

      <div class="col-md-12 form-floating mb-3">
        <select name="id_momento" class="form-select" id="id_momento">
          <option value="">Seleccione el lapso</option>
          <?php foreach ($momentos as $momento) : ?>
            <option value="<?php echo $momento['id']; ?>" <?php echo ($momento['id'] == $id_momento) ? 'selected' : ''; ?>>
              <?php echo "Lapso " . $momento['numero_momento']; ?>
            </option>
          <?php endforeach; ?>
        </select>
        <label for="id_momento">Lapso</label>
      </div>

      <div class="col-md-12 form-floating mb-3">
        <select name="id_seccion" class="form-select" id="id_seccion" required>
          <option value="" selected disabled>Seleccione una sección</option>
          <?php foreach ($secciones as $seccion) : ?>
            <option value="<?= $seccion['id'] ?>"><?= $seccion['nombre'] ?></option>
          <?php endforeach; ?>
        </select>
        <label>Sección:</label>
      </div>

      <div class="btn-group btn-group-lg mx-6">
        <button class="btn btn-success w-75">Inscribir Estudiante</button>
        <a href="javascript:history.back()" class="btn btn-outline-secondary">Regresar</a>
      </div>
    </form>
  </div>
</body>

</html>
<?php include __DIR__ . '/partials/footer.php' ?>
