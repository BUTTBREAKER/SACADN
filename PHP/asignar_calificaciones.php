iugiu
<?php

require __DIR__ . '/../vendor/autoload.php';

/** @var mysqli */
$db = require_once __DIR__ . '/conexion_be.php';
include __DIR__ . '/partials/header.php';



$sql = <<<SQL
  SELECT id_materia, id_boletin, calificacion FROM calificaciones
SQL;

$id_boletin = $mostrar[''];
$result = $db->query($sql);

?>
  
<div class="container card card-body table-responsive">
  <table id="tablaCalificaciones" class="table table-striped datatable">
  <form class="card col-md-6 py-4" method="post" action="./asignar_calificaciones.php" autocomplete="off">
    <h1 class="card-title h3 text-center">Calificaciones del estudiante</h1>

      <div class="col-md-4 form-floating mb-3">
        <input class="form-control" type="number" min="4000000" max="50000000" placeholder=" " name="cedula" required />
        <label class="ms-2">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
            <path fill="currentColor" d="M360 73c-14.43 0-27.79 7.71-38.055 21.395c-10.263 13.684-16.943 33.2-16.943 54.94c0 21.74 6.68 41.252 16.943 54.936c10.264 13.686 23.625 21.396 38.055 21.396s27.79-7.71 38.055-21.395C408.318 190.588 415 171.075 415 149.335c0-21.74-6.682-41.255-16.945-54.94C387.79 80.71 374.43 73 360 73m-240 96c-10.012 0-19.372 5.32-26.74 15.145C85.892 193.968 81 208.15 81 224c0 15.85 4.892 30.032 12.26 39.855C100.628 273.68 109.988 279 120 279c10.012 0 19.374-5.32 26.742-15.145c7.368-9.823 12.256-24.006 12.256-39.855c0-15.85-4.888-30.032-12.256-39.855C139.374 174.32 130.012 169 120 169m188.805 47.674a77.568 77.568 0 0 0-4.737 3.974c-13.716 12.524-23.816 31.052-31.53 54.198c-14.59 43.765-20.404 103.306-30.063 164.154h235.05c-9.66-60.848-15.476-120.39-30.064-164.154c-7.714-23.146-17.812-41.674-31.528-54.198a76.795 76.795 0 0 0-4.737-3.974c-12.84 16.293-30.942 26.994-51.195 26.994s-38.355-10.7-51.195-26.994zM81.27 277.658c-.573.485-1.143.978-1.702 1.488c-9.883 9.024-17.315 22.554-23.03 39.7c-10.6 31.8-15.045 75.344-22.063 120.154h171.048c-7.017-44.81-11.462-88.354-22.062-120.154c-5.714-17.146-13.145-30.676-23.028-39.7a59.378 59.378 0 0 0-1.702-1.488C148.853 289.323 135.222 297 120 297c-15.222 0-28.852-7.678-38.73-19.342" />
          </svg>
          Cédula
        </label>
      </div>
       </div>
      <div class="col-md-4 form-floating mb-3">
        <input class="form-control" value="<?= $mostrar['nombre'] ?>" placeholder=" " name="nombre" required />
        <label class="ms-2">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
            <path fill="currentColor" d="M360 73c-14.43 0-27.79 7.71-38.055 21.395c-10.263 13.684-16.943 33.2-16.943 54.94c0 21.74 6.68 41.252 16.943 54.936c10.264 13.686 23.625 21.396 38.055 21.396s27.79-7.71 38.055-21.395C408.318 190.588 415 171.075 415 149.335c0-21.74-6.682-41.255-16.945-54.94C387.79 80.71 374.43 73 360 73m-240 96c-10.012 0-19.372 5.32-26.74 15.145C85.892 193.968 81 208.15 81 224c0 15.85 4.892 30.032 12.26 39.855C100.628 273.68 109.988 279 120 279c10.012 0 19.374-5.32 26.742-15.145c7.368-9.823 12.256-24.006 12.256-39.855c0-15.85-4.888-30.032-12.256-39.855C139.374 174.32 130.012 169 120 169m188.805 47.674a77.568 77.568 0 0 0-4.737 3.974c-13.716 12.524-23.816 31.052-31.53 54.198c-14.59 43.765-20.404 103.306-30.063 164.154h235.05c-9.66-60.848-15.476-120.39-30.064-164.154c-7.714-23.146-17.812-41.674-31.528-54.198a76.795 76.795 0 0 0-4.737-3.974c-12.84 16.293-30.942 26.994-51.195 26.994s-38.355-10.7-51.195-26.994zM81.27 277.658c-.573.485-1.143.978-1.702 1.488c-9.883 9.024-17.315 22.554-23.03 39.7c-10.6 31.8-15.045 75.344-22.063 120.154h171.048c-7.017-44.81-11.462-88.354-22.062-120.154c-5.714-17.146-13.145-30.676-23.028-39.7a59.378 59.378 0 0 0-1.702-1.488C148.853 289.323 135.222 297 120 297c-15.222 0-28.852-7.678-38.73-19.342" />
          </svg>
          Nombres
        </label>
      </div>
      <div class="col-md-4 form-floating mb-3">
        <input class="form-control" placeholder=" " name="apellido" required />
        <label class="ms-2">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
            <path fill="currentColor" d="M360 73c-14.43 0-27.79 7.71-38.055 21.395c-10.263 13.684-16.943 33.2-16.943 54.94c0 21.74 6.68 41.252 16.943 54.936c10.264 13.686 23.625 21.396 38.055 21.396s27.79-7.71 38.055-21.395C408.318 190.588 415 171.075 415 149.335c0-21.74-6.682-41.255-16.945-54.94C387.79 80.71 374.43 73 360 73m-240 96c-10.012 0-19.372 5.32-26.74 15.145C85.892 193.968 81 208.15 81 224c0 15.85 4.892 30.032 12.26 39.855C100.628 273.68 109.988 279 120 279c10.012 0 19.374-5.32 26.742-15.145c7.368-9.823 12.256-24.006 12.256-39.855c0-15.85-4.888-30.032-12.256-39.855C139.374 174.32 130.012 169 120 169m188.805 47.674a77.568 77.568 0 0 0-4.737 3.974c-13.716 12.524-23.816 31.052-31.53 54.198c-14.59 43.765-20.404 103.306-30.063 164.154h235.05c-9.66-60.848-15.476-120.39-30.064-164.154c-7.714-23.146-17.812-41.674-31.528-54.198a76.795 76.795 0 0 0-4.737-3.974c-12.84 16.293-30.942 26.994-51.195 26.994s-38.355-10.7-51.195-26.994zM81.27 277.658c-.573.485-1.143.978-1.702 1.488c-9.883 9.024-17.315 22.554-23.03 39.7c-10.6 31.8-15.045 75.344-22.063 120.154h171.048c-7.017-44.81-11.462-88.354-22.062-120.154c-5.714-17.146-13.145-30.676-23.028-39.7a59.378 59.378 0 0 0-1.702-1.488C148.853 289.323 135.222 297 120 297c-15.222 0-28.852-7.678-38.73-19.342" />
          </svg>
          Apellidos
        </label>
      </div>
      <div class="col-md-4 form-floating mb-3">
          <input class="form-control" type="number" id="numero_momento" placeholder=" " name="numero_momento" pattern="[0-9]+" required>
          <label class="ms-2" for="anio">Numero momento</label>
        </div>
    <thead>
      <tr>
        <th>Materias</th>
        <th>Lapso 1</th>
        <th>Lapso 2</th>
        <th>Lapso 3</th>
        <th>Definitiva</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($id_materias = $result->fetch_assoc()) { ?>
        <tr>
          <td><?= $id_materias['id_materias'] ?></td>
          <td><?= $mostrar['nombre'] ?></td>
          <td><?= $mostrar['apellido'] ?></td>
          <td><?= formatearFecha($mostrar['fecha_nacimiento']) ?></td>
          <td><?= calcularEdad($mostrar['fecha_nacimiento']) ?></td>
          <td><?= $mostrar['estado_nacimiento'] ?></td>
          <td><?= $mostrar['lugar_nacimiento'] ?></td>
          <td><?= $mostrar['genero'] ?></td>
          <td><?= $mostrar['nombresRepresentante'] . ' ' . $mostrar['apellidosRepresentante'] ?></td>
          <td>
            <form method="post">
              <button data-bs-toggle= "tooltip" title="Eliminar" class="btn btn-outline-danger fs-4 p-1" formaction="eliminar-estudiante.php?cedula=<?= $mostrar['cedula'] ?>">
                <i class="ri-delete-bin-line"></i>
              </button>
              <button data-bs-toggle= "tooltip" title="Editar" class="btn btn-outline-dark fs-4 p-1" formaction="editar-estudiante.php?cedula=<?= $mostrar['cedula'] ?>">
                <i class="ri-edit-box-line "></i>
              </button>
            </form>
          </td>
        </tr>
      <?php } ?>
    </tbody>
  </table>
</div>

<script src="../Assets/simple-datatables/simple-datatables.min.js"></script>
<script>
  const tablaCalificaciones = new simpleDatatables.DataTable("#tablaCalificaciones");
</script>
<?php include __DIR__ . '/partials/footer.php' ?>

