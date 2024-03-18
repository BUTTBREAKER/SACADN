<?php

$db = require_once __DIR__ . '/con_db.php';

/* Selecciona campo ci_repr y cambiale el nombre a cedula, ..., de la tabla representantes */
$sql = <<<SQL
  SELECT ci_repr AS cedula, nombre_completo AS nombres, apellido AS apellidos,
  fecha_nac AS fecha_nacimiento, estado as estado_nacimiento, lugar AS lugar_nacimiento,
  genero AS sexo, telefono, direccion, fech_repr AS fecha_registro FROM representantes
SQL;

$result = $db->query($sql);

function calcularEdad(string $fechaNacimiento): int {
  $fechaNacimiento = new DateTimeImmutable($fechaNacimiento);
  $fechaNacimientoTimestamp = $fechaNacimiento->getTimestamp();
  $timestampActual = time();

  $diferencia = $timestampActual - $fechaNacimientoTimestamp;

  $edad = date('Y', $diferencia);
  $edad -= 1970;

  return abs($edad);
}

function formatearFecha(string $crudo): string {
  $datetime = new DateTimeImmutable($crudo);

  return $datetime->format('d/m/Y');
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Registro Representante</title>  
    <link rel="stylesheet" type="text/css" href="">
</head>
<style type="text/css">
  .btn btn-primary
</style>

 <body>
 	<div class="contenedor">
    <form class="table" method="post">
<div class="row">
  <h2 style="text-align: center;">Lista Representantes</h2>
</div>
<div class="row">
  <a href="nuevo.php" class="btn btn-primary">Nuevo Registro</a>
</div>

  <table>
    <tr>
      <!-- <td>ID</td> -->
      <th>Cédula</th>
      <th>Nombres</th>
      <th>Apellidos</th>
      <th>Fecha de nacimiento</th>
      <th>Edad</th>
      <th>Estado de nacimiento</th>
      <th>Lugar de nacimiento</th>
      <th>Sexo</th>
      <th>Teléfono</th>
      <th>Dirección</th>
      <th>Fecha</th>
    </tr>
    <?php while ($mostrar = $result->fetch_assoc()) { ?>
      <tr>
        <td><?= $mostrar['cedula'] ?></td>
        <td><?= $mostrar['nombres'] ?></td>
        <td><?= $mostrar['apellidos'] ?></td>
        <td><?= formatearFecha($mostrar['fecha_nacimiento']) ?></td>
        <td><?= calcularEdad($mostrar['fecha_nacimiento']) ?></td>
        <td><?= $mostrar['estado_nacimiento'] ?></td>
        <td><?= $mostrar['lugar_nacimiento'] ?></td>
        <td><?= $mostrar['sexo'] ?></td>
        <td><?= $mostrar['telefono'] ?></td>
        <td><?= $mostrar['direccion'] ?></td>
        <td><?= formatearFecha($mostrar['fecha_registro']) ?></td>
      </tr>
    <?php } ?>
  </table>
</form>
</div>