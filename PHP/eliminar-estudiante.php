<?php

$db = require __DIR__ . '/conexion_be.php';

$db->prepare('DELETE FROM estudiantes WHERE ci_est=?')
	->execute([$_GET['cedula']]);

echo <<<HTML
<body>
  <link rel="stylesheet" href="../assets/sweetalert2/borderless.min.css" />
  <script src="../assets/sweetalert2/sweetalert2.min.js"></script>
  <script>
    Swal.fire({
      title: 'Estudiante eliminado correctamente',
      icon: 'success',
      showConfirmButton: false,
      timer: 3000
    }).then(() => location.href = './Estudiantes.php')
  </script>
</body>
HTML;