<?php 

require __DIR__."/conexion_be.php";

$resultado = null;

if($_POST) {

 $ci_repr = $_POST['ci_repr'];
 $nombre_completo = $_POST['nombre_completo'];
 $apellido = $_POST['apellido'];
 $fecha_nac = $_POST['fecha_nac'];
 $estado = $_POST['estado'];
 $lugar = $_POST['lugar'];
 $genero = $_POST['genero'];
 $telefono = $_POST['telefono'];
 $direccion = $_POST['direccion'];
 $fech_repr = date("d/m/y");  

 $sql= "INSERT INTO `representantes`(`ci_repr`, `nombre_completo`, `apellido`, `fecha_nac`, `estado`, `lugar`, `genero`, `telefono`, `direccion`, `fech_repr`)
 VALUES ('$ci_repr','$nombre_completo','$apellido','$fecha_nac','$estado','$lugar','$genero','$telefono','$direccion','$fech_repr')";
   
 $resultado = mysqli_query($conexion,$sql);


 }
echo <<<HTML
<body>
  <link rel="stylesheet" href="../Assets/sweetalert2/borderless.min.css" />
  <script src="../Assets/sweetalert2/sweetalert2.min.js"></script>
  <script>
    Swal.fire({
      title: 'Representante Almacenado correctamente',
      icon: 'success',
      showConfirmButton: false,
      timer: 3000
    }).then(() => location.href = './representantes.php')
  </script>
</body>
HTML;
