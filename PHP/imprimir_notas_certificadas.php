<?php

require __DIR__ . '/../vendor/autoload.php';
include __DIR__ . '/partials/header.php';
// Incluir el archivo de conexión a la base de datos
/** @var mysqli */
$db = require_once __DIR__ . '/conexion_be.php';

?>

<div class="row mx-0 justify-content-center pb-4">
  <form class="card col-md-5 py-3" method="post" action="./reporte_notas_certificadas.php" autocomplete="off">
    <h1 class="card-title h3 text-center">Imprimir notas certificadas</h1>
    <div class="card-body row">


      <div class="col-md-12 form-floating mb-3">
        <input class="form-control" type="number" id="cedula" placeholder=" " name="cedula" pattern="[0-9]+" required>
        <label class="ms-2" for="cedula">
          Cédula del estudiante:
        </label>
      </div>

      <div class="btn-group btn-group-lg mx-3">
        <button class="btn btn-success w-75">Imprimir</button>
        <a href="javascript:history.back()" class="btn btn-outline-secondary">Regresar</a>
      </div>
  </form>
</div>
<?php include __DIR__ . '/partials/footer.php' ?>