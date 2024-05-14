<?php

declare(strict_types=1);

function updateErrorsUrls(string $htaccessPath, string $errorsDir): void {
  $root = str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']);

  $errors = <<<htaccess
  ErrorDocument 404 $root/$errorsDir/404.html
  ErrorDocument 500 $root/$errorsDir/500.html
  htaccess;

  file_put_contents($htaccessPath, $errors);
}
