<?php

declare(strict_types=1);

use Illuminate\Container\Container;
use Psr\Container\ContainerInterface;
use SACADN\View;

require_once __DIR__ . '/../vendor/autoload.php';
$_ENV += require __DIR__ . '/../.env.php';

date_default_timezone_set('America/Caracas');

function updateErrorsUrls(string $htaccessPath, string $errorsDir): void {
  $root = str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']);

  $errors = <<<htaccess
  ErrorDocument 404 $root/$errorsDir/404.html
  ErrorDocument 500 $root/$errorsDir/500.html
  htaccess;

  file_put_contents($htaccessPath, $errors);
}

function container(): ContainerInterface {
  static $container = null;

  if (!$container) {
    $container = new Container();

    $container->bind(mysqli::class, function (): mysqli {
      $mysql = new mysqli(
        $_ENV['DB_HOST'], $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD']
      );

      try {
        $mysql->select_db($_ENV['DB_DATABASE']);
      } catch (mysqli_sql_exception) {
        $mysql->query('CREATE DATABASE ' . $_ENV['DB_DATABASE']);
      }

      return $mysql;
    }, true);

    $container->bind(
      abstract: View::class,
      concrete: fn (): View => new View(__DIR__ . '/../views'),
      shared: true
    );
  }

  return $container;
}
