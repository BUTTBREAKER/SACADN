<?php 

require __DIR__."/conexion2.php";

$resultado = null;

if($_POST) {

 $ID_per= $_POST['ID_per'];
 $nombre= $_POST['nombre'];
 $fech_per = date("d/m/y");  

 $sql= "INSERT INTO `periodos`( `ID_per`, `nombre`,`fech_per`)
 VALUES ( '$ID_per','$nombre','$fech_per')";
   
 $resultado = mysqli_query($conn,$sql);
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
    }).then(() => location.href = './Periodos.php')
  </script>
</body>
 HTML;