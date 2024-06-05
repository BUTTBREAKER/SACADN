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

// Obtener los estudiantes, niveles de estudio y secciones
$estudiantes_result = $conexion->query('SELECT id, nombre, apellido FROM estudiantes');
$estudiantes = $estudiantes_result->fetch_all(MYSQLI_ASSOC);

$niveles_result = $conexion->query('SELECT id, nombre FROM niveles_estudio');
$niveles = $niveles_result->fetch_all(MYSQLI_ASSOC);

$secciones_result = $conexion->query('SELECT id, nombre FROM secciones');
$secciones = $secciones_result->fetch_all(MYSQLI_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $id_estudiante = $_POST['id_estudiante'];
  $id_nivel_estudio = $_POST['id_nivel_estudio'];
  $id_seccion = $_POST['id_seccion'];

  $stmt_asignacion = $conexion->prepare("INSERT INTO asignaciones_estudiantes (id_estudiante, id_nivel_estudio, id_seccion) VALUES (?, ?, ?)");
  $stmt_asignacion->bind_param("iii", $id_estudiante, $id_nivel_estudio, $id_seccion);

  try {
    $stmt_asignacion->execute();
    $mensaje = "Estudiante asignado correctamente a su nivel y sección.";
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
  <title>Asignar Nivel y Sección a Estudiante</title>
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
    <h2>Asignar Nivel y Sección a Estudiante</h2>

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
      <label for="id_estudiante">Estudiante:</label>
      <select name="id_estudiante" id="id_estudiante" required>
        <option value="" selected disabled>Seleccione un estudiante</option>
        <?php foreach ($estudiantes as $estudiante) : ?>
          <option value="<?= $estudiante['id'] ?>"><?= $estudiante['nombre'] . " " . $estudiante['apellido'] ?></option>
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
        <?php foreach ($secciones as $seccion) : ?>
          <option value="<?= $seccion['id'] ?>"><?= $seccion['nombre'] ?></option>
        <?php endforeach; ?>
      </select>

      <input type="submit" value="Asignar Nivel y Sección">
    </form>
  </div>
</body>

</html>
<?php include __DIR__ . '/partials/footer.php' ?>
