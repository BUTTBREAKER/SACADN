<?php
// Verificar que solo los Administradores pueden acceder
include __DIR__ . '/partials/header.php';
require __DIR__ . "/middlewares/autorizacion.php";

// Incluir archivo de conexión a la base de datos
require_once __DIR__ . '/conexion_be.php';

if (!isset($conexion)) {
  die('Error: No se pudo establecer la conexión a la base de datos.');
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $anio_inicio = $_POST['anio_inicio'];
  $anio_cierre = $anio_inicio + 1;

  // Preparar la consulta para insertar el nuevo periodo
  $stmt = $conexion->prepare("INSERT INTO periodos (anio_inicio) VALUES (?)");
  $stmt->bind_param("i", $anio_inicio);

  if ($stmt->execute()) {
    echo "Nuevo periodo registrado exitosamente: $anio_inicio-$anio_cierre.";
  } else {
    echo "Error: " . $stmt->error;
  }

  $stmt->close();
  $conexion->close();
}
?>
<div class=" row mx-0 justify-content-center pb-4">
  <form class="card col-md-5 py-3" method="post" action="./guardar_periodo.php" autocomplete="off">
    <h1 class="card-title h3 text-center">Registrar Nuevo Periodo</h1>
    <div class="card-body row">
      <div class="col-md-7 form-floating mb-2">
        <input class="form-control" type="number" id="anio_inicio" placeholder=" " name="anio_inicio" required>
        <label class="ms-2" for="anio_inicio">Año de Inicio:</label>
        <button class="btn btn-success w-30" type="submit">Registrar</button>
  </form>
</div>
<?php include('partials/footer.php') ?>
</body>

