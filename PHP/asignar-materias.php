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

// Obtener los datos necesarios
$profesores_result = $conexion->query('SELECT id, nombre, apellido FROM profesores');
$profesores = $profesores_result->fetch_all(MYSQLI_ASSOC);

$materias_result = $conexion->query('SELECT id, nombre FROM materias');
$materias = $materias_result->fetch_all(MYSQLI_ASSOC);

$niveles_result = $conexion->query('SELECT id, nombre FROM niveles_estudio');
$niveles = $niveles_result->fetch_all(MYSQLI_ASSOC);

$secciones_result = $conexion->query('SELECT id, nombre FROM secciones');
$secciones = $secciones_result->fetch_all(MYSQLI_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $id_profesor = $_POST['id_profesor'];
  $id_materia = $_POST['id_materia'];
  $id_nivel_estudio = $_POST['id_nivel_estudio'];
  $id_seccion = $_POST['id_seccion'];

  // Verificar si ya existe una asignación para esa materia en la misma sección
  $stmt_verificar = $conexion->prepare("SELECT id FROM asignaciones WHERE id_materia = ? AND id_seccion = ?");
  $stmt_verificar->bind_param("ii", $id_materia, $id_seccion);
  $stmt_verificar->execute();
  $stmt_verificar->store_result();

  if ($stmt_verificar->num_rows > 0) {
    $mensaje = "Error: Esta materia ya está asignada a otro profesor en la misma sección.";
    $icono = "error";
  } else {
    $stmt_asignacion = $conexion->prepare("INSERT INTO asignaciones (id_profesor, id_materia, id_nivel_estudio, id_seccion) VALUES (?, ?, ?, ?)");
    $stmt_asignacion->bind_param("iiii", $id_profesor, $id_materia, $id_nivel_estudio, $id_seccion);

    try {
      $stmt_asignacion->execute();
      $mensaje = "Asignación creada correctamente.";
      $icono = "success";
    } catch (mysqli_sql_exception $e) {
      $mensaje = "Error: " . $e->getMessage();
      $icono = "error";
    }

    $stmt_asignacion->close();
  }

  $stmt_verificar->close();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Asignar Materias a Profesores</title>
  <link rel="stylesheet" href="../Assets/sweetalert2/borderless.min.css" />
  <script src="../Assets/sweetalert2/sweetalert2.min.js"></script>
</head>

<body>
  <div class="container">
    <div class="row mx-0 justify-content-center pb-5">
      <form class="card col-md-5 py-4" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <h2 class="card-title h3 text-center">Asignar Materias a Profesores</h2>
        <div class="col-md-12 form-floating mb-3">
          <select name="id_profesor" class="form-select select-styled" id="id_profesor" required>
            <option value="" selected disabled>Seleccione un profesor</option>
            <?php foreach ($profesores as $profesor) : ?>
              <option value="<?= $profesor['id'] ?>"><?= $profesor['nombre'] . " " . $profesor['apellido'] ?></option>
            <?php endforeach; ?>
          </select>
          <label class="ms-2 select-label" for="id_profesor">
            <i class="ri-user-line ri-lg icon"></i>
            Profesor:
          </label>
        </div>

        <div class="col-md-12 form-floating mb-3">
          <select name="id_materia" class="form-select select-styled" id="id_materia" required>
            <option value="" selected disabled>Seleccione una materia</option>
            <?php foreach ($materias as $materia) : ?>
              <option value="<?= $materia['id'] ?>"><?= $materia['nombre'] ?></option>
            <?php endforeach; ?>
          </select>
          <label class="ms-2 select-label" for="id_materia">
            <i class="ri-booklet-line ri-lg icon"></i>
            Materia:
          </label>
        </div>

        <div class="col-md-12 form-floating mb-3">
          <select name="id_nivel_estudio" class="form-select select-styled" id="id_nivel_estudio" required>
            <option value="" selected disabled>Seleccione un nivel de estudio</option>
            <?php foreach ($niveles as $nivel) : ?>
              <option value="<?= $nivel['id'] ?>"><?= $nivel['nombre'] ?></option>
            <?php endforeach; ?>
          </select>
          <label class="ms-2 select-label" for="id_nivel_estudio">
            <i class="ri-barricade-line ri-lg icon"></i>
            Nivel de Estudio:
          </label>
        </div>

        <div class="col-md-12 form-floating mb-3">
          <select name="id_seccion" class="form-select select-styled" id="id_seccion" required>
            <option value="" selected disabled>Seleccione una sección</option>
            <?php foreach ($secciones as $seccion) : ?>
              <option value="<?= $seccion['id'] ?>"><?= $seccion['nombre'] ?></option>
            <?php endforeach; ?>
          </select>
          <label class="ms-2 select-label" for="id_seccion">
            <i class="ri-barricade-line ri-lg icon"></i>
            Sección:
          </label>
        </div>

        <div class="btn-group btn-group-lg mx-6">
          <button class="btn btn-success w-75" type="submit" value="Asignar Materia">Asignar Materia</button>
          <a href="javascript:history.back()" class="btn btn-outline-secondary">Regresar</a>
        </div>

      </form>
    </div>
  </div>
  </div>


  <script>
    document.getElementById('id_nivel_estudio').addEventListener('change', cargarSecciones);

    function cargarSecciones() {
        const idNivelEstudio = document.getElementById('id_nivel_estudio').value;

        if (idNivelEstudio) {
            fetch(`./Procesophp/cargar_secciones.php?id_nivel_estudio=${idNivelEstudio}`)
                .then(response => response.json())
                .then(secciones => {
                    const seccionSelect = document.getElementById('id_seccion');
                    seccionSelect.innerHTML = ''; // Limpiamos las opciones existentes

                    // Iteramos sobre las secciones y creamos opciones
                    secciones.forEach(seccion => {
                        const option = document.createElement('option');
                        option.value = seccion.id;
                        option.textContent = seccion.nombre;
                        seccionSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error al cargar secciones:', error));
        }
    }
    // Mostrar mensaje con SweetAlert2
    <?php if (isset($mensaje)) : ?>
      Swal.fire({
        title: 'Resultado',
        text: '<?= $mensaje ?>',
        icon: '<?= $icono ?>',
        confirmButtonText: 'OK'
      });
    <?php endif; ?>
  </script>

  <?php include __DIR__ . '/partials/footer.php' ?>
</body>

</html>
