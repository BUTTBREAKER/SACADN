<?php

require __DIR__ . '/../vendor/autoload.php';
include __DIR__ . '/partials/header.php';
// Incluir el archivo de conexión a la base de datos
/** @var mysqli */
$db = require_once __DIR__ . '/conexion_be.php';

?>

<div class="row mx-0 justify-content-center pb-4">
  <form class="card col-md-5 py-3" method="post" action="./reporte_notas_certificadas.php" autocomplete="off" id="cedulaForm">
    <h1 class="card-title h3 text-center">Imprimir notas certificadas</h1>
    <div class="card-body row">
      <div class="col-md-12 form-floating mb-3">
        <input class="form-control" type="number" id="cedula" placeholder=" " name="cedula" pattern="[0-9]+" required>
        <label class="ms-2" for="cedula">Cédula del estudiante:</label>
      </div>

      <div class="btn-group btn-group-lg mx-3">
        <button type="button" class="btn btn-success w-75" onclick="verificarCedula()">Imprimir</button>
        <a href="javascript:history.back()" class="btn btn-outline-secondary">Regresar</a>
      </div>
    </div>
  </form>
</div>
<?php include __DIR__ . '/partials/footer.php' ?>


<script>
  function verificarCedula() {
    var cedula = document.getElementById('cedula').value;
    if (cedula === '') {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Por favor, ingrese una cédula.',
        confirmButtonText: 'OK'
      });
      return;
    }

    fetch('./verificar_cedula.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          cedula: cedula
        })
      })
      .then(response => response.json())
      .then(data => {
        if (data.exists) {
          document.getElementById('cedulaForm').submit();
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Estudiante no encontrado.',
            confirmButtonText: 'OK'
          });
        }
      })
      .catch(error => {
        console.error('Error:', error);
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Ocurrió un error al verificar la cédula.',
          confirmButtonText: 'OK'
        });
      });
  }
</script>
