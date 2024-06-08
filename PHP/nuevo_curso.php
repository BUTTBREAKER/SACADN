<?php
// Incluir archivos necesarios
include __DIR__ . '/partials/header.php';
require __DIR__ . "/Middlewares/autorizacion.php";
require_once __DIR__ . '/../vendor/autoload.php';

// Incluir archivo de conexión a la base de datos
require_once __DIR__ . '/conexion_be.php';

// Función para mostrar alerta SweetAlert2
function mostrarAlerta($titulo, $mensaje, $tipo) {
  echo "<script>
            Swal.fire({
                title: '$titulo',
                text: '$mensaje',
                icon: '$tipo',
                confirmButtonText: 'Aceptar'
            });
        </script>";
}

// Verificar si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Obtener los datos del formulario
  $nivel_estudio_id = $_POST['nivel_estudio_id'];
  $nombre_seccion = $_POST['nombre_seccion'];
  $numero_matriculas = $_POST['numero_matriculas'];

  // Verificar si ya existe una sección con el mismo nivel de estudio y nombre de sección
  $stmt_verificar_seccion = $conexion->prepare("SELECT id FROM secciones WHERE id_nivel_estudio = ? AND nombre = ?");
  $stmt_verificar_seccion->bind_param("is", $nivel_estudio_id, $nombre_seccion);
  $stmt_verificar_seccion->execute();
  $result_verificar_seccion = $stmt_verificar_seccion->get_result();
  $stmt_verificar_seccion->close();

  if ($result_verificar_seccion->num_rows > 0) {
    mostrarAlerta("Error", "Ya existe una sección con el mismo nivel de estudio y nombre de sección.", "error");
  } else {
    // Insertar la sección
    $stmt_seccion = $conexion->prepare("INSERT INTO secciones (nombre, id_nivel_estudio, numero_matriculas) VALUES (?, ?, ?)");
    $stmt_seccion->bind_param("sii", $nombre_seccion, $nivel_estudio_id, $numero_matriculas);
    $stmt_seccion->execute();
    $stmt_seccion->close();

    // Mostrar alerta de éxito
    mostrarAlerta("Éxito", "Sección creada correctamente.", "success");
  }
}

// Obtener los niveles de estudio existentes
$stmt_niveles = $conexion->prepare("SELECT id, nombre FROM niveles_estudio ORDER BY id");
$stmt_niveles->execute();
$result_niveles = $stmt_niveles->get_result();
$stmt_niveles->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Crear Nivel de Estudio y Sección</title>
  <!-- Enlaces de estilo y script de SweetAlert2 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-default@11/default.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  
</head>

<body>
  <div class="row mx-0 justify-content-center pb-5">
    <form class="card col-md-5 py-4" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <h2 class="card-title h3 text-center">Crear Nivel de Estudio y Sección</h2>
   

    <div class="col-md-12 form-floating mb-3"> 
      <select placeholder=""  name="nivel_estudio_id" class="form-select" id="nivel_estudio" required>
        <option selected disabled>Seleccione un nivel de estudio</option>
        <?php while ($row = $result_niveles->fetch_assoc()) : ?>
          <option value="<?= $row['id'] ?>"><?= $row['nombre'] ?></option>
        <?php endwhile; ?>
      </select>
      <label class="ms-2" for="nivel_estudio">
             <i class="ri-barricade-line"></i>
            <path fill="currentColor" d="M360 73c-14.43 0-27.79 7.71-38.055 21.395c-10.263 13.684-16.943 33.2-16.943 54.94c0 21.74 6.68 41.252 16.943 54.936c10.264 13.686 23.625 21.396 38.055 21.396s27.79-7.71 38.055-21.395C408.318 190.588 415 171.075 415 149.335c0-21.74-6.682-41.255-16.945-54.94C387.79 80.71 374.43 73 360 73m-240 96c-10.012 0-19.372 5.32-26.74 15.145C85.892 193.968 81 208.15 81 224c0 15.85 4.892 30.032 12.26 39.855C100.628 273.68 109.988 279 120 279c10.012 0 19.374-5.32 26.742-15.145c7.368-9.823 12.256-24.006 12.256-39.855c0-15.85-4.888-30.032-12.256-39.855C139.374 174.32 130.012 169 120 169m188.805 47.674a77.568 77.568 0 0 0-4.737 3.974c-13.716 12.524-23.816 31.052-31.53 54.198c-14.59 43.765-20.404 103.306-30.063 164.154h235.05c-9.66-60.848-15.476-120.39-30.064-164.154c-7.714-23.146-17.812-41.674-31.528-54.198a76.795 76.795 0 0 0-4.737-3.974c-12.84 16.293-30.942 26.994-51.195 26.994s-38.355-10.7-51.195-26.994zM81.27 277.658c-.573.485-1.143.978-1.702 1.488c-9.883 9.024-17.315 22.554-23.03 39.7c-10.6 31.8-15.045 75.344-22.063 120.154h171.048c-7.017-44.81-11.462-88.354-22.062-120.154c-5.714-17.146-13.145-30.676-23.028-39.7a59.378 59.378 0 0 0-1.702-1.488C148.853 289.323 135.222 297 120 297c-15.222 0-28.852-7.678-38.73-19.342" />
      Nivel de Estudio:
    </label>
    </div>

    <div class="col-md-12 form-floating mb-3"> 
      <input class="form-control" type="text" placeholder="" name="nombre_seccion" id="nombre_seccion" required maxlength="1">
      <label class="ms-2" for="nombre_seccion">
             <i class="ri-barricade-line"></i>
            <path fill="currentColor" d="M360 73c-14.43 0-27.79 7.71-38.055 21.395c-10.263 13.684-16.943 33.2-16.943 54.94c0 21.74 6.68 41.252 16.943 54.936c10.264 13.686 23.625 21.396 38.055 21.396s27.79-7.71 38.055-21.395C408.318 190.588 415 171.075 415 149.335c0-21.74-6.682-41.255-16.945-54.94C387.79 80.71 374.43 73 360 73m-240 96c-10.012 0-19.372 5.32-26.74 15.145C85.892 193.968 81 208.15 81 224c0 15.85 4.892 30.032 12.26 39.855C100.628 273.68 109.988 279 120 279c10.012 0 19.374-5.32 26.742-15.145c7.368-9.823 12.256-24.006 12.256-39.855c0-15.85-4.888-30.032-12.256-39.855C139.374 174.32 130.012 169 120 169m188.805 47.674a77.568 77.568 0 0 0-4.737 3.974c-13.716 12.524-23.816 31.052-31.53 54.198c-14.59 43.765-20.404 103.306-30.063 164.154h235.05c-9.66-60.848-15.476-120.39-30.064-164.154c-7.714-23.146-17.812-41.674-31.528-54.198a76.795 76.795 0 0 0-4.737-3.974c-12.84 16.293-30.942 26.994-51.195 26.994s-38.355-10.7-51.195-26.994zM81.27 277.658c-.573.485-1.143.978-1.702 1.488c-9.883 9.024-17.315 22.554-23.03 39.7c-10.6 31.8-15.045 75.344-22.063 120.154h171.048c-7.017-44.81-11.462-88.354-22.062-120.154c-5.714-17.146-13.145-30.676-23.028-39.7a59.378 59.378 0 0 0-1.702-1.488C148.853 289.323 135.222 297 120 297c-15.222 0-28.852-7.678-38.73-19.342" />
      Nombre de la Sección (una letra):
    </label>
    </div>

     <div class="col-md-12 form-floating mb-3"> 
      <input class="form-control" type="number" placeholder="" name="numero_matriculas" id="numero_matriculas" required>
       <label class="ms-2" for="numero_matriculas">
       Número de Matrículas:</label>
     </div>
     
     <div class="btn-group btn-group-lg mx-3">
      <button class="btn btn-success w-75" value="Crear Sección">Crear Seccion</button>
    </div>

    </form>
  </div>

  <script>
    // Función para mostrar alerta SweetAlert2
    function mostrarAlerta(titulo, mensaje, tipo) {
      Swal.fire({
        title: titulo,
        text: mensaje,
        icon: tipo,
        confirmButtonText: 'Aceptar'
      });
    }
  </script>
</body>

</html>
<?php include __DIR__ . '/partials/footer.php'; ?>
