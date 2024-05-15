<?php

declare(strict_types=1);

namespace SACADN\Middlewares;

use mysqli;

final readonly class EnsureUserIsAuthenticated
{
  public function __construct(private mysqli $db)
  {
  }

  public function check(): void
  {
    session_start();

    if (!key_exists('usuario_id', $_SESSION)) {
      exit(header('Location: salir.php'));
    }
  }
}
