<?php

declare(strict_types=1);

include __DIR__ . '/partials/header.php';

$user = auth()['usuario'];
$conn = require_once __DIR__ . '/conexion_be.php';
// Consulta para obtener la cantidad de estudiantes por año y sección
$query_estudiantes_por_seccion = "
  SELECT niveles_estudio.nombre AS anio, secciones.nombre AS seccion, COUNT(inscripciones.id_estudiante) AS cantidad_estudiantes
  FROM inscripciones
  JOIN secciones ON inscripciones.id_seccion = secciones.id
  JOIN niveles_estudio ON secciones.id_nivel_estudio = niveles_estudio.id
  GROUP BY anio, seccion
";

$result_estudiantes_por_seccion = $conn->query($query_estudiantes_por_seccion);

$secciones = [];
$cantidad_estudiantes = [];

while ($row = $result_estudiantes_por_seccion->fetch_assoc()) {
    $secciones[] = $row['anio'] . ' - ' . $row['seccion']; // Año y sección juntos
    $cantidad_estudiantes[] = $row['cantidad_estudiantes']; // Cantidad de estudiantes en esa sección
}



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
      labels: <?= json_encode($secciones) ?>,  // Nombres de las secciones con año
      datasets: [{
        label: 'Cantidad de Estudiantes',
        data: <?= json_encode($cantidad_estudiantes) ?>, // Cantidad de estudiantes por sección y año
        borderWidth: 1,
        backgroundColor: '#C2EFB3'
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
