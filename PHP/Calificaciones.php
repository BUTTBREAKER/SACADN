
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

// Obtener todos los periodos, niveles de estudio y secciones
$periodos = $conexion->query("SELECT id, anio_inicio FROM periodos")->fetch_all(MYSQLI_ASSOC);
$niveles_estudio = $conexion->query("SELECT id, nombre FROM niveles_estudio")->fetch_all(MYSQLI_ASSOC);
$secciones = $conexion->query("SELECT id, nombre FROM secciones")->fetch_all(MYSQLI_ASSOC);

$id_periodo = $_POST['id_periodo'] ?? null;
$id_nivel_estudio = $_POST['id_nivel_estudio'] ?? null;
$id_seccion = $_POST['id_seccion'] ?? null;

// Obtener las asignaciones
$asignaciones = [];
if ($id_periodo && $id_nivel_estudio && $id_seccion) {
  $stmt_asignaciones = $conexion->prepare("SELECT a.id, m.nombre as nombre_materia, p.nombre as nombre_profesor
                                             FROM asignaciones a
                                             JOIN materias m ON a.id_materia = m.id
                                             JOIN profesores p ON a.id_profesor = p.id
                                             WHERE a.id_periodo = ? AND a.id_nivel_estudio = ? AND a.id_seccion = ?");
  $stmt_asignaciones->bind_param("iii", $id_periodo, $id_nivel_estudio, $id_seccion);
  $stmt_asignaciones->execute();
  $result_asignaciones = $stmt_asignaciones->get_result();
  $asignaciones = $result_asignaciones->fetch_all(MYSQLI_ASSOC);
  $stmt_asignaciones->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_materia'], $_POST['id_estudiante'], $_POST['calificacion'])) {
  $id_materia = $_POST['id_materia'];
  $id_estudiante = $_POST['id_estudiante'];
  $calificacion = $_POST['calificacion'];

  // Obtener id_boletin basado en el estudiante y periodo (usado como momento)
  $stmt_boletin = $conexion->prepare("SELECT id FROM boletines WHERE id_estudiante = ? AND id_momento = ?");
  $stmt_boletin->bind_param("ii", $id_estudiante, $id_periodo);
  $stmt_boletin->execute();
  $result_boletin = $stmt_boletin->get_result();
  $boletin = $result_boletin->fetch_assoc();
  $id_boletin = $boletin['id'] ?? null;

  if ($id_boletin) {
    $stmt_calificacion = $conexion->prepare("INSERT INTO calificaciones (id_materia, id_boletin, calificacion) VALUES (?, ?, ?)");
    $stmt_calificacion->bind_param("iii", $id_materia, $id_boletin, $calificacion);

    try {
      $stmt_calificacion->execute();
      $mensaje = "Calificación ingresada correctamente.";
    } catch (mysqli_sql_exception $e) {
      $mensaje = "Error: " . $e->getMessage();
    }

    $stmt_calificacion->close();
  } else {
    $mensaje = "No se encontró un boletín para el estudiante y el periodo seleccionados.";
  }
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
        <select name="id_periodo" class="form-select" id="id_periodo" required>
          <option value="" selected disabled>Seleccione un periodo</option>
          <?php foreach ($periodos as $periodo) : ?>
            <option value="<?= $periodo['id'] ?>"><?= $periodo['anio_inicio'] ?></option>
          <?php endforeach; ?>
        </select>
        <label for="id_periodo">
          <i class="ri-calendar-line"></i>
          <path fill="currentColor" d="M360 73c-14.43 0-27.79 7.71-38.055 21.395c-10.263 13.684-16.943 33.2-16.943 54.94c0 21.74 6.68 41.252 16.943 54.936c10.264 13.686 23.625 21.396 38.055 21.396s27.79-7.71 38.055-21.395C408.318 190.588 415 171.075 415 149.335c0-21.74-6.682-41.255-16.945-54.94C387.79 80.71 374.43 73 360 73m-240 96c-10.012 0-19.372 5.32-26.74 15.145C85.892 193.968 81 208.15 81 224c0 15.85 4.892 30.032 12.26 39.855C100.628 273.68 109.988 279 120 279c10.012 0 19.374-5.32 26.742-15.145c7.368-9.823 12.256-24.006 12.256-39.855c0-15.85-4.888-30.032-12.256-39.855C139.374 174.32 130.012 169 120 169m188.805 47.674a77.568 77.568 0 0 0-4.737 3.974c-13.716 12.524-23.816 31.052-31.53 54.198c-14.59 43.765-20.404 103.306-30.063 164.154h235.05c-9.66-60.848-15.476-120.39-30.064-164.154c-7.714-23.146-17.812-41.674-31.528-54.198a76.795 76.795 0 0 0-4.737-3.974c-12.84 16.293-30.942 26.994-51.195 26.994s-38.355-10.7-51.195-26.994zM81.27 277.658c-.573.485-1.143.978-1.702 1.488c-9.883 9.024-17.315 22.554-23.03 39.7c-10.6 31.8-15.045 75.344-22.063 120.154h171.048c-7.017-44.81-11.462-88.354-22.062-120.154c-5.714-17.146-13.145-30.676-23.028-39.7a59.378 59.378 0 0 0-1.702-1.488C148.853 289.323 135.222 297 120 297c-15.222 0-28.852-7.678-38.73-19.342" />
          Periodo:
        </label>
      </div>

      <div class="col-md-12 form-floating mb-3">
        <select name="id_nivel_estudio" class="form-select" id="id_nivel_estudio" required>
          <option value="" selected disabled>Seleccione un nivel de estudio</option>
          <?php foreach ($niveles_estudio as $nivel) : ?>
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
          <!-- Las opciones se cargarán mediante AJAX -->
        </select>
        <label for="id_seccion">
          <i class="ri-barricade-line"></i>
          <path fill="currentColor" d="M360 73c-14.43 0-27.79 7.71-38.055 21.395c-10.263 13.684-16.943 33.2-16.943 54.94c0 21.74 6.68 41.252 16.943 54.936c10.264 13.686 23.625 21.396 38.055 21.396s27.79-7.71 38.055-21.395C408.318 190.588 415 171.075 415 149.335c0-21.74-6.682-41.255-16.945-54.94C387.79 80.71 374.43 73 360 73m-240 96c-10.012 0-19.372 5.32-26.74 15.145C85.892 193.968 81 208.15 81 224c0 15.85 4.892 30.032 12.26 39.855C100.628 273.68 109.988 279 120 279c10.012 0 19.374-5.32 26.742-15.145c7.368-9.823 12.256-24.006 12.256-39.855c0-15.85-4.888-30.032-12.256-39.855C139.374 174.32 130.012 169 120 169m188.805 47.674a77.568 77.568 0 0 0-4.737 3.974c-13.716 12.524-23.816 31.052-31.53 54.198c-14.59 43.765-20.404 103.306-30.063 164.154h235.05c-9.66-60.848-15.476-120.39-30.064-164.154c-7.714-23.146-17.812-41.674-31.528-54.198a76.795 76.795 0 0 0-4.737-3.974c-12.84 16.293-30.942 26.994-51.195 26.994s-38.355-10.7-51.195-26.994zM81.27 277.658c-.573.485-1.143.978-1.702 1.488c-9.883 9.024-17.315 22.554-23.03 39.7c-10.6 31.8-15.045 75.344-22.063 120.154h171.048c-7.017-44.81-11.462-88.354-22.062-120.154c-5.714-17.146-13.145-30.676-23.028-39.7a59.378 59.378 0 0 0-1.702-1.488C148.853 289.323 135.222 297 120 297c-15.222 0-28.852-7.678-38.73-19.342" />
          Sección:
        </label>
      </div>

      <div class="col-md-12 form-floating mb-3">
        <select name="id_materia" class="form-select" id="id_materia" required>
          <option value="" selected disabled>Seleccione una materia</option>
          <?php foreach ($asignaciones as $asignacion) : ?>
            <option value="<?= $asignacion['id'] ?>"><?= $asignacion['nombre_materia'] ?> (Profesor: <?= $asignacion['nombre_profesor'] ?>)</option>
          <?php endforeach; ?>
        </select>
        <label for="id_materia">
          <i class="ri-booklet-line"></i>
          <path fill="currentColor" d="M360 73c-14.43 0-27.79 7.71-38.055 21.395c-10.263 13.684-16.943 33.2-16.943 54.94c0 21.74 6.68 41.252 16.943 54.936c10.264 13.686 23.625 21.396 38.055 21.396s27.79-7.71 38.055-21.395C408.318 190.588 415 171.075 415 149.335c0-21.74-6.682-41.255-16.945-54.94C387.79 80.71 374.43 73 360 73m-240 96c-10.012 0-19.372 5.32-26.74 15.145C85.892 193.968 81 208.15 81 224c0 15.85 4.892 30.032 12.26 39.855C100.628 273.68 109.988 279 120 279c10.012 0 19.374-5.32 26.742-15.145c7.368-9.823 12.256-24.006 12.256-39.855c0-15.85-4.888-30.032-12.256-39.855C139.374 174.32 130.012 169 120 169m188.805 47.674a77.568 77.568 0 0 0-4.737 3.974c-13.716 12.524-23.816 31.052-31.53 54.198c-14.59 43.765-20.404 103.306-30.063 164.154h235.05c-9.66-60.848-15.476-120.39-30.064-164.154c-7.714-23.146-17.812-41.674-31.528-54.198a76.795 76.795 0 0 0-4.737-3.974c-12.84 16.293-30.942 26.994-51.195 26.994s-38.355-10.7-51.195-26.994zM81.27 277.658c-.573.485-1.143.978-1.702 1.488c-9.883 9.024-17.315 22.554-23.03 39.7c-10.6 31.8-15.045 75.344-22.063 120.154h171.048c-7.017-44.81-11.462-88.354-22.062-120.154c-5.714-17.146-13.145-30.676-23.028-39.7a59.378 59.378 0 0 0-1.702-1.488C148.853 289.323 135.222 297 120 297c-15.222 0-28.852-7.678-38.73-19.342" />
          Materia:
        </label>
      </div>

      <div class="col-md-12 form-floating mb-3">
        <select name="id_estudiante" class="form-select" id="id_estudiante" required>
          <option value="" selected disabled>Seleccione un estudiante</option>
          <!-- Las opciones se cargarán mediante AJAX -->
        </select>
        <label for="id_estudiante">
          <label class="ms-2">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
              <path fill="currentColor" d="M360 73c-14.43 0-27.79 7.71-38.055 21.395c-10.263 13.684-16.943 33.2-16.943 54.94c0 21.74 6.68 41.252 16.943 54.936c10.264 13.686 23.625 21.396 38.055 21.396s27.79-7.71 38.055-21.395C408.318 190.588 415 171.075 415 149.335c0-21.74-6.682-41.255-16.945-54.94C387.79 80.71 374.43 73 360 73m-240 96c-10.012 0-19.372 5.32-26.74 15.145C85.892 193.968 81 208.15 81 224c0 15.85 4.892 30.032 12.26 39.855C100.628 273.68 109.988 279 120 279c10.012 0 19.374-5.32 26.742-15.145c7.368-9.823 12.256-24.006 12.256-39.855c0-15.85-4.888-30.032-12.256-39.855C139.374 174.32 130.012 169 120 169m188.805 47.674a77.568 77.568 0 0 0-4.737 3.974c-13.716 12.524-23.816 31.052-31.53 54.198c-14.59 43.765-20.404 103.306-30.063 164.154h235.05c-9.66-60.848-15.476-120.39-30.064-164.154c-7.714-23.146-17.812-41.674-31.528-54.198a76.795 76.795 0 0 0-4.737-3.974c-12.84 16.293-30.942 26.994-51.195 26.994s-38.355-10.7-51.195-26.994zM81.27 277.658c-.573.485-1.143.978-1.702 1.488c-9.883 9.024-17.315 22.554-23.03 39.7c-10.6 31.8-15.045 75.344-22.063 120.154h171.048c-7.017-44.81-11.462-88.354-22.062-120.154c-5.714-17.146-13.145-30.676-23.028-39.7a59.378 59.378 0 0 0-1.702-1.488C148.853 289.323 135.222 297 120 297c-15.222 0-28.852-7.678-38.73-19.342" />
            </svg>
            Estudiante:
          </label>
      </div>

      <div class="col-md-12 form-floating mb-3">
        <input class="form-control" type="number" name="calificacion" id="calificacion" min="0" max="20" required>
        <label class="ms-2" for="calificacion">Calificación:</label>
      </div>

      <div class="btn-group btn-group-lg mx-6">
        <button class="btn btn-success w-75" type="submit" value="Cargar Nota">Cargar Nota</button>
        <a href="javascript:history.back()" class="btn btn-outline-secondary">Regresar</a>
      </div>
    </form>
  </div>


  <script>
    // Cargar secciones cuando se selecciona el nivel de estudio
    document.getElementById('id_nivel_estudio').addEventListener('change', cargarSecciones);

    // Cargar estudiantes cuando se selecciona el nivel de estudio y la sección
    document.getElementById('id_nivel_estudio').addEventListener('change', cargarEstudiantes);
    document.getElementById('id_seccion').addEventListener('change', cargarEstudiantes);

    function cargarSecciones() {
      const idNivelEstudio = document.getElementById('id_nivel_estudio').value;

      if (idNivelEstudio) {
        fetch(`./procesophp/cargar_secciones.php?id_nivel_estudio=${idNivelEstudio}`)
          .then(response => response.json())
          .then(secciones => {
            const seccionSelect = document.getElementById('id_seccion');
            seccionSelect.innerHTML = '<option value="" selected disabled>Seleccione una sección</option>';
            secciones.forEach(seccion => {
              seccionSelect.innerHTML += `<option value="${seccion.id}">${seccion.nombre}</option>`;
            });
          })
          .catch(error => console.error('Error al cargar secciones:', error));
      }
    }

    function cargarEstudiantes() {
      const idNivelEstudio = document.getElementById('id_nivel_estudio').value;
      const idSeccion = document.getElementById('id_seccion').value;

      if (idNivelEstudio && idSeccion) {
        fetch(`./procesophp/cargar_estudiantes.php?id_nivel_estudio=${idNivelEstudio}&id_seccion=${idSeccion}`)
          .then(response => response.json())
          .then(estudiantes => {
            const estudiantesSelect = document.getElementById('id_estudiante');
            estudiantesSelect.innerHTML = '<option value="" selected disabled>Seleccione un estudiante</option>';
            estudiantes.forEach(estudiante => {
              estudiantesSelect.innerHTML += `<option value="${estudiante.id}">${estudiante.nombre} ${estudiante.apellido}</option>`;
            });
          })
          .catch(error => console.error('Error al cargar estudiantes:', error));
      }
    }

    // Agrega la llamada a cargarMaterias() en los eventos de cambio
document.getElementById('id_periodo').addEventListener('change', cargarMaterias);
document.getElementById('id_nivel_estudio').addEventListener('change', cargarMaterias);
document.getElementById('id_seccion').addEventListener('change', cargarMaterias);

// Define la función cargarMaterias()
function cargarMaterias() {
  const idPeriodo = document.getElementById('id_periodo').value;
  const idNivelEstudio = document.getElementById('id_nivel_estudio').value;
  const idSeccion = document.getElementById('id_seccion').value;

  if (idPeriodo && idNivelEstudio && idSeccion) {
    fetch(`./procesophp/cargar_materias.php?id_periodo=${idPeriodo}&id_nivel_estudio=${idNivelEstudio}&id_seccion=${idSeccion}`)
      .then(response => response.json())
      .then(materias => {
        // Aquí puedes manejar las materias devueltas por el archivo PHP
        const materiaSelect = document.getElementById('id_materia');
        materiaSelect.innerHTML = '<option value="" selected disabled>Seleccione una materia</option>';
        materias.forEach(materia => {
          materiaSelect.innerHTML += `<option value="${materia.id}">${materia.nombre}</option>`;
        });
      })
      .catch(error => console.error('Error al cargar las materias:', error));
  }
}

  </script>

  <?php include __DIR__ . '/partials/footer.php' ?>
</body>

</html>
