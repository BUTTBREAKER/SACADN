<?php

require __DIR__ . '/../vendor/autoload.php';
// Incluir el archivo de conexión a la base de datos
/** @var mysqli */
$db = require_once __DIR__ . '/conexion_be.php';
include __DIR__ . '/partials/header.php';

/* Selecciona campo ci_repr y cambiale el nombre a cedula, ..., de la tabla notas */
$sql = <<<SQL
  SELECT ci_repr AS cedula, nombre_completo AS nombres, apellido AS apellidos,
  fecha_nac AS fecha_nacimiento, estado as estado_nacimiento, lugar AS lugar_nacimiento,
  genero AS sexo, telefono, direccion, fech_repr AS fecha_registro FROM representantes
SQL;

$result = $db->query($sql);

include __DIR__ . '/partials/footer.php';
