<?php
require_once __DIR__ . '/conexion_be.php';
include __DIR__ . '/partials/header.php';

// Obtener todos los períodos
$sql = "SELECT id, anio_inicio, estado FROM periodos ORDER BY anio_inicio DESC";
$result = $conexion->query($sql);
$periods = $result->fetch_all(MYSQLI_ASSOC);
?>

<title>Periodos aperturados</title>
<link rel="stylesheet" href="../Assets/simple-datatables/simple-datatables.css" />

<div class="container card card-body table-responsive">
  <?php if ($periods): ?>
    <table id="tablaPeriodos" class="table datatable">
      <thead>
        <tr>
          <th>Periodo</th>
          <th>Estado</th>
          <?php if ($role === 'A') : ?>
            <th>Opciones</th>
          <?php endif ?>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($periods as $period): ?>
          <tr>
            <td><?= htmlspecialchars("{$period['anio_inicio']}-" . ($period['anio_inicio'] + 1)) ?></td>
            <td><?= $period['estado'] === 'activo' ? 'Activo' : 'Inactivo' ?></td>
            <?php if ($role === 'A') : ?>
            <td>
              <button
                class="btn btn-<?= $period['estado'] === 'activo' ? 'danger' : 'success' ?>"
                onclick="confirmToggle(<?= $period['id'] ?>, '<?= $period['estado'] ?>')">
                <?= $period['estado'] === 'activo' ? 'Desactivar' : 'Activar' ?>
              </button>
            </td>
            <?php endif ?>
          </tr>
        <?php endforeach ?>
      </tbody>
    </table>
  <?php else: ?>
    <h2>No se encuentra aperturado ningún período actualmente</h2>
    <h5>¿Desea aperturar un nuevo período?</h5>
    <div class="text-center">
      <a href="nuevo_periodo.php" class="btn btn-primary">Aperturar período</a>
    </div>
  <?php endif ?>
</div>

<script src="../Assets/simple-datatables/simple-datatables.min.js"></script>

<script>
  function confirmToggle(id, estado) {
    console.log(`Intentando cambiar estado del periodo ${id} de ${estado} a ${estado === 'activo' ? 'inactivo' : 'activo'}`);
    const nuevoEstado = estado === 'activo' ? 'inactivo' : 'activo';
    Swal.fire({
      title: 'Confirmar',
      text: `¿Está seguro de ${estado === 'activo' ? 'desactivar' : 'activar'} este período?`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Sí, proceder',
      cancelButtonText: 'Cancelar',
      reverseButtons: true
    }).then((result) => {
      if (result.isConfirmed) {
        fetch('alternar-estado-periodo.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
              id_periodo: id,
              estado: nuevoEstado
            })
          })
          .then(response => response.json())
          .then(data => {
            console.log('Respuesta del servidor:', data);
            if (data.success) {
              Swal.fire('Éxito', 'El estado del período ha sido actualizado', 'success')
                .then(() => {
                  location.reload();
                });
            } else {
              Swal.fire('Error', data.message || 'Error desconocido', 'error');
            }
          })
          .catch(error => {
            console.error('Error:', error);
            Swal.fire('Error', 'Ocurrió un error al procesar la solicitud', 'error');
          });
      }
    });
  }

  new simpleDatatables.DataTable('#tablaPeriodos');
</script>

<?php include __DIR__ . '/partials/footer.php'; ?>
