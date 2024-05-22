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
  <style>
    /* Estilos CSS */
    .contenedor {
      max-width: 500px;
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

    button[type="submit"] {
      padding: 0.7em 1.7em;
      font-size: 15px;
      border-radius: 0.5em;
      cursor: pointer;
      border: 1px solid #e8e8e8;
      transition: all 0.3s;
      box-shadow: 6px 6px 12px #c5c5c5, -6px -6px 12px #ffffff;
      color: white;
      font-weight: 700;
      background: rgb(0, 255, 0, .7);
    }

    button[type="submit"]:hover {
      border: 1px solid rgb(0, 255, 0);
      ;
    }
  </style>
</head>

<body>
  <div class="contenedor">
    <h1>Registrar Nueva Materia</h1>

    <!-- Aquí va tu formulario para registrar la nueva materia -->

    <form action="nueva_materia.php" method="POST">
      <!-- Campos del formulario -->
      <label for="nombre">Nombre de la Materia:</label>
      <input type="text" id="nombre" name="nombre" required>
      <!-- Otros campos del formulario según tus requisitos -->

      <!-- Botón para enviar el formulario -->
      <button type="submit">Registrar Materia</button>
    </form>
  </div>
</body>

</html>
