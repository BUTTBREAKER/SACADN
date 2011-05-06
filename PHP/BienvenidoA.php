<?php

declare(strict_types=1);

include __DIR__ . '/partials/header.php';

$user = auth()['usuario'];
$conn = require_once __DIR__ . '/conexion_be.php';

$query_asignaciones_estudiantes = "SELECT COUNT(*) AS count FROM asignaciones_estudiantes";
$query_materias = "SELECT COUNT(*) AS count FROM materias";
$query_profesores = "SELECT COUNT(*) AS count FROM profesores";

$result_asignaciones_estudiantes = $conn->query($query_asignaciones_estudiantes);
$result_materias = $conn->query($query_materias);
$result_profesores = $conn->query($query_profesores);

$count_asignaciones_estudiantes = $result_asignaciones_estudiantes->fetch_assoc()['count'];
$count_materias = $result_materias->fetch_assoc()['count'];
$count_profesores = $result_profesores->fetch_assoc()['count'];

$conn->close();
?>

<div class="container card card-body">
  <h1>Bienvenido, <?= $user ?></h1>
  <p>¡Gracias por iniciar sesión!</p>
  <div class="row">
    <div class="col-md-6">
      <canvas id="myChart"></canvas>
    </div>
  </div>
</div>

<script>
  const ctx = document.getElementById('myChart').getContext('2d');

  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ['Primero A', 'Materias', 'Profesores'],
      datasets: [{
        label: 'Cantidad de Estudiantes',
        data: [<?= $count_asignaciones_estudiantes ?>, <?= $count_materias ?>, <?= $count_profesores ?>],
        borderWidth: 1,
        backgroundColor: ['#C2EFB3', '#C4B0B3', '#4C56D8']
      }]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      },
      responsive: true,
      maintainAspectRatio: false
    }
  });
</script>

<?php include __DIR__ . '/partials/footer.php' ?>
