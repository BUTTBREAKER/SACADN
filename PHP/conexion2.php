<?php 
// Crear la conexión
$conn = new mysqli(
$servername = "localhost",
$username = "root",
$password = "",
$database = "sacadn1");

$conn->set_charset('utf8');

return $conn; ?>
