<?php
// Verificar que solo los Administradores pueden acceder
include __DIR__ . '/partials/header.php';
require __DIR__ . "/Middlewares/autorizacion.php";

// Incluir archivo de conexión a la base de datos
require_once __DIR__ . '/conexion_be.php';

if ($_POST) {

  $nombre = $_POST['nombre'];

  $sql = "INSERT INTO `materias`(`nombre`)
  VALUES ('$nombre')";

  $resultado = mysqli_query($conexion, $sql);

  exit(<<<HTML
  <body>
    <link rel="stylesheet" href="../Assets/sweetalert2/borderless.min.css" />
    <script src="../Assets/sweetalert2/sweetalert2.min.js"></script>
    <script>
      Swal.fire({
        title: 'Materia Registrada Exitosamente',
        icon: 'success',
        showConfirmButton: false,
        timer: 3000
      }).then(() => location.href = './materias.php')
    </script>
  </body>
  HTML);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registrar Nueva Materia</title>
</head>

<body>
  <div class="row mx-0 justify-content-center pb-5">
    <form class="card col-md-4 py-4" action="nueva_materia.php" method="POST">
      <h1 class="card-title h3 text-center">Registrar Nueva Materia</h1>
      <!-- Aquí va tu formulario para registrar la nueva materia -->
      <div class="card-body row">
        <!-- Campos del formulario -->
        <div class="col-md-10 form-floating mb-3">
          <input class="form-control" type="text" id="nombre" name="nombre" placeholder=" " required>
          <label class="ms-2" for="nombre">Nombre de la Materia:</label>
        </div>
        <!-- Otros campos del formulario según tus requisitos -->

        <!-- Botón para enviar el formulario -->
        <button class="btn btn-success w-35" type="submit">Registrar Materia</button>
    </form>
  </div>
  </div>
</body>

</html>
<?php include __DIR__ . '/partials/footer.php' ?>
