<?php

$db = require __DIR__ . '/conexion_be.php';

$db->prepare('DELETE FROM profesores WHERE ci_prof=?')
	->execute([$_GET['cedula']]);

echo <<<HTML
<body>
  <link rel="stylesheet" href="../assets/sweetalert2/borderless.min.css" />
  <script src="../assets/sweetalert2/sweetalert2.min.js"></script>
  <script>
    Swal.fire({
      title: 'Profesor eliminado correctamente',
      icon: 'success',
      showConfirmButton: false,
      timer: 3000
    }).then(() => location.href = './Profesores.php')
  </script>
</body>
HTML;