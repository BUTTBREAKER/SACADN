<?php
// Verificar que solo los Administradores pueden acceder
include __DIR__ . '/partials/header.php';
require __DIR__ . "/Middlewares/autorizacion.php";

// Incluir archivo de conexión a la base de datos
require_once __DIR__ . '/conexion_be.php';

// Obtener el periodo activo
$stmt_periodo_activo = $conexion->prepare("SELECT id, anio_inicio FROM periodos WHERE estado = 'activo' LIMIT 1");
$stmt_periodo_activo->execute();
$result_periodo_activo = $stmt_periodo_activo->get_result();
$periodo_activo = $result_periodo_activo->fetch_assoc();
$stmt_periodo_activo->close();

if ($_POST) {
  $nombre = $_POST['nombre'];
  $periodo_id = $_POST['periodo_id']; // Obtener el periodo activo

  // Consulta SQL para insertar la nueva materia
  $sql = "INSERT INTO `materias`(`nombre`, `id_periodo`)
          VALUES ('$nombre', $periodo_id)";

  $resultado = mysqli_query($conexion, $sql);

  if ($resultado) {
    exit(<<<HTML
    <body>
      <link rel="stylesheet" href="../Assets/sweetalert2/borderless.min.css" />
      <script src="../Assets/sweetalert2/sweetalert2.min.js"></script>
      <script>
        Swal.fire({
          title: 'Materia Registrada Exitosamente',
          icon: 'success',
          showConfirmButton: false,
          timer: 3000
        }).then(() => location.href = './materias.php')
      </script>
    </body>
    HTML);
  } else {
    echo "Error: " . mysqli_error($conexion);
  }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registrar Nueva Materia</title>
  <link rel="stylesheet" href="../Assets/sweetalert2/borderless.min.css" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
  <div class="row mx-0 justify-content-center pb-5">
    <form class="card col-md-5 py-4" action="nueva_materia.php" method="POST">
      <h2 class="card-title h3 text-center">Registrar Nueva Materia</h2>

      <!-- Campo oculto para el periodo activo -->
      <input type="hidden" name="periodo_id" value="<?= @$periodo_activo['id'] ?>">

      <div class="col-md-12 form-floating mb-3">
        <input class="form-control" type="text" id="nombre" name="nombre" placeholder=" " required>
        <label for="nombre">Nombre de la Materia:</label>
      </div>

      <div class="col-md-12 form-floating mb-3">
        <input class="form-control" type="text" value="<?= @$periodo_activo['anio_inicio'] ?>" readonly>
        <label for="periodo_id">Período Activo:</label>
      </div>

      <!-- Botón para enviar el formulario -->
      <div class="btn-group btn-group-lg mx-6">
        <button class="btn btn-success w-75">Registrar Materia</button>
        <a href="javascript:history.back()" class="btn btn-outline-secondary">Regresar</a>
      </div>
    </form>
  </div>
</body>

</html>
<?php include __DIR__ . '/partials/footer.php'; ?>
