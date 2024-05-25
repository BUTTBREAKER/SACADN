<?php

require __DIR__ . '/../vendor/autoload.php';

/** @var mysqli */
$db = require_once __DIR__ . '/conexion_be.php';
include __DIR__ . '/partials/header.php';

$sql = <<<SQL
  SELECT cedula, nombre as nombres, apellido AS apellidos,
  fecha_nacimiento, lugar_nacimiento, genero AS sexo, telefono, direccion,
  fecha_registro FROM representantes
SQL;

$result = $db->query($sql);

include __DIR__ . '/partials/footer.php';
