<?php

declare(strict_types=1);

use SACADN\Enums\PCRegExp;

$showAdministratorRegister ??= true;

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width" />
    <title>SACADN</title>
    <link rel="icon" href="./favicon.ico" />
    <link rel="stylesheet" href="./Assets/fonts.css" />
    <link rel="stylesheet" href="./Assets/style.css" />
  </head>
  <body>
    <div class="container" id="container">
      <div class="form-container register-container">
        <?php if ($showAdministratorRegister) { ?>
          <form
            id="registerForm"
            action="PHP/registro_usuario_be.php"
            method="post"
            class="formulario__register"
          >
            <img src="./favicon.ico" width="100" height="100" />
            <h1>Regístrate aquí</h1>
            <div class="form-control">
              <input
                id="registerUsuario"
                pattern="<?= PCRegExp::UserAlias->pattern() ?>"
                title="<?= PCRegExp::UserAlias->title() ?>"
                title="Sólo letras y números"
                placeholder="Usuario"
                name="usuario"
                required
              /> <small class="error-message"></small> <span></span>
            </div>
            <div class="form-control">
              <input
                id="nombre"
                minlength="3"
                maxlength="80"
                pattern="<?= PCRegExp::Names->pattern() ?>"
                title="<?= PCRegExp::Names->title() ?>"
                placeholder="Nombres"
                name="nombre"
                required
              /> <small class="error-message"></small> <span></span>
            </div>
            <div class="form-control">
              <input
                id="apellido"
                minlength="3"
                maxlength="80"
                pattern="<?= PCRegExp::Names->pattern() ?>"
                title="<?= PCRegExp::Names->title() ?>"
                placeholder="Apellidos"
                name="apellido"
                required
              /> <small class="error-message"></small> <span></span>
            </div>
            <div class="form-control">
              <input
                type="number"
                id="registerCedula"
                placeholder="Cédula"
                name="cedula"
                required
              /> <small class="error-message"></small> <span></span>
            </div>
            <div class="form-control">
              <input
                type="password"
                id="registerContrasena"
                placeholder="Contraseña"
                name="clave"
                required
                minlength="4"
                maxlength="20"
                pattern="<?= PCRegExp::Password->pattern() ?>"
                title="<?= PCRegExp::Password->title() ?>"
              /> <small class="error-message"></small> <span></span>
            </div>
            <input type="hidden" name="rol" value="A" />
            <button>Registrar</button>
          </form>
        <?php } ?>
      </div>
      <div class="form-container login-container">
        <form
          id="loginForm"
          method="post"
          class="formulario__login"
        >
          <img src="./favicon.ico" width="100" height="100" />
          <h1>Inicia sesión aquí</h1>
          <div class="form-control">
            <input
              id="loginUsuario"
              placeholder="Usuario"
              name="usuario"
            /> <small class="error-message"></small> <span></span>
          </div>
          <div class="form-control">
            <input
              type="password"
              id="loginContrasena"
              placeholder="Contraseña"
              name="clave"
            /> <small class="error-message"></small> <span></span>
          </div>
          <button value="submit">Iniciar sesión</button>
        </form>
      </div>
      <div class="overlay-container">
        <div class="overlay">
          <div class="overlay-panel overlay-left">
            <h1 class="title">Bienvenido de nuevo</h1>
            <p>Si tienes una cuenta, inicia sesión aquí y comienza a trabajar</p>
            <button class="ghost" id="login">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                <path
                  d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.2 288 416 288c17.7 0 32-14.3 32-32s-14.3-32-32-32l-306.7 0L214.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-160 160z" />
              </svg>
              Iniciar sesión
            </button>
          </div>
          <div class="overlay-panel overlay-right">
            <h1 class="title">Comienza tu gestión ahora</h1>
            <?php if ($showAdministratorRegister) { ?>
              <p>
                Si aún no tienes una cuenta, únete a nosotros y comienza tu
                gestión
              </p>
              <button class="ghost" id="register">
                Registrarse
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                  <path
                    d="M438.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-160-160c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L338.8 224 32 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l306.7 0L233.4 393.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l160-160z" />
                </svg>
              </button>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
    <script src="./Assets/main.js"></script>
  </body>
</html>
