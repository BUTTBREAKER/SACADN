<?php
@session_start();

require_once __DIR__ . '/../../PHP/conexion_be.php';
$rol = $conexion
  ->query("SELECT rol FROM usuarios WHERE id={$_SESSION['usuario_id']}")
  ->fetch_assoc()['rol'];

// Usuario no autorizado
if ($rol === "U") {
  exit (<<<HTML
  <body>
    <link rel="stylesheet" href="../Assets/sweetalert2/borderless.min.css" />
    <script src="../Assets/sweetalert2/sweetalert2.min.js"></script>
    <script>
      Swal.fire({
        title: 'Acceso Denegado Solo Personal Autorizado',
        icon: 'warning',
        showConfirmButton: false,
        timer: 3000
      }).then(() => location.href = '../')
    </script>
  </body>
  HTML);
}
