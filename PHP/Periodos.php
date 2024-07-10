<?php

use SACADN\Repositories\PeriodRepository;

require __DIR__ . '/../app/bootstrap.php';
include __DIR__ . '/partials/header.php';

/** @var PeriodRepository */
$periodRepository = container()->get(PeriodRepository::class);
$periods = $periodRepository->all();

?>

<title>Periodos aperturados</title>
<link rel="stylesheet" href="../Assets/simple-datatables/simple-datatables.css" />

<div class="container card card-body table-responsive">
  <?php if ($periods): ?>
    <table id="tablaPeriodos" class="table datatable">
      <thead>
        <tr>
          <th>ID</th>
          <th>Periodo</th>
          <th>Opciones</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($periods as $period): ?>
          <tr>
            <td><?= $period['id'] ?></td>
            <td>
              <?= "{$period['anio_inicio']}-" . ($period['anio_inicio'] + 1) ?>
            </td>
            <td>
              <a
                class="btn btn-outline-secondary"
                href="detalles-periodo.php?id=<?= $period['id'] ?>">
                Ver detalles
              </a>
            </td>
          </tr>
        <?php endforeach ?>
      </tbody>
    </table>
  <?php else: ?>
    <h2>No se encuentra aperturado ningun período actualmente</h2>
    <h5>¿Desea aperturar un nuevo período?</h5>

    <div class="text-center">
      <a href="nuevo_periodo.php" class="btn btn-primary">Aperturar período</a>
    </div>
  <?php endif ?>
</div>

<script src="../Assets/simple-datatables/simple-datatables.min.js"></script>

<script>
  new simpleDatatables.DataTable('#tablaPeriodos')
</script>

<?php include __DIR__ . '/partials/footer.php';
