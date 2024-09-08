<?php
// Incluir cabecera
include __DIR__ . '/partials/header.php';

// Verificar autorización del usuario (profesor)
require __DIR__ . "/middlewares/autorizacion.php";
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
$niveles_estudio = $conexion->query("SELECT id, nombre FROM niveles_estudio ORDER BY id")->fetch_all(MYSQLI_ASSOC);
$secciones = $conexion->query("SELECT id, nombre FROM secciones")->fetch_all(MYSQLI_ASSOC);

// Obtener el nombre y apellido del estudiante seleccionado
$nombre_estudiante = '';
if ($id_estudiante) {
  $stmt_nombre_estudiante = $conexion->prepare("SELECT nombres, apellidos FROM estudiantes WHERE id = ?");
  $stmt_nombre_estudiante->bind_param("i", $id_estudiante);
  $stmt_nombre_estudiante->execute();
  $result_nombre_estudiante = $stmt_nombre_estudiante->get_result();
  $nombre_estudiante_data = $result_nombre_estudiante->fetch_assoc();
  $stmt_nombre_estudiante->close();

  if ($nombre_estudiante_data) {
    $nombre_estudiante = $nombre_estudiante_data['nombres'] . ' ' . $nombre_estudiante_data['apellidos'];
  }
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
    GROUP BY a.id, m.nombre, p.nombre, c.calificacion
  ");
  $stmt_asignaciones->bind_param("iiii", $id_momento, $id_estudiante, $id_nivel_estudio, $id_seccion);
  $stmt_asignaciones->execute();
  $result_asignaciones = $stmt_asignaciones->get_result();
  $asignaciones = $result_asignaciones->fetch_all(MYSQLI_ASSOC);
  $stmt_asignaciones->close();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <title>Asignaciones de <?php echo $nombre_estudiante; ?></title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-default@4/default.min.css" />
 <!--  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"> -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
  <div class="row mx-0 justify-content-center pb-5">
    <form class="card col-md-5 py-4" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
      <h2 class="card-title h3 text-center">Cargar Notas</h2>
      <?php if (isset($_SESSION['mensaje'])) : ?>
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
          <h3 class="card-title h4 text-center">Asignaciones de <?php echo $nombre_estudiante; ?></h3>
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
                  <td id="calificacion_<?php echo $asignacion['id']; ?>">
                    <?php echo $asignacion['calificacion'] ?? 'No registrada'; ?>
                  </td>
                  <td>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_<?php echo $asignacion['id']; ?>">Cargar Nota</button>
                  </td>
                </tr>
                <!-- Modal -->
                <div class="modal fade" id="modal_<?php echo $asignacion['id']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <form onsubmit="guardarCalificacion(event, <?php echo $asignacion['id']; ?>)">
                        <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLabel">Cargar Nota</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                          <input type="hidden" name="id_asignacion" value="<?php echo $asignacion['id']; ?>">
                          <input type="hidden" name="id_estudiante" value="<?php echo $id_estudiante; ?>">
                          <input type="hidden" name="id_momento" value="<?php echo $id_momento; ?>">
                          <div class="mb-3">
                            <label for="calificacion_input_<?php echo $asignacion['id']; ?>" class="form-label">Calificación</label>
                            <input type="number" class="form-control" id="calificacion_input_<?php echo $asignacion['id']; ?>" name="calificacion" step="0.01" min="0" max="20" required>
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                          <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  <?php endif; ?>

  <script>
    $(document).ready(function() {
      $('#id_nivel_estudio, #id_seccion').on('change', function() {
        var nivelEstudioId = $('#id_nivel_estudio').val();
        var seccionId = $('#id_seccion').val();

        if (nivelEstudioId && seccionId) {
          $.ajax({
            url: './Procesophp/obtener_estudiantes.php',
            type: 'POST',
            data: {
              id_nivel_estudio: nivelEstudioId,
              id_seccion: seccionId
            },
            success: function(response) {
              console.log(response); // Verifica la respuesta en la consola
              try {
                var result = typeof response === 'string' ? JSON.parse(response) : response;
                if (result.status === 'success') {
                  var estudiantes = result.data;
                  var options = '<option value="">Seleccione el estudiante</option>';
                  estudiantes.forEach(function(estudiante) {
                    options += `<option value="${estudiante.id}">${estudiante.nombre}</option>`;
                  });
                  $('#id_estudiante').html(options);
                } else {
                  Swal.fire({
                    title: 'Error',
                    text: result.message,
                    icon: 'error',
                    confirmButtonText: 'OK'
                  });
                }
              } catch (e) {
                console.error('Error al procesar la respuesta:', e);
                Swal.fire({
                  title: 'Error',
                  text: 'Ocurrió un error al procesar la respuesta.',
                  icon: 'error',
                  confirmButtonText: 'OK'
                });
              }
            },
            error: function(xhr, status, error) {
              console.error('Error en la solicitud AJAX:', error);
              Swal.fire({
                title: 'Error',
                text: 'Ocurrió un error al cargar los estudiantes.',
                icon: 'error',
                confirmButtonText: 'OK'
              });
            }
          });
        }
      });
    });

    function guardarCalificacion(event, asignacionId) {
      event.preventDefault();
      var form = event.target;
      var formData = new FormData(form);

      $.ajax({
        url: './Procesophp/guardar_calificacion.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
          try {
            var result = JSON.parse(response);
            if (result.status === 'success') {
              $('#modal_' + asignacionId).modal('hide'); // Cerrar la modal

              // Mostrar mensaje de éxito
              Swal.fire({
                title: 'Éxito',
                text: result.message,
                icon: 'success',
                confirmButtonText: 'OK'
              }).then(function() {
                // Recargar la página después de cerrar el mensaje
                window.location.reload();
              });

            } else {
              Swal.fire({
                title: 'Error',
                text: result.message,
                icon: 'error',
                confirmButtonText: 'OK'
              });
            }
          } catch (e) {
            Swal.fire({
              title: 'Error',
              text: 'Ocurrió un error al procesar la respuesta.',
              icon: 'error',
              confirmButtonText: 'OK'
            });
          }
        },
        error: function(xhr, status, error) {
          Swal.fire({
            title: 'Error',
            text: 'Ocurrió un error al guardar la calificación.',
            icon: 'error',
            confirmButtonText: 'OK'
          });
        }
      });
    }

    function limpiarFormulario() {
      document.getElementById('id_momento').value = '';
      document.getElementById('id_nivel_estudio').value = '';
      document.getElementById('id_seccion').value = '';
      document.getElementById('id_estudiante').innerHTML = '<option value="">Seleccione el estudiante</option>';
      // Limpiar la tabla de asignaciones si existe
      var tablaAsignaciones = document.querySelector('.card.col-md-8');
      if (tablaAsignaciones) {
        tablaAsignaciones.style.display = 'none';
      }
    }
  </script>
  <script src="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-default@4/default.min.js"></script>
  <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> -->
  <?php
  // Incluir pie de página
  include __DIR__ . '/partials/footer.php';
  ?>
</body>

</html>
