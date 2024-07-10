<?php
// Clase Registro para el proceso de registro de usuarios
class Registro
{
  private $conexion;

  public function __construct($conexion)
  {
    $this->conexion = $conexion;
  }

  public function registrarUsuario($usuario, $nombre, $apellido, $cedula, $contrasena, $rol)
  {
    // Verificar si ya existe un usuario con el mismo nombre
    $query_check = "SELECT usuario FROM usuarios WHERE usuario = ?";
    $stmt_check = mysqli_prepare($this->conexion, $query_check);
    mysqli_stmt_bind_param($stmt_check, "s", $usuario);
    mysqli_stmt_execute($stmt_check);
    mysqli_stmt_store_result($stmt_check);
    $num_rows = mysqli_stmt_num_rows($stmt_check);
    mysqli_stmt_close($stmt_check);

    if ($num_rows > 0) {
      $this->mostrarMensajeError("El nombre de usuario ya está en uso.");
      return; // Detener el proceso de registro
    }



    // Insertar el nuevo usuario en la base de datos
    $contrasena_hash = password_hash($contrasena, PASSWORD_BCRYPT);

    $query_insert = "INSERT INTO usuarios (usuario, nombre, apellido, cedula, clave, rol)
                         VALUES (?, ?, ?, ?, ?, ?)";

    $stmt_insert = mysqli_prepare($this->conexion, $query_insert);

    if ($stmt_insert) {
      mysqli_stmt_bind_param($stmt_insert, "ssssss", $usuario, $nombre, $apellido, $cedula, $contrasena_hash, $rol);
      mysqli_stmt_execute($stmt_insert);
      mysqli_stmt_close($stmt_insert);
      mysqli_close($this->conexion);

      $this->mostrarMensajeExito("Usuario almacenado exitosamente");
    } else {
      $this->mostrarMensajeError("Error al almacenar el usuario. Por favor, intenta nuevamente más tarde.");
      error_log("Error al preparar la consulta: " . mysqli_error($this->conexion));
    }
  }

  private function mostrarMensajeExito(string $mensaje): void
  {
    echo <<<HTML
        <body>
          <link rel="stylesheet" href="../Assets/sweetalert2/borderless.min.css" />
          <script src="../Assets/sweetalert2/sweetalert2.min.js"></script>
          <script>
            Swal.fire({
              title: '$mensaje',
              icon: 'success',
              showConfirmButton: false,
              timer: 3000
            }).then(() => location.href = '../')
            </script>
        </body>
        HTML;
  }

  private function mostrarMensajeError(string $mensaje): void
  {
    echo <<<HTML
            <body>
                <link rel="stylesheet" href="../Assets/sweetalert2/borderless.min.css" />
                <script src="../Assets/sweetalert2/sweetalert2.min.js"></script>
             <script>
            Swal.fire({
              title: '$mensaje',
              icon: 'error',
              showConfirmButton: false,
              timer: 3000
            }).then(() => location.href = '../')
          </script>
        </body>
        HTML;
  }
}

// Uso de la clase Registro
include 'conexion_be.php'; // Asumiendo que la conexión se establece aquí

$registro = new Registro($conexion);

if (isset($_POST['usuario'], $_POST['nombre'], $_POST['apellido'], $_POST['cedula'], $_POST['clave'], $_POST['rol'])) {
  $usuario = $_POST['usuario'];
  $nombre = $_POST['nombre'];
  $apellido = $_POST['apellido'];
  $cedula = $_POST['cedula'];
  $contrasena = $_POST['clave'];
  $rol = $_POST['rol'];

  $registro->registrarUsuario($usuario, $nombre, $apellido, $cedula, $contrasena, $rol);
}
