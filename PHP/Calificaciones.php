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

// Obtener todos los lapsos, niveles de estudio y secciones
$momentos = $conexion->query("SELECT id, CONCAT('Lapso ', numero_momento) AS momento FROM momentos")->fetch_all(MYSQLI_ASSOC);
$niveles_estudio = $conexion->query("SELECT id, nombre FROM niveles_estudio")->fetch_all(MYSQLI_ASSOC);
$secciones = $conexion->query("SELECT id, nombre FROM secciones")->fetch_all(MYSQLI_ASSOC);

$id_momento = $_POST['id_momento'] ?? null;
$id_nivel_estudio = $_POST['id_nivel_estudio'] ?? null;
$id_seccion = $_POST['id_seccion'] ?? null;
$id_estudiante = $_POST['id_estudiante'] ?? null;

// Obtener los estudiantes
$estudiantes = [];
if ($id_nivel_estudio && $id_seccion) {
  $stmt_estudiantes = $conexion->prepare("SELECT e.id, CONCAT(e.nombre, ' ', e.apellido) as nombre FROM estudiantes e
                                            JOIN inscripciones i ON e.id = i.id_estudiante
                                            WHERE i.id_nivel_estudio = ? AND i.id_seccion = ?");
  $stmt_estudiantes->bind_param("ii", $id_nivel_estudio, $id_seccion);
  $stmt_estudiantes->execute();
  $result_estudiantes = $stmt_estudiantes->get_result();
  $estudiantes = $result_estudiantes->fetch_all(MYSQLI_ASSOC);
  $stmt_estudiantes->close();
}

// Obtener las asignaciones del estudiante si se ha seleccionado un estudiante
$asignaciones = [];
if ($id_estudiante) {
  $stmt_asignaciones = $conexion->prepare("SELECT a.id, m.nombre as nombre_materia, p.nombre as nombre_profesor, c.calificacion
                                             FROM asignaciones a
                                             JOIN materias m ON a.id_materia = m.id
                                             JOIN profesores p ON a.id_profesor = p.id
                                             LEFT JOIN calificaciones c ON c.id_asignacion = a.id AND c.id_estudiante = ?
                                             WHERE a.id_nivel_estudio = ? AND a.id_seccion = ?");
  $stmt_asignaciones->bind_param("iii", $id_estudiante, $id_nivel_estudio, $id_seccion);
  $stmt_asignaciones->execute();
  $result_asignaciones = $stmt_asignaciones->get_result();
  $asignaciones = $result_asignaciones->fetch_all(MYSQLI_ASSOC);
  $stmt_asignaciones->close();
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
        <select name="id_momento" class="form-select" id="id_momento">
          <option value="">Seleccione el lapso</option>
          <?php foreach ($momentos as $momento) : ?>
            <option value="<?php echo $momento['id']; ?>"><?php echo $momento['momento']; ?></option>
          <?php endforeach; ?>
        </select>
        <label for="id_momento">Lapso</label>
      </div>
      <div class="col-md-12 form-floating mb-3">
        <select name="id_nivel_estudio" class="form-select" id="id_nivel_estudio">
          <option value="">Seleccione el nivel de estudio</option>
          <?php foreach ($niveles_estudio as $nivel_estudio) : ?>
            <option value="<?php echo $nivel_estudio['id']; ?>"><?php echo $nivel_estudio['nombre']; ?></option>
          <?php endforeach; ?>
        </select>
        <label for="id_nivel_estudio">Nivel de Estudio</label>
      </div>
      <div class="col-md-12 form-floating mb-3">
        <select name="id_seccion" class="form-select" id="id_seccion">
          <option value="">Seleccione la sección</option>
          <?php foreach ($secciones as $seccion) : ?>
            <option value="<?php echo $seccion['id']; ?>"><?php echo $seccion['nombre']; ?></option>
          <?php endforeach; ?>
        </select>
        <label for="id_seccion">Sección</label>
      </div>

      <div class="col-md-12 form-floating mb-3">
        <select name="id_estudiante" class="form-select" id="id_estudiante">
          <option value="">Seleccione el estudiante</option>
          <?php foreach ($estudiantes as $estudiante) : ?>
            <option value="<?php echo $estudiante['id']; ?>"><?php echo $estudiante['nombre']; ?></option>
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
      <div class="card col-md-8 py-4">
        <h2 class="card-title h3 text-center">Asignaciones de <?php echo $estudiantes[array_search($id_estudiante, array_column($estudiantes, 'id'))]['nombre']; ?></h2>

        <table class="table table-bordered">
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
                  <form id="form-calificacion-<?php echo $asignacion['id']; ?>" action="./procesophp/actualizar_calificacion.php" method="post">
                    <input type="hidden" name="id_asignacion" value="<?php echo $asignacion['id']; ?>">
                    <input type="hidden" name="id_estudiante" value="<?php echo $id_estudiante; ?>">
                    <input type="hidden" name="id_momento" value="<?php echo $id_momento; ?>">
                    <input type="number" name="calificacion" value="<?php echo $asignacion['calificacion']; ?>" class="form-control" min="0" max="20">
                  </form>
                </td>
                <td>
                  <button type="submit" form="form-calificacion-<?php echo $asignacion['id']; ?>" class="btn btn-primary">Guardar</button>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  <?php endif; ?>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    function verificarCalificacion(id_asignacion, id_estudiante) {
      const form = document.getElementById(`form-calificacion-${id_asignacion}`);
      const nueva_calificacion = form.querySelector('input[name="nueva_calificacion"]').value;
      const calificacion_existente = form.querySelector('input[name="calificacion_existente"]').value;

      if (!nueva_calificacion) {
        Swal.fire('Error', 'Debe ingresar una calificación.', 'error');
        return;
      }

      if (nueva_calificacion != calificacion_existente) {
        Swal.fire({
          title: 'Advertencia',
          text: 'La calificación ya existe. ¿Desea actualizarla?',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Sí, actualizar',
          cancelButtonText: 'Cancelar'
        }).then((result) => {
          if (result.isConfirmed) {
            actualizarCalificacion(id_asignacion, id_estudiante, nueva_calificacion);
          }
        });
      } else {
        actualizarCalificacion(id_asignacion, id_estudiante, nueva_calificacion);
      }
    }

    function actualizarCalificacion(id_asignacion, id_estudiante, nueva_calificacion) {
      const formData = new FormData();
      formData.append('id_asignacion', id_asignacion);
      formData.append('id_estudiante', id_estudiante);
      formData.append('nueva_calificacion', nueva_calificacion);

      fetch('./procesophp/actualizar_calificacion.php', {
          method: 'POST',
          body: formData
        })
        .then(response => {
          if (!response.ok) {
            throw new Error('Network response was not ok');
          }
          return response.text();
        })
        .then(data => {
          console.log(data); // Agrega esta línea para depurar
          Swal.fire('Éxito', 'La calificación ha sido guardada correctamente.', 'success').then(() => {
            location.reload();
          });
        })
        .catch(error => {
          console.error(error);
          Swal.fire('Error', 'Ocurrió un error al actualizar la calificación.', 'error');
        });
    }
  </script>

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
  </script>

  <?php include __DIR__ . '/partials/footer.php' ?>
</body>

</html>
