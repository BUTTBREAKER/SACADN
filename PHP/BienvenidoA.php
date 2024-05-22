<?php

declare(strict_types=1);

include __DIR__ . '/partials/header.php';

$user = auth()['usuario'];

?>

<div class="container">
  <div class="row mt-3 justify-content-md-center">
    <div class="col-md-6">
      <h1>Bienvenido, <?= $user ?></h1>
      <p>¡Gracias por iniciar sesión!</p>
    </div>
  </div>
  <div class="row mt-3 justify-content-md-center">
    <!-- Aquí puedes agregar más contenido si es necesario -->
  </div>
</div>

<?php include __DIR__ . '/partials/footer.php' ?>
