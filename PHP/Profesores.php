<?php

require __DIR__ . '/../vendor/autoload.php';
// Incluir el archivo de conexión a la base de datos
$db = require_once __DIR__ . '/conexion_be.php';
include_once __DIR__ . '/../Assets/Menu/Menu.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lista de Profesores</title>
  <link rel="stylesheet" href="../Assets/simple-datatables/simple-datatables.css">
</head>

<body>
  <table id="tablaProfesores" class="datatable">
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
      $query = "SELECT * FROM profesores";
      $result = $conn->query($query);

      // Verificar si la consulta tuvo éxito
      if ($result) {
        // Inicializar el contador para enumerar las filas
        $i = 1;

        // Comenzar a imprimir las filas de la tabla
        while ($row = $result->fetch_assoc()) {
          echo "<tr>";
          echo "<td class='text-center'>$i</td>";
          echo "<td>" . $row['ci_prof'] . "</td>";
          echo "<td>" . $row['nombre_completo'] . "</td>";
          echo "<td>" . $row['apellido'] . "</td>";
          echo "<td>" . formatearFecha($row['fecha_nac']) . "</td>";
          echo "<td>" . calcularEdad($row['fecha_nac']) . "</td>";
          echo "<td>" . $row['estado'] . "</td>";
          echo "<td>" . $row['lugar'] . "</td>";
          echo "<td>" . $row['genero'] . "</td>";
          echo "<td>" . $row['telefono'] . "</td>";
          echo "<td>" . $row['direccion'] . "</td>";
          echo "<td>" . formatearFecha($row['fech_prof']) . "</td>";
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
    const tablaProfesores = new simpleDatatables.DataTable("#tablaProfesores");
  </script>
  <?php include('partials/footer.php') ?><?php include('partials/footer.php') ?>
