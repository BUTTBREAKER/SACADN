<?php 

require __DIR__."/conexion_be.php";

$resultado = null;

if($_POST) {

 $ci_prof = $_POST['ci_prof'];
 $nombre_completo = $_POST['nombre_completo'];
 $apellido = $_POST['apellido'];
 $fecha_nac = $_POST['fecha_nac'];
 $estado = $_POST['estado'];
 $lugar = $_POST['lugar'];
 $genero = $_POST['genero'];
 $telefono = $_POST['telefono'];
 $direccion = $_POST['direccion'];
 $fech_prof = date("d/m/y");  

 $sql= "INSERT INTO `profesores`(`ci_prof`, `nombre_completo`, `apellido`, `fecha_nac`, `estado`, `lugar`, `genero`, `telefono`, `direccion`, `fech_prof`)
 VALUES ('$ci_prof','$nombre_completo','$apellido','$fecha_nac','$estado','$lugar','$genero','$telefono','$direccion','$fech_prof')";
   
 $resultado = mysqli_query($conexion,$sql);


 }
echo <<<HTML
<body>
  <link rel="stylesheet" href="../assets/sweetalert2/borderless.min.css" />
  <script src="../assets/sweetalert2/sweetalert2.min.js"></script>
  <script>
    Swal.fire({
      title: 'Profesor Almacenado correctamente',
      icon: 'success',
      showConfirmButton: false,
      timer: 3000
    }).then(() => location.href = './Profesores.php')
  </script>
</body>
HTML;
