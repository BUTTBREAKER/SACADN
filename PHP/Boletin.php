<?php  
require __DIR__ . '/../vendor/autoload.php';
// Incluir el archivo de conexión a la base de datos
/** @var mysqli */
$db = require_once __DIR__ . '/conexion_be.php';
include __DIR__ . '/partials/header.php';

 $sql = <<<SQL
  SELECT id, id_momentos, id_estudiantes  FROM boletines
SQL;

   // code...
 


?>