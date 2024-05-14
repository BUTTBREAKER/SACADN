<?php

declare(strict_types=1);

namespace SACADN\Middlewares;

use mysqli;

final readonly class EnsureUserIsNotAuthenticated {
  public function __construct(private mysqli $db) {
  }

  public function check(): void {
    session_start();

    if (key_exists('usuario_id', $_SESSION)) {
      $query = "SELECT rol FROM usuarios WHERE id={$_SESSION['usuario_id']}";
      $rol = $this->db->query($query)->fetch_column();
      header("Location: PHP/Bienvenido$rol.php");

      exit;
    }
  }
}
