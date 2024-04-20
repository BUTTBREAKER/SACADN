<?php 

require 'con_db.php';

$resultado = null;

if($_POST) {

 $ci_est = $_POST['ci_est'];
 $nombre_completo = $_POST['nombre_completo'];
 $apellido = $_POST['apellido'];
 $fecha_nac = $_POST['fecha_nac'];
 $estado = $_POST['estado'];
 $lugar = $_POST['lugar'];
 $genero = $_POST['genero'];
 $telefono = $_POST['telefono'];
 $direccion = $_POST['direccion'];
 $fech_est = date("d/m/y");
 $ci_repr = $_POST['ci_repr']; 

 $sql= "INSERT INTO `estudiantes`(`ci_est`, `nombre_completo`, `apellido`, `fecha_nac`, `estado`, `lugar`, `genero`, `telefono`, `direccion`, `ci_repr`)
 VALUES ('$ci_est','$nombre_completo','$apellido','$fecha_nac','$estado','$lugar','$genero','$telefono','$direccion','$fech_est','$ci_repr')";
   
 $resultado = mysqli_query($conn,$sql);


 }
?>
echo <<<HTML
<body>
  <link rel="stylesheet" href="../assets/sweetalert2/borderless.min.css" />
  <script src="../assets/sweetalert2/sweetalert2.min.js"></script>
  <script>
    Swal.fire({
      title: 'Estudiante Almacenado correctamente',
      icon: 'success',
      showConfirmButton: false,
      timer: 3000
    }).then(() => location.href = './representantes.php')
  </script>
</body>
HTML;