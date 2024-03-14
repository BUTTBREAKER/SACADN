<?php

$_ENV += (function () {
  $vars = require_once __DIR__ . '/../../.env.php';

  return is_array($vars) ? $vars : [];
})();

// Verifica si el directorio de respaldo existe, si no, créalo
$backupDirectory = __DIR__ . '/../backups';
if (!is_dir($backupDirectory)) {
  if (!mkdir($backupDirectory, 0777, true)) {
    die("Error al crear el directorio de respaldo.");
  }
}

$backupPath = $backupDirectory . '/full_backup.mysql.sql';

$conexion = new mysqli(
  $_ENV['DB_HOST'],
  $_ENV['DB_USERNAME'],
  $_ENV['DB_PASSWORD'],
  $_ENV['DB_DATABASE'],
  $_ENV['DB_PORT'],
);

if ($conexion->connect_error) {
  die("Error de conexión: " . $conexion->connect_error);
}

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

exit(<<<HTML
<html>
  <head>
  </head>
  <body>
    <script>
      alert('Base de datos respaldada exitósamente');
      location.href = '../../';
    </script>
  </body>
</html>
HTML);

$conexion->close();
?>