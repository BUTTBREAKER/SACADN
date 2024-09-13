<?php
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
$periodo_activo = $stmt_periodo_activo->get_result()->fetch_assoc();
$stmt_periodo_activo->close();

// Manejo del formulario
$mensaje = "";
$estudiante = null;
$inscrito_en_periodo_activo = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['buscar'])) {
        $cedula = $_POST['cedula'];

        // Buscar estudiante por cédula
        $stmt_estudiante = $conexion->prepare("
            SELECT e.id, e.nombres, e.apellidos, s.nombre AS seccion, s.id AS id_seccion, ne.id AS id_nivel_estudio, ne.nombre AS nivel_estudio, p.anio_inicio AS periodo_anterior
            FROM estudiantes e
            JOIN inscripciones i ON e.id = i.id_estudiante
            JOIN secciones s ON i.id_seccion = s.id
            JOIN niveles_estudio ne ON s.id_nivel_estudio = ne.id
            JOIN periodos p ON i.id_periodo = p.id
            WHERE e.cedula = ? AND p.estado = 'inactivo'
        ");
        $stmt_estudiante->bind_param('i', $cedula);
        $stmt_estudiante->execute();
        $estudiante = $stmt_estudiante->get_result()->fetch_assoc();
        $stmt_estudiante->close();

        if ($estudiante) {
            // Verificar si el estudiante ya está inscrito en el periodo activo
            $stmt_verificar_periodo_activo = $conexion->prepare("
                SELECT COUNT(*) as inscrito_en_periodo_activo
                FROM inscripciones
                WHERE id_estudiante = ? AND id_periodo = ?
            ");
            $stmt_verificar_periodo_activo->bind_param("ii", $estudiante['id'], $periodo_activo['id']);
            $stmt_verificar_periodo_activo->execute();
            $result_verificar_periodo_activo = $stmt_verificar_periodo_activo->get_result()->fetch_assoc();
            $inscrito_en_periodo_activo = $result_verificar_periodo_activo['inscrito_en_periodo_activo'] > 0;
            $stmt_verificar_periodo_activo->close();

            if ($inscrito_en_periodo_activo) {
                $mensaje = "El estudiante ya está inscrito en el periodo activo.";
            }
        } else {
            $mensaje = "Estudiante no encontrado.";
        }
    } elseif (isset($_POST['promover'])) {
        $id_estudiante = $_POST['id_estudiante'];
        $id_nivel_actual = $_POST['id_nivel_actual'];
        $seccion_actual = $_POST['seccion_actual'];

        // Obtener el siguiente nivel de estudio
        $stmt_nivel_siguiente = $conexion->prepare("
            SELECT id, nombre
            FROM niveles_estudio
            WHERE id = ?
        ");
        $siguiente_nivel_id = $id_nivel_actual + 1;
        $stmt_nivel_siguiente->bind_param("i", $siguiente_nivel_id);
        $stmt_nivel_siguiente->execute();
        $nivel_siguiente = $stmt_nivel_siguiente->get_result()->fetch_assoc();
        $stmt_nivel_siguiente->close();

        if ($nivel_siguiente) {
            // Obtener la sección correspondiente en el siguiente nivel
            $stmt_seccion_siguiente = $conexion->prepare("
                SELECT id, nombre
                FROM secciones
                WHERE id_nivel_estudio = ? AND nombre = ?
            ");
            $stmt_seccion_siguiente->bind_param("is", $nivel_siguiente['id'], $seccion_actual);
            $stmt_seccion_siguiente->execute();
            $seccion_siguiente = $stmt_seccion_siguiente->get_result()->fetch_assoc();
            $stmt_seccion_siguiente->close();

            if ($seccion_siguiente) {
                // Inscribir al estudiante en el siguiente nivel
                $stmt_inscripcion = $conexion->prepare("
                    INSERT INTO inscripciones (id_estudiante, id_seccion, id_periodo, fecha_registro)
                    VALUES (?, ?, ?, NOW())
                ");
                $stmt_inscripcion->bind_param("iii", $id_estudiante, $seccion_siguiente['id'], $periodo_activo['id']);
                $stmt_inscripcion->execute();
                $mensaje = "Estudiante promovido e inscrito en el siguiente nivel.";
                $inscrito_en_periodo_activo = true;
                $stmt_inscripcion->close();
            } else {
                $mensaje = "No se encontró una sección correspondiente en el siguiente nivel.";
            }
        } else {
            $mensaje = "No se encontró un siguiente nivel de estudio.";
        }
    } elseif (isset($_POST['inscribir'])) {
        $id_estudiante = $_POST['id_estudiante'];
        $id_seccion = $_POST['id_seccion'];

        // Inscribir al estudiante manualmente
        $stmt_inscripcion = $conexion->prepare("
            INSERT INTO inscripciones (id_estudiante, id_seccion, id_periodo, fecha_registro)
            VALUES (?, ?, ?, NOW())
        ");
        $stmt_inscripcion->bind_param("iii", $id_estudiante, $id_seccion, $periodo_activo['id']);
        $stmt_inscripcion->execute();
        $mensaje = "Estudiante Reinscrito Correste en el periodo activo.";
        $inscrito_en_periodo_activo = true;
        $stmt_inscripcion->close();
    }
}

// Obtener todos los niveles de estudio y secciones para el formulario manual
$stmt_niveles = $conexion->prepare("SELECT id, nombre FROM niveles_estudio");
$stmt_niveles->execute();
$niveles_estudio = $stmt_niveles->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt_niveles->close();

$secciones = [];
foreach ($niveles_estudio as $nivel) {
    $stmt_secciones = $conexion->prepare("SELECT id, nombre FROM secciones WHERE id_nivel_estudio = ?");
    $stmt_secciones->bind_param("i", $nivel['id']);
    $stmt_secciones->execute();
    $secciones[$nivel['id']] = $stmt_secciones->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt_secciones->close();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reinscripción de Estudiantes</title>
    <link rel="stylesheet" href="../Assets/sweetalert2/borderless.min.css" />
    <script src="../Assets/sweetalert2/sweetalert2.min.js"></script>
</head>

<body>
    <div class="row mx-0 justify-content-center pb-5">
        <?php if (!empty($mensaje)) : ?>
            <script>
                Swal.fire({
                    title: 'Información',
                    text: '<?php echo $mensaje; ?>',
                    icon: 'info',
                    confirmButtonText: 'OK'
                });
            </script>
        <?php endif; ?>

        <?php if (!$estudiante || ($estudiante && $inscrito_en_periodo_activo)) : ?>
            <form class="card col-md-5 py-4" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <h2 class="card-title h3 text-center">Buscar Estudiante</h2>
                <div class="col-md-12 form-floating mb-3">
                    <input type="text" name="cedula" class="form-control" required>
                    <label for="cedula">Cédula del Estudiante:</label>
                </div>
                <div class="btn-group btn-group-lg mx-6">
                    <button class="btn btn-success w-75" name="buscar">Buscar Estudiante</button>
                </div>
            </form>
        <?php elseif ($estudiante && !$inscrito_en_periodo_activo) : ?>
            <script>
                Swal.fire({
                    title: '¿Promover al siguiente nivel?',
                    text: '<?= htmlspecialchars($estudiante['nombres'] . ' ' . $estudiante['apellidos']) . " cursó " . $estudiante['nivel_estudio'] . " Sección " . $estudiante['seccion'] . " en el periodo " . $estudiante['periodo_anterior'] ."-" . ($estudiante['periodo_anterior'] + 1)?>',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Promover',
                    cancelButtonText: 'No Promover'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('promover-form').submit();
                    } else {
                        document.getElementById('inscripcion-form').style.display = 'block';
                    }
                });
            </script>
            <form id="promover-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" style="display:none;">
                <input type="hidden" name="promover" value="1">
                <input type="hidden" name="id_estudiante" value="<?= $estudiante['id'] ?>">
                <input type="hidden" name="id_nivel_actual" value="<?= $estudiante['id_nivel_estudio'] ?>">
                <input type="hidden" name="seccion_actual" value="<?= $estudiante['seccion'] ?>">
            </form>

            <form id="inscripcion-form" class="card col-md-5 py-4" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" style="display:none;">
                <h2 class="card-title h3 text-center">Inscripción Manual</h2>
                <input type="hidden" name="id_estudiante" value="<?php echo $estudiante['id']; ?>">
                <div class="col-md-12 form-floating mb-3">
                    <input type="text" class="form-control" value="<?php echo $estudiante['nombres'] . ' ' . $estudiante['apellidos']; ?>" readonly>
                    <label>Estudiante:</label>
                </div>
                <div class="col-md-12 form-floating mb-3">
                    <select name="id_nivel_estudio" class="form-select" id="id_nivel_estudio" required>
                        <option value="" selected disabled>Seleccione un nivel de estudio</option>
                        <?php foreach ($niveles_estudio as $nivel) : ?>
                            <option value="<?php echo $nivel['id']; ?>"><?php echo $nivel['nombre']; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <label>Nivel de Estudio:</label>
                </div>
                <div class="col-md-12 form-floating mb-3">
                    <select name="id_seccion" class="form-select" id="id_seccion" required>
                        <option value="" selected disabled>Seleccione una sección</option>
                    </select>
                    <label>Sección:</label>
                </div>
                <div class="btn-group btn-group-lg mx-6">
                    <button class="btn btn-success w-75" name="inscribir">Inscribir</button>
                    <a href="javascript:history.back()" class="btn btn-outline-secondary">Regresar</a>
                </div>
            </form>

            <script>
                const niveles = <?php echo json_encode($niveles_estudio); ?>;
                const secciones = <?php echo json_encode($secciones); ?>;
                const nivelSelect = document.getElementById('id_nivel_estudio');
                const seccionSelect = document.getElementById('id_seccion');

                nivelSelect.addEventListener('change', function() {
                    const nivelId = this.value;
                    seccionSelect.innerHTML = '<option value="" selected disabled>Seleccione una sección</option>';
                    if (secciones[nivelId]) {
                        secciones[nivelId].forEach(function(seccion) {
                            const option = document.createElement('option');
                            option.value = seccion.id;
                            option.textContent = seccion.nombre;
                            seccionSelect.appendChild(option);
                        });
                    }
                });
            </script>
        <?php endif; ?>
    </div>
</body>

</html>

<?php include __DIR__ . '/partials/footer.php' ?>
