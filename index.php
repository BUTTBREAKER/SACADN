<?php
  session_start();

  if (key_exists('usuario_id', $_SESSION)) {
    require_once __DIR__ . '/PHP/conexion_be.php';

    $rol = $conexion
      ->query("SELECT rol FROM usuarios WHERE id={$_SESSION['usuario_id']}")
      ->fetch_assoc()['rol'];

    header("Location: PHP/Bienbenido{$rol}.php");
    exit;
  }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SACADN</title>
    <link rel="icon" type="image/ico" href="./Sacadn.ico"/>
    <link rel="stylesheet" href="./Assets/style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</head>
<body>
    <div class="container" id="container">
        <div class="form-container register-container">
            <form id="registerForm" action="php/registro_usuario_be.php" method="POST" class="formulario__register">
                <h1>Regístrate aquí</h1>
                <div class="form-control">
                    <input id="registerUsuario" placeholder="Usuario" name="usuario" />
                    <small class="error-message"></small>
                    <span></span>
                </div>
                <div class="form-control">
                    <input id="registerNombreCompleto" placeholder="Nombre completo" name="nombre_completo" />
                    <small class="error-message"></small>
                    <span></span>
                </div>
                <div class="form-control">
                    <input id="registerCedula" placeholder="Cédula" name="cedula" />
                    <small class="error-message"></small>
                    <span></span>
                </div>
                <div class="form-control">
                    <input
                      type="password"
                      id="registerContrasena"
                      placeholder="Contraseña"
                      name="contrasena"
                      required
                      minlength="4"
                      maxlength="20"
                      pattern=".{4,20}"
                      title="La contraseña debe tener entre 4 y 20 caracteres."
                    />
                    <small class="error-message"></small>
                    <span></span>
                </div>
                <!-- Agregar campo oculto para el rol -->
                <input type="hidden" name="rol" id="rol" value="A"> <!-- Por defecto, se registra como administrador -->
                <button>Registrar</button>
            </form>
        </div>

        <div class="form-container login-container">
            <form id="loginForm" action="php/login_usuario_be.php" method="POST" class="formulario__login">
                <h1>Inicia sesión aquí</h1>
                <div class="form-control">
                    <input type="text" id="loginUsuario" placeholder="Usuario" name="usuario" />
                    <small class="error-message"></small>
                    <span></span>
                </div>
                <div class="form-control">
                    <input type="password" id="loginContrasena" placeholder="Contraseña" name="contrasena" />
                    <small class="error-message"></small>
                    <span></span>
                </div>
                <button type="submit" value="submit">Iniciar sesión</button>
            </form>
        </div>

        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-panel overlay-left">
                    <h1 class="title">Bienvenido <br /> de Nuevo</h1>
                    <p>Si tienes una cuenta, inicia sesión aquí y comienza a trabajar</p>
                    <button class="ghost" id="login">Iniciar sesión <i class="fa-solid fa-arrow-left"></i></button>
                </div>

                <div class="overlay-panel overlay-right">
                    <h1 class="title">Comienza tu <br /> viaje ahora</h1>
                    <p>Si aún no tienes una cuenta, únete a nosotros y comienza tu gestión</p>
                    <button class="ghost" id="register">Registrarse <i class="fa-solid fa-arrow-right"></i></button>
                </div>
            </div>
        </div>
    </div>
    <script src="./Assets/main.js"></script>

    <script>
        // Obtener el número de administradores actuales desde el backend (supongamos que se almacena en una variable PHP llamada $num_administradores)
        const numAdministradores = <?php echo $num_administradores ?>;

        // Supongamos que el límite de administradores permitidos es 5
        const limiteAdministradores = 5;

        // Determinar el rol deseado para el nuevo usuario
        let rolNuevoUsuario = 'A'; // Por defecto, se registra como administrador
        if (numAdministradores >= limiteAdministradores) {
            rolNuevoUsuario = 'U'; // Si se alcanza el límite, se registra como usuario normal
        }

        // Establecer el valor del campo oculto 'rol' en el formulario de registro
        document.getElementById('rol').value = rolNuevoUsuario;
    </script>
</body>
</html>
