<?php
//leemos el archivo del backup 
$consultas = file_get_contents(__DIR__ . "/../backups/full_backup.mysql.sql");

//nos conectamos a la base de datos 
$conexion = require __DIR__."/../conexion_be.php";
// ejecutamos las consultas
$conexion->multi_query($consultas);
//damos feeback al usuario
echo <<<HTML
  <body>
    <link rel="stylesheet" href="../../assets/sweetalert2/borderless.min.css" />
    <script src="../../assets/sweetalert2/sweetalert2.min.js"></script>
    <script>
      Swal.fire({
        title: 'Base  de Datos restaurada correctamente',
        icon: 'success',
        showConfirmButton: false,
        timer: 3000
      }).then(() => location.href = '../../')
    </script>
  </body>
  HTML;