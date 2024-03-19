<?php
// Incluir el archivo de conexión a la base de datos
include('conexion2.php');
include('../Assets/Menu/Menu.php');
?>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Profesores</title>
    <link rel="stylesheet" href="../Assets/simple-datatables/simple-datatables.css">
</head>
<body>

    <div style="overflow-x: auto;">
        <table id="tablaRepresentantes" class="datatable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombres</th>
                    <th>Apellidos</th>
                    <th>Cédula</th>
                    <th>Estado</th>
                    <th>Lugar</th>
                    <th>Género</th>
                    <th>Teléfono</th>
                    <th>Dirección</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php
        // Verificar la conexión
        if ($conn->connect_error) {
        die("Error de conexión a la base de datos: " . $conn->connect_error);
        }
        
        // Realizar la consulta a la base de datos para obtener los datos de los profesores
        $query = "SELECT * FROM representantes";
        $result = $conn->query($query);
        
        // Verificar si la consulta tuvo éxito
        if ($result) {
        // Inicializar el contador para enumerar las filas
        $i = 1;
        // Comenzar a imprimir las filas de la tabla
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td class='text-center'>$i</td>";
            echo "<td>" . $row['nombre'] . "</td>";
            echo "<td>" . $row['apellido'] . "</td>";
            echo "<td>" . $row['cedula'] . "</td>";
            echo "<td>" . $row['estado'] . "</td>";
            echo "<td>" . $row['lugar'] . "</td>";
            echo "<td>" . $row['genero'] . "</td>";
            echo "<td>" . $row['telefono'] . "</td>";
            echo "<td>" . $row['direccion'] . "</td>";
            echo "<td class='text-center'>
            <button>Modificar</button>
            <button>Eliminar</button></td>"; // Aquí puedes agregar botones de acción si lo deseas
            echo "</tr>";
            // Incrementar el contador
            $i++;
        }
        } else {
        // Manejar el caso en que la consulta falle
        echo "Error al consultar la base de datos: " . $conn->error;
        }
        
        // Cerrar la conexión a la base de datos después de usarla
        $conn->close();
         ?>
            </tbody>
        </table>
    </div>

    <script src="../Assets/simple-datatables/simple-datatables.min.js"></script>
    <script>
        const tablaRepresentantes = new simpleDatatables.DataTable("#tablaRepresentantes");
    </script>
<?php include('partials/footer.php') ?>