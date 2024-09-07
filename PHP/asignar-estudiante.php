<?php
// verifica que solo pueden entrar los Administradores
include __DIR__ . '/partials/header.php';
require __DIR__ . "/Middlewares/autorizacion.php";
require_once __DIR__ . '/../vendor/autoload.php';

/** @var mysqli */
require_once __DIR__ . '/conexion_be.php';

if ($conexion === false) {
  die("Error al conectar con la base de datos.");
}

// Obtener los estudiantes, niveles de estudio y secciones
$estudiantes_result = $conexion->query('SELECT id, cedula, nombres, apellidos FROM estudiantes');
$estudiantes = $estudiantes_result->fetch_all(MYSQLI_ASSOC);

$niveles_result = $conexion->query('SELECT id, nombre FROM niveles_estudio ORDER BY id');
$niveles = $niveles_result->fetch_all(MYSQLI_ASSOC);

$secciones_result = $conexion->query('SELECT id, nombre FROM secciones');
$secciones = $secciones_result->fetch_all(MYSQLI_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $id_estudiante = $_POST['id_estudiante'];
  $id_nivel_estudio = $_POST['id_nivel_estudio'];
  $id_seccion = $_POST['id_seccion'];

  $stmt_asignacion = $conexion->prepare("INSERT INTO asignaciones_estudiantes (id_estudiante, id_nivel_estudio, id_seccion) VALUES (?, ?, ?)");
  $stmt_asignacion->bind_param("iii", $id_estudiante, $id_nivel_estudio, $id_seccion);

  try {
    $stmt_asignacion->execute();
    $mensaje = "Estudiante asignado correctamente a su nivel y sección.";
  } catch (mysqli_sql_exception $e) {
    $mensaje = "Error: " . $e->getMessage();
  }

  $stmt_asignacion->close();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Asignar Nivel y Sección a Estudiante</title>
  <link rel="stylesheet" href="../Assets/sweetalert2/borderless.min.css" />
</head>

<body>
  <div class="row mx-0 justify-content-center pb-5">
    <form class="card col-md-5 py-4" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

      <h2 class="card-title h3 text-center">Asignar Nivel y Sección a Estudiante</h2>
      <?php if (isset($mensaje)) : ?>
        <script src="../Assets/sweetalert2/sweetalert2.min.js"></script>
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
        <select name="id_estudiante" class="form-select" id="id_estudiante" required>
          <option value="" selected disabled>Seleccione un estudiante</option>
          <?php foreach ($estudiantes as $estudiante) : ?>
            <option value="<?= $estudiante['id'] ?>"><?= $estudiante['nombres'] . " " . $estudiante['apellidos'] ?></option>
          <?php endforeach; ?>
        </select>
        <label class="ms-2">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
            <path fill="currentColor" d="M360 73c-14.43 0-27.79 7.71-38.055 21.395c-10.263 13.684-16.943 33.2-16.943 54.94c0 21.74 6.68 41.252 16.943 54.936c10.264 13.686 23.625 21.396 38.055 21.396s27.79-7.71 38.055-21.395C408.318 190.588 415 171.075 415 149.335c0-21.74-6.682-41.255-16.945-54.94C387.79 80.71 374.43 73 360 73m-240 96c-10.012 0-19.372 5.32-26.74 15.145C85.892 193.968 81 208.15 81 224c0 15.85 4.892 30.032 12.26 39.855C100.628 273.68 109.988 279 120 279c10.012 0 19.374-5.32 26.742-15.145c7.368-9.823 12.256-24.006 12.256-39.855c0-15.85-4.888-30.032-12.256-39.855C139.374 174.32 130.012 169 120 169m188.805 47.674a77.568 77.568 0 0 0-4.737 3.974c-13.716 12.524-23.816 31.052-31.53 54.198c-14.59 43.765-20.404 103.306-30.063 164.154h235.05c-9.66-60.848-15.476-120.39-30.064-164.154c-7.714-23.146-17.812-41.674-31.528-54.198a76.795 76.795 0 0 0-4.737-3.974c-12.84 16.293-30.942 26.994-51.195 26.994s-38.355-10.7-51.195-26.994zM81.27 277.658c-.573.485-1.143.978-1.702 1.488c-9.883 9.024-17.315 22.554-23.03 39.7c-10.6 31.8-15.045 75.344-22.063 120.154h171.048c-7.017-44.81-11.462-88.354-22.062-120.154c-5.714-17.146-13.145-30.676-23.028-39.7a59.378 59.378 0 0 0-1.702-1.488C148.853 289.323 135.222 297 120 297c-15.222 0-28.852-7.678-38.73-19.342" />
          </svg>
          Estudiante:
        </label>
      </div>

      <div class="col-md-12 form-floating mb-3">
        <select name="id_nivel_estudio" class="form-select" id="id_nivel_estudio" required>
          <option value="" selected disabled>Seleccione un nivel de estudio</option>
          <?php foreach ($niveles as $nivel) : ?>
            <option value="<?= $nivel['id'] ?>"><?= $nivel['nombre'] ?></option>
          <?php endforeach; ?>
        </select>
        <label for="id_nivel_estudio">
          <i class="ri-barricade-line"></i>
          <path fill="currentColor" d="M360 73c-14.43 0-27.79 7.71-38.055 21.395c-10.263 13.684-16.943 33.2-16.943 54.94c0 21.74 6.68 41.252 16.943 54.936c10.264 13.686 23.625 21.396 38.055 21.396s27.79-7.71 38.055-21.395C408.318 190.588 415 171.075 415 149.335c0-21.74-6.682-41.255-16.945-54.94C387.79 80.71 374.43 73 360 73m-240 96c-10.012 0-19.372 5.32-26.74 15.145C85.892 193.968 81 208.15 81 224c0 15.85 4.892 30.032 12.26 39.855C100.628 273.68 109.988 279 120 279c10.012 0 19.374-5.32 26.742-15.145c7.368-9.823 12.256-24.006 12.256-39.855c0-15.85-4.888-30.032-12.256-39.855C139.374 174.32 130.012 169 120 169m188.805 47.674a77.568 77.568 0 0 0-4.737 3.974c-13.716 12.524-23.816 31.052-31.53 54.198c-14.59 43.765-20.404 103.306-30.063 164.154h235.05c-9.66-60.848-15.476-120.39-30.064-164.154c-7.714-23.146-17.812-41.674-31.528-54.198a76.795 76.795 0 0 0-4.737-3.974c-12.84 16.293-30.942 26.994-51.195 26.994s-38.355-10.7-51.195-26.994zM81.27 277.658c-.573.485-1.143.978-1.702 1.488c-9.883 9.024-17.315 22.554-23.03 39.7c-10.6 31.8-15.045 75.344-22.063 120.154h171.048c-7.017-44.81-11.462-88.354-22.062-120.154c-5.714-17.146-13.145-30.676-23.028-39.7a59.378 59.378 0 0 0-1.702-1.488C148.853 289.323 135.222 297 120 297c-15.222 0-28.852-7.678-38.73-19.342" />
          Nivel de Estudio:
        </label>
      </div>


      <div class="col-md-12 form-floating mb-3">
        <select name="id_seccion" class="form-select" id="id_seccion" required>
          <option value="" selected disabled>Seleccione una sección</option>
          <?php foreach ($secciones as $seccion) : ?>
            <option value="<?= $seccion['id'] ?>"><?= $seccion['nombre'] ?></option>
          <?php endforeach; ?>
        </select>
        <label for="id_seccion">
          <i class="ri-barricade-line"></i>
          <path fill="currentColor" d="M360 73c-14.43 0-27.79 7.71-38.055 21.395c-10.263 13.684-16.943 33.2-16.943 54.94c0 21.74 6.68 41.252 16.943 54.936c10.264 13.686 23.625 21.396 38.055 21.396s27.79-7.71 38.055-21.395C408.318 190.588 415 171.075 415 149.335c0-21.74-6.682-41.255-16.945-54.94C387.79 80.71 374.43 73 360 73m-240 96c-10.012 0-19.372 5.32-26.74 15.145C85.892 193.968 81 208.15 81 224c0 15.85 4.892 30.032 12.26 39.855C100.628 273.68 109.988 279 120 279c10.012 0 19.374-5.32 26.742-15.145c7.368-9.823 12.256-24.006 12.256-39.855c0-15.85-4.888-30.032-12.256-39.855C139.374 174.32 130.012 169 120 169m188.805 47.674a77.568 77.568 0 0 0-4.737 3.974c-13.716 12.524-23.816 31.052-31.53 54.198c-14.59 43.765-20.404 103.306-30.063 164.154h235.05c-9.66-60.848-15.476-120.39-30.064-164.154c-7.714-23.146-17.812-41.674-31.528-54.198a76.795 76.795 0 0 0-4.737-3.974c-12.84 16.293-30.942 26.994-51.195 26.994s-38.355-10.7-51.195-26.994zM81.27 277.658c-.573.485-1.143.978-1.702 1.488c-9.883 9.024-17.315 22.554-23.03 39.7c-10.6 31.8-15.045 75.344-22.063 120.154h171.048c-7.017-44.81-11.462-88.354-22.062-120.154c-5.714-17.146-13.145-30.676-23.028-39.7a59.378 59.378 0 0 0-1.702-1.488C148.853 289.323 135.222 297 120 297c-15.222 0-28.852-7.678-38.73-19.342" />
          Sección:
        </label>
      </div>
      <div class="btn-group btn-group-lg mx-6">
        <button class="btn btn-success w-75" value="Asignar Nivel y Sección">Asignar Nivel y Sección</button>
        <a href="javascript:history.back()" class="btn btn-outline-secondary">Regresar</a>
      </div>
    </form>
  </div>
</body>

</html>
<?php include __DIR__ . '/partials/footer.php' ?>
