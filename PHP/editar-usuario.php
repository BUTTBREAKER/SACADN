<?php
//verfica que solo pueden entrar los Administradores
include __DIR__ . '/partials/header.php';
require __DIR__ . "/Middlewares/autorizacion.php";
// Incluir archivo de conexión a la base de datos
require_once __DIR__ . '/conexion_be.php';
if ($_POST) {
  $sql = <<<SQL
 UPDATE usuarios SET
 ID = ?,
 nombre = ?,
 apellido = ?,
 Cedula = ?,
 Usuarios = ?,
 clave = ?,
 rol = ?,
 estado = ?
 WHERE ID= ?
 SQL;

$db->prepare($sql)
    ->execute([
$_POST['ID'],
$_POST['nombre'],
$_POST['apellido'],
$_POST['Cedula'],
$_POST['Usuarios'],
$_POST['clave'],
$_POST['genero'],
$_POST['rol'],
$_POST['estado'],
$_POST['cedula']
]);

 exit(<<<HTML
  <body>
    <link rel="stylesheet" href="../assets/sweetalert2/borderless.min.css" />
    <script src="../assets/sweetalert2/sweetalert2.min.js"></script>
    <script>
      Swal.fire({
        title: 'Profesor actualizado correctamente',
        icon: 'success',
        showConfirmButton: false,
        timer: 3000
      }).then(() => location.href = './Profesores.php' )
    </script>
  </body>
  HTML);

}

$sql = "SELECT ID, nombre, apellido, Cedula, Usuario, clave, rol, estado FROM usuarios";
$resultado = $conexion->query($sql);
?>

<div class=" row mx-0 justify-content-center pb-5">
<form class="card col-md-7 py-4 mt-3" action="registro_usuario_be.php" method="POST">
  <h2 class="card-title h3 text-center">Crear Nuevo Usuario</h2>
  <div class="card-body row">
    <div class="form-floating mb-2 col-6">
      <input type="text" class="form-control form-control-sm" id="registerUsuario" placeholder="Usuario" name="usuario" required pattern="[A-Za-z0-9]+" maxlength="20" title="El usuario solo puede contener letras y números, con un máximo de 20 caracteres." />
      <label class="ms-2" for="registerUsuario">Usuario</label>
    </div>
    <div class="form-floating mb-2 col-6">
      <input type="text" class="form-control form-control-sm" id="registerNombreCompleto" placeholder="Nombre" name="nombre" required maxlength="50" onkeypress="return soloLetrasYEspacios(event)" title="El nombre solo puede contener letras y espacios, con un máximo de 50 caracteres." />
      <label class="ms-2" for="registerNombreCompleto">Nombres</label>
    </div>
    <div class="form-floating mb-2 col-6">
      <input type="text" class="form-control form-control-sm" id="registerApellidoCompleto" placeholder="Apellido" name="apellido" required maxlength="50" onkeypress="return soloLetrasYEspacios(event)" title="El apellido solo puede contener letras y espacios, con un máximo de 50 caracteres." />
      <label class="ms-2" for="registerApellidoCompleto">Apellidos</label>
    </div>
    <div class="form-floating mb-2 col-6">
      <input type="number" class="form-control form-control-sm" id="registerCedula" placeholder="Cédula" name="cedula" required min="0" pattern="[0-9]{11}" title="La cédula debe contener 11 dígitos numéricos." />
      <label class="ms-2" for="registerCedula">Cédula</label>
    </div>
    <div class="form-floating mb-2 col-6">
      <input type="password" class="form-control form-control-sm" id="registerContrasena" placeholder="Contraseña" name="clave" required minlength="4" maxlength="20" pattern=".{4,20}" title="La contraseña debe tener entre 4 y 20 caracteres." />
      <label class="ms-2" for="registerContrasena">Contraseña</label>
    </div>
    <div class="form-floating mb-2 col-6">
      <select class="form-control form-control-sm" id="rol" name="rol" required>
        <option value="A">Administrador</option>
        <option value="U">Usuario</option>
      </select>
      <label for="rol">Rol</label>
    </div>
    <div class="col-12 text-center">
      <button type="submit" class="btn btn-success">Editar Usuario</button>
    </div>
  </div>
</form>
</div>


<script src="../Assets/simple-datatables/simple-datatables.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
  const table = new simpleDatatables.DataTable('#userTable');
});
</script>
<script>
function soloLetrasYEspacios(event) {
  const tecla = event.key;
  const esLetra = (tecla >= 'a' && tecla <= 'z') || (tecla >= 'A' && tecla <= 'Z');
  const esEspacio = tecla === ' ';

  // Si la tecla no es una letra ni un espacio, se cancela la acción predeterminada
  if (!esLetra && !esEspacio) {
    event.preventDefault();
    return false;
  }
}


document.querySelectorAll('[data-action]').forEach(function(link) {
  link.onclick = function(event) {
    event.preventDefault()

    const url = './alternar-estado.php?toggle_estado=true&usuario_id=' + link.dataset.userId + '&nuevo_estado=' + link.dataset.newState

    console.log(url)
    fetch(url)
      .then(function() {
        link.innerText = link.innerText === 'Activar' ? 'Desactivar' : 'Activar'
        link.parentElement.previousElementSibling.innerText = link.parentElement.previousElementSibling.innerText === 'Activo' ? 'Inactivo' : 'Activo'
      })
  }
})
</script>

<?php include __DIR__ . '/partials/footer.php' ?>
<?php include __DIR__ . '/partials/footer.php' ?>
