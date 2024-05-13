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
    $stmt = $conexion->prepare("SELECT id, contrasena, rol, estado FROM usuarios WHERE usuario = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $stmt->store_result();

    // Inicializa las variables antes de su uso
    $id = null;
    $contrasena_hash = null;
    $rol = null;
    $estado = null;

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $contrasena_hash, $rol, $estado);
        $stmt->fetch();

        // Verifica si el usuario está activo
        if ($estado === 'activo') {
            // Verifica si la contraseña coincide con el hash almacenado
            if ($contrasena_hash !== null && password_verify($contrasena, $contrasena_hash)) {
                // Inicia la sesión
                $_SESSION['usuario_id'] = $id;

                // Redirecciona según el rol del usuario
                if ($rol === 'A') {
                    header("Location: BienvenidoA.php");
                } else {
                    header("Location: BienvenidoU.php");
                }
                exit;
            } else {
                // Las credenciales son incorrectas, muestra un mensaje de error
                mostrarMensajeError("Credenciales Incorrectas. Por favor, verifique su usuario y contraseña.", "credenciales_incorrectas");
            }
        } else {
            // El usuario está inactivo, muestra un mensaje de error
            mostrarMensajeError("Su usuario se encuentra inactivo. Por favor, comuníquese con su administrador.", "usuario_inactivo");
        }
    } else {
        // No se encontró ningún usuario con ese nombre, muestra un mensaje de error
        mostrarMensajeError("Usuario No Encontrado. Por favor, verifique su usuario.", "usuario_no_encontrado");
    }

    $stmt->close();
    $conexion->close();
}

// Función para mostrar un mensaje de error y redirigir al usuario a la página de inicio
function mostrarMensajeError(string $mensaje, string $tipoError): void {
    $titulo = '';
    switch ($tipoError) {
        case 'usuario_inactivo':
            $titulo = 'Usuario Inactivo';
            break;
        case 'credenciales_incorrectas':
            $titulo = 'Credenciales Incorrectas';
            break;
        case 'usuario_no_encontrado':
            $titulo = 'Usuario No Encontrado';
            break;
        default:
            $titulo = 'Error';
            break;
    }

    echo <<<HTML
    <body>
      <link rel="stylesheet" href="../assets/sweetalert2/borderless.min.css" />
      <script src="../assets/sweetalert2/sweetalert2.min.js"></script>
      <script>
        Swal.fire({
          title: '$titulo',
          text: '$mensaje',
          icon: 'error',
          showConfirmButton: false,
          timer: 5000
        }).then(() => location.href = './salir.php')
      </script>
    </body>
    HTML;
}
?>
