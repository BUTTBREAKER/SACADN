<?php

$db = require __DIR__ . '/conexion_be.php';

<<<<<<< HEAD
$db->prepare('DELETE FROM estudiantes WHERE ci_est=?')
=======
$db->prepare('DELETE FROM representantes WHERE ci_repr=?')
>>>>>>> c580a3aae25a2245f2a4a5f4af4ca6962248e4d6
	->execute([$_GET['cedula']]);

echo <<<HTML
<body>
  <link rel="stylesheet" href="../assets/sweetalert2/borderless.min.css" />
  <script src="../assets/sweetalert2/sweetalert2.min.js"></script>
  <script>
    Swal.fire({
<<<<<<< HEAD
      title: 'Estudiante eliminado correctamente',
      icon: 'success',
      showConfirmButton: false,
      timer: 3000
    }).then(() => location.href = './Estudiantes.php')
=======
      title: 'Representante eliminado correctamente',
      icon: 'success',
      showConfirmButton: false,
      timer: 3000
    }).then(() => location.href = './representantes.php')
>>>>>>> c580a3aae25a2245f2a4a5f4af4ca6962248e4d6
  </script>
</body>
HTML;