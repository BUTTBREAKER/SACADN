<?php

require __DIR__ . '/../app/bootstrap.php';

// Incluir el archivo de conexión a la base de datos
/** @var mysqli */
$db = require_once __DIR__ . '/conexion_be.php';
include __DIR__ . '/partials/header.php';

$sql = <<<SQL
  SELECT id, anio_inicio AS periodo FROM periodos
SQL;

$result = $db->query($sql);
?>

<title>Periodos registrados</title>
<link rel="stylesheet" href="../Assets/simple-datatables/simple-datatables.css" />

<div>
  <?php if ($result && $result->num_rows > 0): ?>
    <div class="container card card-body table-responsive">
      <table id="tablaPeriodos" class="datatable">
        <thead>
          <tr>
            <th>ID</th>
            <th>Periodo</th>
            <th>Opciones</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($mostrar = $result->fetch_assoc()): ?>
            <tr>
              <td>
                <?= htmlspecialchars($mostrar['id'], ENT_QUOTES, 'UTF-8') ?>
              </td>
              <td>
                <?= htmlspecialchars(
                  "{$mostrar['periodo']}-" . ($mostrar['periodo'] + 1),
                  ENT_QUOTES,
                  'UTF-8'
                ) ?>
              </td>
              <td>
                <form method="post">
                  <button formaction="periodos.php?id=<?= urlencode($mostrar['id']) ?>">
                    Ver periodo
                  </button>
                </form>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  <?php else: ?>
    <h2>No se encuentra registrado ningun periodo actualmente</h2>
    <h5>¿Desea registrar un nuevo periodo?</h5>
    <div class="row">
      <a href="nuevo_periodo.php">
        <button type="submit">Nuevo periodo</button>
      </a>
    </div>
  <?php endif ?>
</div>

<script src="../Assets/simple-datatables/simple-datatables.min.js"></script>
<script>new simpleDatatables.DataTable("#tablaPeriodos")</script>

<?php include __DIR__ . '/partials/footer.php';
