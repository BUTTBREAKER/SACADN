
<?php
//verfica que solo pueden entrar los Administradores
require __DIR__."/middlewares/autorizacion.php";
include_once __DIR__ . '/../Assets/Menu/Menu.php';?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administrador</title>
    <link rel="stylesheet" href="../Assets/simple-datatables/simple-datatables.css">
    <style type="text/css">
      .container {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
}

h1, h2 {
    text-align: center;
}

.users-table {
    margin-bottom: 20px;
}

.create-user-form {
    border: 1px solid #ccc;
    padding: 20px;
}

.create-user-form label {
    display: block;
    margin-bottom: 10px;
}

.create-user-form input[type="text"],
.create-user-form input[type="password"] {
    width: 100%;
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

.table th, .table td {
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
        <div class="users-table">
            <table id="userTable" class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre de Usuario</th>
                        <th>Nombre Completo</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Los datos de los usuarios se cargarán aquí -->
                </tbody>
            </table>
        </div>
        <div class="create-user-form">
            <h2>Crear Nuevo Usuario</h2>
            <form action="crear_usuario.php" method="POST">
                <label for="username">Nombre de Usuario:</label>
                <input type="text" id="username" name="username" required>
                <label for="full-name">Nombre Completo:</label>
                <input type="text" id="full-name" name="full_name" required>
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required>
                <button type="submit">Crear Usuario</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const table = new simpleDatatables.DataTable('#userTable');
        });
    </script>
</body>
</html>
