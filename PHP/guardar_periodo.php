<?php

require __DIR__ . "/conexion_be.php";

$resultado = null;

if($_POST) {

 $anio_inicio = $_POST['anio_inicio'];


 $sql= "INSERT INTO `periodos`(`anio_inicio`) VALUES ('$anio_inicio')";
   
 $resultado = mysqli_query($conexion,$sql);
}
?>

echo <<<HTML
<body>
  <link rel="stylesheet" href="../Assets/sweetalert2/borderless.min.css" />
  <script src="../Assets/sweetalert2/sweetalert2.min.js"></script>
  <script>
    Swal.fire({
      title: 'Periodo registrado',
      icon: 'success',
      showConfirmButton: false,
      timer: 3000
    }).then(() => location.href = '../')
  </script>
</body>
 HTML;