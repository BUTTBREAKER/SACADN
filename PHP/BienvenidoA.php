<?php

declare(strict_types=1);

include __DIR__ . '/partials/header.php';

$user = auth()['usuario'];

?>

<div class="container card card-body">
  <h1>Bienvenido, <?= $user ?></h1>
  <p>¡Gracias por iniciar sesión!</p>
</div>

<?php include __DIR__ . '/partials/footer.php' ?>
