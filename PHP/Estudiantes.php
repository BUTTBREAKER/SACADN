<?php
// Incluir el archivo de conexión a la base de datos
include('conexion2.php');
include('../Assets/Menu/Menu.php');

function calcularEdad(string $fecha_nac): int {
  $fecha_nac = new DateTimeImmutable($fecha_nac);
  $fecha_nacTimestamp = $fecha_nac->getTimestamp();
  $timestampActual = time();

  $diferencia = $timestampActual - $fecha_nacTimestamp;

  $edad = date('Y', $diferencia);
  $edad -= 1970;

  return abs($edad);
}

function formatearFecha(string $crudo): string {
  $datetime = new DateTimeImmutable($crudo);

  return $datetime->format('d/m/Y');
}
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
    <title>Lista de Estudiantes</title>
    <link rel="stylesheet" href="../Assets/simple-datatables/simple-datatables.css">
</head>
<body>
    <table id="tablaEstudiantes" class="datatable">
        <thead>
            <tr>
               <th>#</th>
                <th>Cédula</th>
                <th>Nombres</th>
                <th>Apellidos</th>
                <th>Fecha de Nacimiento</th>
                <th>Edad</th>
                <th>Estado de Nacimiento</th>
                <th>Lugar de Nacimiento</th>
                <th>Género</th>
                <th>Teléfono</th>
                <th>Dirección</th>
                <th>Fecha de Registro</th>
                <th>Representante</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php
// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión a la base de datos: " . $conn->connect_error);
}

// Realizar la consulta a la base de datos para obtener los datos de los estudiantes
$query = "SELECT * FROM estudiantes";
$result = $conn->query($query);

// Verificar si la consulta tuvo éxito
if ($result) {
    // Inicializar el contador para enumerar las filas
    $i = 1;

    // Comenzar a imprimir las filas de la tabla
    while ($row = $result->fetch_assoc()) {
         echo "<tr>";
        echo "<td class='text-center'>$i</td>";
        echo "<td>" . $row['ci_est'] . "</td>";
        echo "<td>" . $row['nombre_completo'] . "</td>";
        echo "<td>" . $row['apellido'] . "</td>";
        echo "<td>" .formatearFecha($row['fecha_nac']). "</td>";
        echo "<td>" .calcularEdad($row['fecha_nac']) ."</td>";
        echo "<td>" . $row['estado'] . "</td>";
        echo "<td>" . $row['lugar'] . "</td>";
        echo "<td>" . $row['genero'] . "</td>";
        echo "<td>" . $row['telefono'] . "</td>";
        echo "<td>" . $row['direccion'] . "</td>";
        echo "<td>" . formatearFecha($row['fech_est']) . "</td>";
        echo "<td>" . $row['ci_repr'] . "</td>";
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

    <script src="../Assets/simple-datatables/simple-datatables.min.js"></script>
    <script>
        const tablaestudintes = new simpleDatatables.DataTable("#tablaEstudiantes");
    </script>
<?php include('partials/footer.php') ?>