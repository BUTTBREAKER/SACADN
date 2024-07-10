<?php
// Incluir archivo de conexión a la base de datos
$db = require_once __DIR__ . '/conexion_be.php';
//verfica que solo pueden entrar los Administradores
include __DIR__ . '/partials/header.php';
require __DIR__ . "/Middlewares/autorizacion.php";


if ($_POST) {
  $sql = <<<SQL
 UPDATE usuarios SET
 nombre = ?,
 apellido = ?,
 cedula = ?,
 usuario = ?,
 clave = ?,
 rol = ?
 WHERE id = ?
 SQL;

  $contrasena_hash = password_hash($_POST['clave'], PASSWORD_BCRYPT);


  $db->prepare($sql)
    ->execute([
      $_POST['nombre'],
      $_POST['apellido'],
      $_POST['cedula'],
      $_POST['usuario'],
      $contrasena_hash,
      $_POST['rol'],
      $_POST['id']
    ]);

  exit(<<<HTML
  <body>
    <link rel="stylesheet" href="../assets/sweetalert2/borderless.min.css" />
    <script src="../assets/sweetalert2/sweetalert2.min.js"></script>
    <script>
      Swal.fire({
        title: 'Usuario actualizado correctamente',
        icon: 'success',
        showConfirmButton: false,
        timer: 3000
      }).then(() => location.href = './ModuloA.php' )
    </script>
  </body>
  HTML);
}

$sql = "SELECT id, nombre, apellido, cedula, usuario, clave, rol FROM usuarios";
$resultado = $conexion->query($sql);
$usuario = $resultado->fetch_assoc()
?>

<div class=" row mx-0 justify-content-center pb-5">
  <form class="card col-md-7 py-4 mt-3" action="editar-usuario.php" method="POST">
    <h2 class="card-title h3 text-center">Editar Usuario</h2>
    <div class="card-body row">
      <div class="form-floating mb-2 col-6">
        <input type="text" class="form-control form-control-sm" value="<?= $usuario['usuario'] ?>" id="registerUsuario" placeholder="Usuario" name="usuario" required pattern="[A-Za-z0-9]+" maxlength="20" title="El usuario solo puede contener letras y números, con un máximo de 20 caracteres." />
        <label class="ms-2" for="registerUsuario">Usuario</label>
      </div>

      <div class="form-floating mb-2 col-6">
        <div class="form-control form-control-sm" placeholder=" " id="registerNombreCompleto" name="id" required><?php echo $usuario['nombre']; ?> </div>

        <label class="ms-2" for="registerNombreCompleto">Nombres</label>
      </div>
      <div class="form-floating mb-2 col-6">
        <input type="text" class="form-control form-control-sm" value="<?= $usuario['apellido'] ?>" id="registerApellidoCompleto" placeholder="Apellido" name="apellido" required maxlength="50" onkeypress="return soloLetrasYEspacios(event)" title="El apellido solo puede contener letras y espacios, con un máximo de 50 caracteres." />
        <label class="ms-2" for="registerApellidoCompleto">Apellidos</label>
      </div>
      <div class="form-floating mb-2 col-6">
        <input type="number" class="form-control form-control-sm" value="<?= $usuario['cedula'] ?>" id="registerCedula" placeholder="Cédula" name="cedula" required min="0" pattern="[0-9]{11}" title="La cédula debe contener 11 dígitos numéricos." />
        <label class="ms-2" for="registerCedula">Cédula</label>
      </div>
      <div class="form-floating mb-2 col-6">
        <input type="password" class="form-control form-control-sm" id="registerContrasena" placeholder="Contraseña" name="clave" required minlength="4" maxlength="20" pattern=".{4,20}" title="La contraseña debe tener entre 4 y 20 caracteres." />
        <label class="ms-2" for="registerContrasena">Contraseña</label>
      </div>
      <div class="form-floating mb-2 col-6">
        <select class="form-control form-control-sm" value="<?= $usuario['rol'] ?>" id="rol" name="rol" required>
          <option value="A">Administrador</option>
          <option value="U">Usuario</option>
        </select>
        <label for="rol">Rol</label>
      </div>
      <input type="hidden" value="<?= $usuario['id'] ?>" name="id">
      <div class="col-12 text-center">
        <button type="submit" class="btn btn-success">Editar Usuario</button>
      </div>
    </div>
  </form>
</div>

<?php include __DIR__ . '/partials/footer.php' ?>
