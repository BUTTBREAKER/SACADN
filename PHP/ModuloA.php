<?php
//verfica que solo pueden entrar los Administradores
include_once __DIR__ . '/../Assets/Menu/Menu.php';
require __DIR__ . "/middlewares/autorizacion.php";
?>
<?php
// Incluir archivo de conexión a la base de datos
require_once __DIR__ . '/conexion_be.php';

// Consultar los datos de los usuarios
$sql = "SELECT ID, nombre_completo, Cedula, Usuario, rol, estado FROM usuarios";
$resultado = $conexion->query($sql);

// Comprobar si se ha enviado una solicitud para cambiar el estado del usuario
if (isset($_GET['toggle_estado'])) {
  // Obtener el ID del usuario y el nuevo estado
  $usuario_id = $_GET['usuario_id'];
  $nuevo_estado = $_GET['nuevo_estado'];

  // Actualizar el estado del usuario en la base de datos
  $update_sql = "UPDATE usuarios SET estado = '$nuevo_estado' WHERE ID = $usuario_id";
  $conexion->query($update_sql);
}

?>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel de Administrador</title>
  <link rel="stylesheet" href="../Assets/simple-datatables/simple-datatables.css">
  <style type="text/css">
    .contenedor {
      max-width: 1000px;
      margin: 0 auto;
      padding: 20px;
    }

    h1,
    h2 {
      text-align: center;
    }

    .users-table {
      margin-bottom: 10px;
    }

    .create-user-form {
  border: 1px solid #ccc;
  padding: 19px;
  margin-bottom: 20px;
}

.create-user-form h2 {
  text-align: center;
  margin-bottom: 20px;
}

.create-user-form label {
  display: block;
  margin-bottom: 10px;
}

.create-user-form input[type="text"],
.create-user-form input[type="number"],
.create-user-form input[type="password"],
.create-user-form select {
  width: 50%;
  padding: 8px;
  margin-bottom: 15px;
}

.create-user-form button {
  padding: 10px 20px;
  background-color: #007bff;
  color: #fff;
  border: none;
  cursor: pointer;
}

.create-user-form button:hover {
  background-color: #0056b3;
}


    .table {
      width: 100%;
      border-collapse: collapse;
    }

    .table th,
    .table td {
      border: 1px solid #ddd;
      padding: 8px;
      text-align: left;
    }

    .table th {
      background-color: #f2f2f2;
    }
  </style>
</head>

<body>
  <div class="container">
    <h1>Panel de Administrador</h1>

    <body>
      <div class="contenedor">
        <h1>Tabla de Usuarios</h1>
        <div class="users-table">
          <table id="userTable" class="table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Nombre Completo</th>
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
                echo "<td>" . $fila['ID'] . "</td>";
                echo "<td>" . $fila['nombre_completo'] . "</td>";
                echo "<td>" . $fila['Cedula'] . "</td>";
                echo "<td>" . $fila['Usuario'] . "</td>";
                echo "<td>" . $fila['rol'] . "</td>";
                echo "<td>" . $fila['estado'] . "</td>";
                echo "<td>";
                echo "<a href='?toggle_estado=true&usuario_id=" . $fila['ID'] . "&nuevo_estado=" . ($fila['estado'] == 'activo' ? 'inactivo' : 'activo') . "'>Alternar Estado</a>";
                echo "</td>";
                echo "</tr>";
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </body>

</html>

<div class="create-user-form">
  <h2>Crear Nuevo Usuario</h2>
  <form action="registro_usuario_be.php" method="POST">
    <div class="form-control">
      <input type="text" id="registerUsuario" placeholder="Usuario" name="usuario" required pattern="[A-Za-z0-9]+" maxlength="20" title="El usuario solo puede contener letras y números, con un máximo de 20 caracteres." />
      <small class="error-message"></small>
      <span></span>
    </div>
    <div class="form-control">
      <input type="text" id="registerNombreCompleto" placeholder="Nombre completo" name="nombre_completo"  required pattern="[A-Za-z\s]+" maxlength="50" title="El nombre solo puede contener letras y espacios, con un máximo de 50 caracteres."/>
      <small class="error-message"></small>
      <span></span>
    </div>
    <div class="form-control">
      <input type="number" id="registerCedula" placeholder="Cédula" name="cedula"  required min="0" pattern="[0-9]{11}" title="La cédula debe contener 11 dígitos numéricos."/>
      <small class="error-message"></small>
      <span></span>
    </div>
    <div class="form-control">
      <input type="password" id="registerContrasena" placeholder="Contraseña" name="contrasena" required minlength="4" maxlength="20" pattern=".{4,20}" title="La contraseña debe tener entre 4 y 20 caracteres." />
      <small class="error-message"></small>
      <span></span>
    </div>
    <div class="form-control">
      <label for="rol">Rol:</label>
      <select id="rol" name="rol" required>
        <option value="A">Administrador</option>
        <option value="U">Usuario</option>
      </select>
    </div>
    <button type="submit">Crear Usuario</button>
  </form>
</div>




<script src="../Assets/simple-datatables/simple-datatables.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const table = new simpleDatatables.DataTable('#userTable');
  });
</script>
</body>

</html>
