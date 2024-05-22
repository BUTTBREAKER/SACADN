<?php
// Incluir archivos necesarios
include __DIR__ . '/partials/header.php';
require __DIR__ . "/Middlewares/autorizacion.php";
require_once __DIR__ . '/../vendor/autoload.php';

// Incluir archivo de conexión a la base de datos
require_once __DIR__ . '/conexion_be.php';

// Función para mostrar alerta SweetAlert2
function mostrarAlerta($titulo, $mensaje, $tipo)
{
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
$stmt_niveles = $conexion->prepare("SELECT id, nombre FROM niveles_estudio");
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
    <style>
        /* Estilos CSS */
        .contenedor {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
            background-color: aliceblue;
        }
    
        body {
            font-family: Arial, sans-serif;
        }

        form {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #f9f9f9;
        }

        input[type="text"],
        select {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
     </style>
</head>

<body>
    <div class="contenedor">
        <h2>Crear Nivel de Estudio y Sección</h2>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="nivel_estudio">Nivel de Estudio:</label>
            <select name="nivel_estudio_id" id="nivel_estudio" required>
                <option value="" selected disabled>Seleccione un nivel de estudio</option>
                <?php while ($row = $result_niveles->fetch_assoc()) : ?>
                    <option value="<?= $row['id'] ?>"><?= $row['nombre'] ?></option>
                <?php endwhile; ?>
            </select>

            <label for="nombre_seccion">Nombre de la Sección (una letra):</label>
            <input type="text" name="nombre_seccion" id="nombre_seccion" required maxlength="1">

            <label for="numero_matriculas">Número de Matrículas:</label>
            <input type="text" name="numero_matriculas" id="numero_matriculas" required>

            <input type="submit" value="Crear Sección">
        </form>
    </div>

    <script>
        // Función para mostrar alerta SweetAlert2
        function mostrarAlerta(titulo, mensaje, tipo)
        {
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
