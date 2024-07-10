<?php
//verfica que solo pueden entrar los Administradores
include __DIR__ . '/partials/header.php';
require __DIR__ . "/Middlewares/autorizacion.php";
// Incluir archivo de conexión a la base de datos
require_once __DIR__ . '/conexion_be.php';

// Consultar los datos de los usuarios
$sql = "SELECT ID, nombre, apellido, Cedula, Usuario, rol, estado FROM usuarios";
$resultado = $conexion->query($sql);

?>

<!-- Content Wrapper. Contains page content -->

<body>
  <div class="container card card-body table-responsive mt-3">
    <table id="userTable" class="table ">
      <h1 class="card-title h3 text-center">Panel de Administrador</h1>
      <h1 class="card-title h3 text-center">Tabla de Usuarios</h1>
      <thead>
        <tr>
          <th>ID</th>
          <th>Nombres</th>
          <th>Apellidos</th>
          <th>Cédula</th>
          <th>Usuario</th>
          <th>Rol</th>
          <th>Estado</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php
        // Iterar sobre los resultados de la consulta y mostrar los datos de los usuarios en la tabla
        while ($fila = $resultado->fetch_assoc()) {
          echo "<tr>";
          echo "<td>" . ucwords($fila['ID']) . "</td>";
          echo "<td>" . ucwords($fila['nombre']) . "</td>";
          echo "<td>" . ucwords($fila['apellido']) . "</td>";
          echo "<td>" . $fila['Cedula'] . "</td>";
          echo "<td>" . ucwords($fila['Usuario']) . "</td>";
          echo "<td>" . ucwords($fila['rol']) . "</td>";
          echo "<td>" . ucwords($fila['estado']) . "</td>";
          echo "<td>";
          echo "<a data-action='toggle-status' data-user-id='" . $fila['ID'] . "' data-new-state='" . ($fila['estado'] == 'activo' ? 'inactivo' : 'activo') . "'  href='./alternar-estado.php?toggle_estado=true&usuario_id=" . $fila['ID'] . "&nuevo_estado=" . ($fila['estado'] == 'activo' ? 'inactivo' : 'activo') . "'>" . ($fila['estado'] === 'activo' ? 'Desactivar' : 'Activar')  . "</a>";
          echo "</td>";
          echo "</tr>";
        }
        ?>
      </tbody>
    </table>
  </div>
</body>

</html>

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
        <button type="submit" class="btn btn-success">Crear Usuario</button>
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
