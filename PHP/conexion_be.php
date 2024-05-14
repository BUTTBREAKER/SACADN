<?php

$_ENV += (function () {
  $vars = require_once __DIR__ . '/../.env.php';

  return is_array($vars) ? $vars : [];
})();

$conexion = new mysqli(
  $_ENV['DB_HOST'],
  $_ENV['DB_USERNAME'],
  $_ENV['DB_PASSWORD'],
  $_ENV['DB_DATABASE'],
  $_ENV['DB_PORT']
);

$conexion->set_charset('utf8');
date_default_timezone_set('America/Caracas');

return $conexion;
