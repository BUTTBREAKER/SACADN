<?php

require __DIR__ . "/conexion_be.php";

$resultado = null;

if ($_POST) {

  $cedula = $_POST['cedula'];
  $nombre = $_POST['nombre'];
  $apellido = $_POST['apellido'];
  $fecha_nacimiento = $_POST['fecha_nacimiento'];
  $estado_nacimiento = $_POST['estado_nacimiento'];
  $lugar_nacimiento = $_POST['lugar_nacimiento'];
  $genero = $_POST['genero'];
  $telefono = $_POST['telefono'];
  $direccion = $_POST['direccion'];
  $fecha_registro = date("d/m/y");

  $sql = "INSERT INTO profesores( cedula, nombre, apellido, fecha_nacimiento, estado_nacimiento,
  lugar_nacimiento, genero , telefono , direccion, fecha_registro)
 VALUES ('$cedula','$nombre','$apellido','$fecha_nacimiento','$estado_nacimiento',
  '$lugar_nacimiento','$genero','$telefono','$direccion','$fecha_registro')";

  $conexion->query($sql);
}

echo <<<HTML
<body>
  <link rel="stylesheet" href="../Assets/sweetalert2/borderless.min.css" />
  <script src="../Assets/sweetalert2/sweetalert2.min.js"></script>
  <script>
    Swal.fire({
      title: 'Profesor almacenado correctamente',
      icon: 'success',
      showConfirmButton: false,
      timer: 3000
    }).then(() => location.href = './Profesores.php')
  </script>
</body>
HTML;
