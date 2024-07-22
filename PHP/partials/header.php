<?php

use Psr\Container\ContainerExceptionInterface;
use SACADN\Middlewares\EnsureUserIsAuthenticated;

require_once __DIR__ . '/../../app/bootstrap.php';

try {
  container()->get(EnsureUserIsAuthenticated::class)->check();

  $role = auth()['rol'];
} catch (ContainerExceptionInterface $error) {
  file_put_contents(__DIR__ . '/../../storage/logs/errors.log', $error);

  exit(http_response_code(500));
}

?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width" />
  <title>SACADN</title>
  <link rel="icon" href="../favicon.ico" />
  <link rel="stylesheet" href="../Assets/fonts/remixicon.css" />
  <link rel="stylesheet" href="../Assets/app.css" />
  <link rel="stylesheet" href="../Assets/simple-datatables/simple-datatables.css" />
  <link rel="stylesheet" href="../vendor/thomaspark/bootswatch/dist/sandstone/bootstrap.min.css" />
  <link rel="stylesheet" href="../vendor/select2/select2/dist/css/select2.min.css" />
  <link rel="stylesheet" href="../Assets/sweetalert2/borderless.min.css" />
  <link rel="stylesheet" href="../vendor/apalfrey/select2-bootstrap-5-theme/dist/select2-bootstrap-5-theme.min.css" />
  <script src="../Assets/ResizeObserver.global.js"></script>
  <script src="../Assets/chart.js"></script>
</head>

<body>
  <?php include __DIR__ . '/navigation.php';
