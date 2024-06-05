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
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .contenedor {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: aliceblue;
            border-radius: 8px;
        }

        form {
            margin-top: 20px;
        }

        select,
        input[type="number"],
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="contenedor">
        <h2>Cargar Notas</h2>

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

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="id_periodo">Periodo:</label>
            <select name="id_periodo" id="id_periodo" required>
                <option value="" selected disabled>Seleccione un periodo</option>
                <?php foreach ($periodos as $periodo) : ?>
                    <option value="<?= $periodo['id'] ?>"><?= $periodo['anio_inicio'] ?></option>
                <?php endforeach; ?>
            </select>

            <label for="id_nivel_estudio">Nivel de Estudio:</label>
            <select name="id_nivel_estudio" id="id_nivel_estudio" required>
                <option value="" selected disabled>Seleccione un nivel de estudio</option>
                <?php foreach ($niveles_estudio as $nivel) : ?>
                    <option value="<?= $nivel['id'] ?>"><?= $nivel['nombre'] ?></option>
                <?php endforeach; ?>
            </select>

            <label for="id_seccion">Sección:</label>
            <select name="id_seccion" id="id_seccion" required>
                <option value="" selected disabled>Seleccione una sección</option>
                <!-- Las opciones se cargarán mediante AJAX -->
            </select>

            <label for="id_materia">Materia:</label>
            <select name="id_materia" id="id_materia" required>
                <option value="" selected disabled>Seleccione una materia</option>
                <?php foreach ($asignaciones as $asignacion) : ?>
                    <option value="<?= $asignacion['id'] ?>"><?= $asignacion['nombre_materia'] ?> (Profesor: <?= $asignacion['nombre_profesor'] ?>)</option>
                <?php endforeach; ?>
            </select>

            <label for="id_estudiante">Estudiante:</label>
            <select name="id_estudiante" id="id_estudiante" required>
                <option value="" selected disabled>Seleccione un estudiante</option>
                <!-- Las opciones se cargarán mediante AJAX -->
            </select>

            <label for="calificacion">Calificación:</label>
            <input type="number" name="calificacion" id="calificacion" min="0" max="20" required>

            <input type="submit" value="Cargar Nota">
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
    </script>

    <?php include __DIR__ . '/partials/footer.php' ?>
</body>

</html>

