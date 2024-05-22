<?php

declare(strict_types=1);

namespace SACADN\Controllers;

use mysqli;
use SACADN\View;

final readonly class LoginController
{
  public function __construct(private mysqli $db, private View $view)
  {
  }

  public function renderPage(): void
  {
    $sql = "SELECT COUNT(*) FROM usuarios WHERE rol = 'A'";
    $administratorsNumber = (int) $this->db->query($sql)->fetch_column();

    $this->view->render('pages/login', [
      'showAdministratorRegister' => $administratorsNumber === 0
    ]);
  }

  public function handleLogin(): never
  {
    if (!key_exists('usuario', $_POST) || !key_exists('clave', $_POST)) {
      // TODO: show 'Required credentials' error
      exit;
    }

    $query = "SELECT * FROM usuarios WHERE usuario = ?";

    $stmt = $this->db->prepare($query);
    $stmt->execute([$_POST['usuario']]);
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
      $this->showError(
        'Usuario no encontrado. Por favor, verifique su usuario.',
        'usuario_no_encontrado'
      );
    }

    $stmt->bind_result($id, $Name,$LastName, $idCard, $user, $hash, $role, $state,$time);
    $stmt->fetch();

    if ($state !== 'activo') {
      $this->showError(
        'Su usuario se encuentra inactivo.'
          . ' Por favor, comuníquese con su administrador.',
        'usuario_inactivo'
      );
    }

    if (!password_verify($_POST['clave'], $hash)) {
      $this->showError(
        'Credenciales incorrectas.'
          . ' Por favor, verifique su usuario y contraseña.',
        'credenciales_incorrectas'
      );
    }

    session_start();
    $_SESSION['usuario_id'] = $id;

    exit(header("Location: php/Bienvenido$role.php"));
  }

  private function showError(string $message, string $type): never
  {
    $title = match ($type) {
      'usuario_inactivo' => 'Usuario inactivo',
      'credenciales_incorrectas' => 'Credenciales incorrectas',
      'usuario_no_encontrado' => 'Usuario no encontrado',
      default => 'Error'
    };

    exit(<<<html
    <body>
      <link rel="stylesheet" href="./assets/sweetalert2/borderless.min.css" />
      <script src="./assets/sweetalert2/sweetalert2.min.js"></script>
      <script>
        Swal.fire({
          title: '$title',
          text: '$message',
          icon: 'error',
          showConfirmButton: false,
          timer: 5000
        }).then(() => location.href = './php/salir.php')
      </script>
    </body>
    html);
  }
}
