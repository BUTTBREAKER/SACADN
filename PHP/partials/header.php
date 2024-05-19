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
  <link rel="stylesheet" href="../assets/fonts/remixicon.css" />
  <link rel="stylesheet" href="../assets/app.css" />
  <link rel="stylesheet" href="../assets/simple-datatables/simple-datatables.css" />
</head>

<body>
  <?php include __DIR__ . '/navigation.php';
