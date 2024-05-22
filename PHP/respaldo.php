<?php
require __DIR__."/middlewares/autorizacion.php";

// Verifica si el directorio de respaldo existe, si no, créalo
$backupDirectory = __DIR__ .'/backups';

if (!is_dir($backupDirectory)) {
  if (!mkdir($backupDirectory, 0777, true)) {
    die("Error al crear el directorio de respaldo.");
  }
}

$backupPath = $backupDirectory . '/full_backup.mysql.sql';

$conexion = require __DIR__."/conexion_be.php";

// Abre el archivo para escritura
$backupFile = fopen($backupPath, 'w');

if ($backupFile === false) {
  die("Error al abrir el archivo de respaldo.");
}

// Obtiene el nombre de todas las tablas
$tables = [];
$result = $conexion->query("SHOW TABLES");
while ($row = $result->fetch_row()) {
  $tables[] = $row[0];
}

// Itera sobre todas las tablas y guarda sus datos en el archivo
foreach ($tables as $table) {
  // Escribe el encabezado de la tabla en el archivo
  fwrite($backupFile, "DROP TABLE IF EXISTS $table;\n");
  $result = $conexion->query("SHOW CREATE TABLE $table");
  $row = $result->fetch_row();
  fwrite($backupFile, $row[1] . ";\n");
  
  // Obtiene y escribe los datos de la tabla en el archivo
  $result = $conexion->query("SELECT * FROM $table");
  while ($row = $result->fetch_assoc()) {
    $values = array_map(function ($value) use ($conexion) {
      return "'" . $conexion->real_escape_string($value) . "'";
    }, $row);
    fwrite($backupFile, "INSERT INTO $table VALUES (" . implode(', ', $values) . ");\n");
  }
}

fclose($backupFile);

echo <<<HTML
  <body>
    <link rel="stylesheet" href="../Assets/sweetalert2/borderless.min.css" />
    <script src="../Assets/sweetalert2/sweetalert2.min.js"></script>
    <script>
      Swal.fire({
        title: 'Base  de Datos Respaldada correctamente',
        icon: 'success',
        showConfirmButton: false,
        timer: 3000
      }).then(() => location.href = "../index.php");
    </script>
  </body>
  HTML;

$conexion->close();
