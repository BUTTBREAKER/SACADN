<?php
// Incluir archivos necesarios
include __DIR__ . '/partials/header.php';
require __DIR__ . "/Middlewares/autorizacion.php";
require_once __DIR__ . '/../vendor/autoload.php';

// Incluir archivo de conexión a la base de datos
require_once __DIR__ . '/conexion_be.php';

// Inicializar variable para la alerta
$alerta = "";

// Obtener los niveles de estudio existentes
$stmt_niveles = $conexion->prepare("SELECT id, nombre FROM niveles_estudio ORDER BY id");
$stmt_niveles->execute();
$result_niveles = $stmt_niveles->get_result();
$stmt_niveles->close();

// Obtener el periodo activo
$stmt_periodo_activo = $conexion->prepare("SELECT id, anio_inicio FROM periodos WHERE estado = 'activo' LIMIT 1");
$stmt_periodo_activo->execute();
$result_periodo_activo = $stmt_periodo_activo->get_result();
$periodo_activo = $result_periodo_activo->fetch_assoc();
$stmt_periodo_activo->close();

// Verificar si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $nivel_estudio_id = $_POST['nivel_estudio_id'] ?? null;
    $nombre_seccion = $_POST['nombre_seccion'] ?? null;
    $numero_matriculas = $_POST['numero_matriculas'] ?? null;
    $periodo_id = $periodo_activo['id'];

    // Validar que todos los campos requeridos estén presentes
    if (!$nivel_estudio_id || !$nombre_seccion || !$numero_matriculas) {
        $alerta = "mostrarAlerta('Error', 'Todos los campos son obligatorios.', 'error');";
    } elseif (!preg_match('/^[A-Za-z]$/', $nombre_seccion)) {
        $alerta = "mostrarAlerta('Error', 'El nombre de la sección debe ser una sola letra.', 'error');";
    } else {
        // Verificar si ya existe una sección con el mismo nivel de estudio y nombre de sección
        $stmt_verificar_seccion = $conexion->prepare("SELECT id FROM secciones WHERE id_nivel_estudio = ? AND nombre = ?");
        $stmt_verificar_seccion->bind_param("is", $nivel_estudio_id, $nombre_seccion);
        $stmt_verificar_seccion->execute();
        $result_verificar_seccion = $stmt_verificar_seccion->get_result();
        $stmt_verificar_seccion->close();

        if ($result_verificar_seccion->num_rows > 0) {
            $alerta = "mostrarAlerta('Error', 'Ya existe una sección con el mismo nivel de estudio y nombre de sección.', 'error');";
        } else {
            // Insertar la sección
            $stmt_seccion = $conexion->prepare("INSERT INTO secciones (nombre, id_nivel_estudio, numero_matriculas, id_periodo) VALUES (?, ?, ?, ?)");
            $stmt_seccion->bind_param("siii", $nombre_seccion, $nivel_estudio_id, $numero_matriculas, $periodo_id);
            if ($stmt_seccion->execute()) {
                // Mostrar alerta de éxito
                $alerta = "mostrarAlerta('Éxito', 'Sección creada correctamente.', 'success');";
            } else {
                // Mostrar alerta de error si falla la inserción
                $alerta = "mostrarAlerta('Error', 'No se pudo crear la sección.', 'error');";
            }
            $stmt_seccion->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Nivel de Estudio y Sección</title>
    <!-- Enlaces de estilo y script de SweetAlert2 -->
    <link rel="stylesheet" href="../Assets/sweetalert2/borderless.min.css" />
    <script src="../Assets/sweetalert2/sweetalert2.min.js"></script>
</head>

<body>
    <div class="row mx-0 justify-content-center pb-5">
        <form class="card col-md-5 py-4" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <h2 class="card-title h3 text-center">Crear Nivel de Estudio y Sección</h2>

            <div class="col-md-12 form-floating mb-3">
                <select name="nivel_estudio_id" class="form-select" id="nivel_estudio" required>
                    <option selected disabled>Seleccione un nivel de estudio</option>
                    <?php while ($row = $result_niveles->fetch_assoc()) : ?>
                        <option value="<?= $row['id'] ?>"><?= $row['nombre'] ?></option>
                    <?php endwhile; ?>
                </select>
                <label for="nivel_estudio">Nivel de Estudio:</label>
            </div>

            <div class="col-md-12 form-floating mb-3">
                <input type="hidden" name="periodo_id" value="<?= $periodo_activo['id'] ?>">
                <input class="form-control" type="text" value="<?= $periodo_activo['anio_inicio'] ?>" readonly>
                <label for="periodo_id">Período Activo:</label>
            </div>

            <div class="col-md-12 form-floating mb-3">
                <input class="form-control" type="text" name="nombre_seccion" id="nombre_seccion" required pattern="[A-Za-z]" title="El nombre de la sección debe ser una sola letra.">
                <label for="nombre_seccion">Nombre de la Sección (una letra):</label>
            </div>

            <div class="col-md-12 form-floating mb-3">
                <input class="form-control" type="number" name="numero_matriculas" id="numero_matriculas" required>
                <label for="numero_matriculas">Número de Matrículas:</label>
            </div>

            <div class="btn-group btn-group-lg mx-3">
                <button class="btn btn-success w-75" type="submit">Crear Seccion</button>
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
                confirmButtonText: 'Aceptar',
                width: '400px', // Ajustar el ancho del cuadro de alerta
                padding: '1em', // Ajustar el padding del cuadro de alerta
                customClass: {
                    popup: 'my-swal-popup' // Agregar clase personalizada
                }
            });
        }

        // Ejecutar la alerta si está definida
        <?php if ($alerta) : ?>
            <?= $alerta ?>
        <?php endif; ?>
    </script>

    <style>
        /* Estilos personalizados para el cuadro de alerta */
        .my-swal-popup {
            border-radius: 10px; /* Bordes redondeados */
        }
    </style>
</body>

</html>
<?php include __DIR__ . '/partials/footer.php'; ?>
