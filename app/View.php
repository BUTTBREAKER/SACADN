<?php

declare(strict_types=1);

namespace SACADN;

final readonly class View {
  public function __construct(private string $viewsPath) {
  }

  public function render(string $view, array $data): void {
    extract($data);

    include "$this->viewsPath/$view.php";
  }
}
