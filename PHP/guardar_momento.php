<?php

require __DIR__ . "/conexion_be.php";

$resultado = null;

if($_POST) {
  $numero_momento = $_POST['numero_momento'];
  $mes_inicio = $_POST['mes_inicio'];
  $dia_inicio = $_POST['dia_inicio'];
  $id_periodo = $_POST['id_periodo'];
  
  $sql= "INSERT INTO `momentos`(`numero_momento`, `mes_inicio`, `dia_inicio`, `id_periodo`) 
  VALUES ('$numero_momento','$mes_inicio','$dia_inicio','$id_periodo')";

  $resultado = mysqli_query($conexion, $sql);

}
?>

echo <<<HTML
<body>
  <link rel="stylesheet" href="../Assets/sweetalert2/borderless.min.css" />
  <script src="../Assets/sweetalert2/sweetalert2.min.js"></script>
  <script>
    Swal.fire({
      title: 'Momento registrado',
      icon: 'success',
      showConfirmButton: false,
      timer: 3000
    }).then(() => location.href = '../')
  </script>
</body>
 HTML;