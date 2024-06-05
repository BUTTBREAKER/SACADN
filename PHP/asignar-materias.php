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

$periodos_result = $conexion->query('SELECT id, anio_inicio FROM periodos');
$periodos = $periodos_result->fetch_all(MYSQLI_ASSOC);

$niveles_result = $conexion->query('SELECT id, nombre FROM niveles_estudio');
$niveles = $niveles_result->fetch_all(MYSQLI_ASSOC);

$secciones_result = $conexion->query('SELECT id, nombre FROM secciones');
$secciones = $secciones_result->fetch_all(MYSQLI_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_profesor = $_POST['id_profesor'];
    $id_materia = $_POST['id_materia'];
    $id_periodo = $_POST['id_periodo'];
    $id_nivel_estudio = $_POST['id_nivel_estudio'];
    $id_seccion = $_POST['id_seccion'];

    $stmt_asignacion = $conexion->prepare("INSERT INTO asignaciones (id_profesor, id_materia, id_periodo, id_nivel_estudio, id_seccion) VALUES (?, ?, ?, ?, ?)");
    $stmt_asignacion->bind_param("iiiii", $id_profesor, $id_materia, $id_periodo, $id_nivel_estudio, $id_seccion);

    try {
        $stmt_asignacion->execute();
        $mensaje = "Asignación creada correctamente.";
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
    <title>Asignar Materias a Profesores</title>
    <link rel="stylesheet" href="../Assets/sweetalert2/borderless.min.css" />
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
        <h2>Asignar Materias a Profesores</h2>

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

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="id_profesor">Profesor:</label>
            <select name="id_profesor" id="id_profesor" required>
                <option value="" selected disabled>Seleccione un profesor</option>
                <?php foreach ($profesores as $profesor) : ?>
                    <option value="<?= $profesor['id'] ?>"><?= $profesor['nombre'] . " " . $profesor['apellido'] ?></option>
                <?php endforeach; ?>
            </select>

            <label for="id_materia">Materia:</label>
            <select name="id_materia" id="id_materia" required>
                <option value="" selected disabled>Seleccione una materia</option>
                <?php foreach ($materias as $materia) : ?>
                    <option value="<?= $materia['id'] ?>"><?= $materia['nombre'] ?></option>
                <?php endforeach; ?>
            </select>

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
                <?php foreach ($niveles as $nivel) : ?>
                    <option value="<?= $nivel['id'] ?>"><?= $nivel['nombre'] ?></option>
                <?php endforeach; ?>
            </select>

            <label for="id_seccion">Sección:</label>
            <select name="id_seccion" id="id_seccion" required>
                <option value="" selected disabled>Seleccione una sección</option>
            </select>

            <input type="submit" value="Asignar Materia">
        </form>
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
                        seccionSelect.innerHTML = '<option value="" selected disabled>Seleccione una sección</option>';
                        secciones.forEach(seccion => {
                            seccionSelect.innerHTML += `<option value="${seccion.id}">${seccion.nombre}</option>`;
                        });
                    })
                    .catch(error => console.error('Error al cargar secciones:', error));
            }
        }
    </script>

    <?php include __DIR__ . '/partials/footer.php' ?>
</body>

</html>
