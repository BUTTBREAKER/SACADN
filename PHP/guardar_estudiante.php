<?php

require __DIR__."/conexion_be.php";

$resultado = null;

if($_POST) {
 $cedula = $_POST['cedula'];
 $nombre = $_POST['nombre'];
 $apellido = $_POST['apellido'];
 $fecha_nacimiento = $_POST['fecha_nacimiento'];
 $estado_nacimiento = $_POST['estado_nacimiento'];
 $lugar_nacimiento = $_POST['lugar_nacimiento'];
 $genero = $_POST['genero'];
 $fecha_registro = date("d/m/y");
 $id_representante= $_POST['id_representante'];

 $sql= "INSERT INTO estudiantes(cedula, nombre, apellido , fecha_nacimiento, estado_nacimiento,
 lugar_nacimiento, genero, id_representante)
 VALUES ('$cedula','$nombre','$apellido','$fecha_nacimiento','$estado_nacimiento','$lugar_nacimiento',
 '$genero', '$id_representante')";

 $resultado = mysqli_query($conexion,$sql);


 }
echo <<<HTML
<body>
  <link rel="stylesheet" href="../Assets/sweetalert2/borderless.min.css" />
  <script src="../Assets/sweetalert2/sweetalert2.min.js"></script>
  <script>
    Swal.fire({
      title: 'Estudiante Almacenado correctamente',
      icon: 'success',
      showConfirmButton: false,
      timer: 3000
    }).then(() => location.href = './Estudiantes.php')
  </script>
</body>
HTML;
