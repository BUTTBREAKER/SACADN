<?php
session_start();

// Verifica si se ha enviado el formulario de inicio de sesión
if (isset($_POST['usuario']) && isset($_POST['contrasena'])) {
    // Conexión a la base de datos (debes incluir tu propia lógica de conexión)
    require_once __DIR__ . '/conexion_be.php';

    // Recupera las credenciales del formulario
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];

    // Consulta a la base de datos para obtener las credenciales del usuario
    $stmt = $conexion->prepare("SELECT id, contrasena, rol FROM usuarios WHERE usuario = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $stmt->store_result();

    // Inicializa las variables antes de su uso
    $id = null;
    $contrasena_hash = null;
    $rol = null;

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $contrasena_hash, $rol);
        $stmt->fetch();

        // Verifica si la contraseña coincide con el hash almacenado
        if ($contrasena_hash !== null && password_verify($contrasena, $contrasena_hash)) {
            // Inicia la sesión
            $_SESSION['usuario_id'] = $id;

            // Redirecciona según el rol del usuario
            if ($rol === 'A') {
                header("Location: BienbenidoA.php");
            } else {
                header("Location: BienbenidoU.php");
            }
            exit;
        } else {
            // Las credenciales son incorrectas, muestra un mensaje de error
            mostrarMensajeError("Usuario o Contraseña Incorrecta");
        }
    } else {
        // No se encontró ningún usuario con ese nombre, muestra un mensaje de error
        mostrarMensajeError("Usuario o Contraseña Incorrecta");
    }

    $stmt->close();
    $conexion->close();
}

// Función para mostrar un mensaje de error y redirigir al usuario a la página de inicio
function mostrarMensajeError(string $mensaje): void {
  echo <<<HTML
  <body>
    <link rel="stylesheet" href="../assets/sweetalert2/borderless.min.css" />
    <script src="../assets/sweetalert2/sweetalert2.min.js"></script>
    <script>
      Swal.fire({
        title: '$mensaje',
        icon: 'error',
        showConfirmButton: false,
        timer: 3000
      }).then(() => location.href = './salir.php')
    </script>
  </body>
  HTML;
}
?> 
