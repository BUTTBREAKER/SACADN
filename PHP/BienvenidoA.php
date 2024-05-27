<?php

declare(strict_types=1);

include __DIR__ . '/partials/header.php';

$user = auth()['usuario'];

?>

<div class="container card card-body">
  <h1>Bienvenido, <?= $user ?></h1>
  <p>¡Gracias por iniciar sesión!</p>
  <canvas id="myChart" style="width: 100%"></canvas>
</div>

<script>
  const ctx = document.getElementById('myChart')

  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
      datasets: [{
        label: '# of Votes',
        data: [12, 19, 3, 5, 2, 3],
        borderWidth: 1,
        backgroundColor: ['red', 'blue', 'yellow', 'green', 'purple', 'orange']
      }]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  })
</script>

<?php include __DIR__ . '/partials/footer.php' ?>
