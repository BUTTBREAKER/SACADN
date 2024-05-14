<?php

declare(strict_types=1);

use Psr\Container\ContainerExceptionInterface;
use SACADN\Controllers\LoginController;
use SACADN\Middlewares\EnsureUserIsNotAuthenticated;

require_once __DIR__ . '/app/bootstrap.php';

updateErrorsUrls(__DIR__ . '/.htaccess', 'views/errors');

try {
  /** @var LoginController */
  $loginController = container()->get(LoginController::class);

  container()->get(EnsureUserIsNotAuthenticated::class)->check();

  empty($_POST)
    ? $loginController->renderPage()
    : $loginController->handleLogin();
} catch (ContainerExceptionInterface $error) {
  file_put_contents(__DIR__ . '/../storage/logs/errors.log', $error);
  http_response_code(500);
}
