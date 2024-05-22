<?php

$db = require __DIR__ . '/conexion_be.php';

$db->prepare('DELETE FROM representantes WHERE ci_repr=?')
	->execute([$_GET['cedula']]);

echo <<<HTML
<body>
  <link rel="stylesheet" href="../Assets/sweetalert2/borderless.min.css" />
  <script src="../Assets/sweetalert2/sweetalert2.min.js"></script>
  <script>
    Swal.fire({
      title: 'Representante eliminado correctamente',
      icon: 'success',
      showConfirmButton: false,
      timer: 3000
    }).then(() => location.href = './representantes.php')
  </script>
</body>
HTML;