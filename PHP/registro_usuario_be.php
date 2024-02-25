<?php
// Clase Registro para el proceso de registro de usuarios
class Registro {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function registrarUsuario($usuario, $nombre_completo, $cedula, $contrasena, $rol) {
        // Verificar si ya existe un usuario con el mismo nombre
        $query_check = "SELECT Usuario FROM usuarios WHERE Usuario = ?";
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

        // Obtener el número de administradores actuales desde la base de datos
        $query_admins = "SELECT COUNT(*) AS num_administradores FROM usuarios WHERE rol = 'A'";
        $result_admins = mysqli_query($this->conexion, $query_admins);
        $row_admins = mysqli_fetch_assoc($result_admins);
        $numAdministradores = (int) $row_admins['num_administradores'];
        mysqli_free_result($result_admins);

        // Supongamos que el límite de administradores es x
        $limiteAdministradores = 5;

        // Determinar el rol del nuevo usuario
        if ($numAdministradores < $limiteAdministradores) {
            $rol = 'A'; // Si hay menos de x administradores, asignar como administrador
        } else {
            $rol = 'U'; // Si se alcanza o supera el límite, asignar como usuario normal
        }

        // Insertar el nuevo usuario en la base de datos
        $contrasena_hash = password_hash($contrasena, PASSWORD_BCRYPT);

        $query_insert = "INSERT INTO usuarios (Usuario, nombre_completo, Cedula, contrasena, rol) 
                         VALUES (?, ?, ?, ?, ?)";
        
        $stmt_insert = mysqli_prepare($this->conexion, $query_insert);

        if ($stmt_insert) {
            mysqli_stmt_bind_param($stmt_insert, "sssss", $usuario, $nombre_completo, $cedula, $contrasena_hash, $rol);
            mysqli_stmt_execute($stmt_insert);
            mysqli_stmt_close($stmt_insert);
            mysqli_close($this->conexion);

            $this->mostrarMensajeExito("Usuario almacenado exitosamente");
        } else {
            $this->mostrarMensajeError("Error al almacenar el usuario. Por favor, intenta nuevamente más tarde.");
            error_log("Error al preparar la consulta: " . mysqli_error($this->conexion));
        }
    }

    private function mostrarMensajeExito($mensaje) {
        echo '
        <script>
            alert("' . $mensaje . '");
            window.location = "../index.php";
        </script>';
    }

    private function mostrarMensajeError($mensaje) {
        echo '
        <script>
            alert("' . $mensaje . '");
            window.location = "../index.php";
        </script>';
    }
}

// Uso de la clase Registro
include 'conexion_be.php'; // Asumiendo que la conexión se establece aquí

$registro = new Registro($conexion);

if (isset($_POST['usuario'], $_POST['nombre_completo'], $_POST['cedula'], $_POST['contrasena'])) {
    $usuario = $_POST['usuario'];
    $nombre_completo = $_POST['nombre_completo'];
    $cedula = $_POST['cedula'];
    $contrasena = $_POST['contrasena'];
    $rol = ''; // El rol se asignará automáticamente en la función registrarUsuario

    $registro->registrarUsuario($usuario, $nombre_completo, $cedula, $contrasena, $rol);
}

$conexion->close();
?>
