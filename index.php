<?php

  require_once __DIR__ . '/vendor/autoload.php';
  require_once __DIR__ . '/app/bootstrap.php';

  updateErrorsUrls(__DIR__ . '/.htaccess', 'views/errors');

  // Iniciar una sesión para manejar la autenticación de usuario
  session_start();

  if (key_exists('usuario_id', $_SESSION)) {

    // Requerir el archivo de conexión a la base de datos
    require_once __DIR__. '/PHP/conexion_be.php';

    $rol = $conexion
      ->query("SELECT rol FROM usuarios WHERE id={$_SESSION['usuario_id']}")
      ->fetch_assoc()['rol'];

    // Redirigir al usuario a la página apropiada basada en su rol
    header("Location: PHP/Bienvenido{$rol}.php");
    exit;
  }

  // Incluir el archivo de conexión a la base de datos
  require_once __DIR__ . '/PHP/conexion_be.php';

  // Consultar el número de administradores en la base de datos
  $sql = "SELECT COUNT(*) as num_administradores FROM usuarios WHERE rol = 'A'";
  $resultado = $conexion->query($sql);
  $num_administradores = $resultado->fetch_assoc()['num_administradores'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SACADN</title>
    <link rel="icon" type="image/ico" href="./Sacadn.ico"/>
    <link rel="stylesheet" href="./Assets/fonts.css" />
    <link rel="stylesheet" href="./Assets/style.css" />
    <link rel="stylesheet" href="./Assets/fontawesome/css/all.min.css" />
</head>
<body>
    <div class="container" id="container">
        <div class="form-container register-container">
            <form id="registerForm" action="php/registro_usuario_be.php" method="POST" class="formulario__register">
            <img src="./Sacadn.ico" style="width: 100px; height: 100px;">
                <h1>Regístrate aquí</h1>
                <div class="form-control">
                    <input id="registerUsuario" pattern="[a-zA-Z0-9]{4,}" title="Sólo letras y números" placeholder="Usuario" name="usuario" required />
                    <small class="error-message"></small>
                    <span></span>
                </div>
                <div class="form-control">
                    <input id="registerNombreCompleto" minlength="7" maxlength="80" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ]{3,20} (\s?[a-zA-ZáéíóúÁÉÍÓÚñÑ]{3,20}){1,3}" placeholder="Nombre completo" name="nombre_completo"  required/>
                    <small class="error-message"></small>
                    <span></span>
                </div>
                <div class="form-control">
                    <input type= "number" id="registerCedula" placeholder="Cédula" name="cedula"  required/>
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
            <img src="./Sacadn.ico" style="width: 100px; height: 100px;">
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
                    <h1 class="title">Comienza tu <br /> Gestion ahora</h1>
                    <p>Si aún no tienes una cuenta, únete a nosotros y comienza tu gestión</p>
                    <?php
                    // Si no hay ningún administrador en la base de datos, mostrar el botón de registro
                    if ($num_administradores == 0) {
                        echo '<button class="ghost" id="register">Registrarse <i class="fa-solid fa-arrow-right"></i></button>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <script src="./Assets/main.js"></script>
</body>
</html>
