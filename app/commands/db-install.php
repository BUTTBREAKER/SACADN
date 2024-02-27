<?php

$_ENV += require __DIR__ . '/../../.env.php';

switch (strtolower($_ENV['DB_CONNECTION'])) {
  case 'sqlite':
    $pdo = new PDO("sqlite:{$_ENV['DB_DATABASE']}");
    break;

  case 'mysql':
    $dsn = "mysql:host={$_ENV['DB_HOST']}; dbname={$_ENV['DB_DATABASE']}; charset=utf8";
    $pdo = new PDO($dsn, $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD']);
    break;

  default:
    exit("❌ DB_CONNECTION \"{$_ENV['DB_CONNECTION']}\" not supported!");
}

$sqlFilePath = __DIR__ . "/../database/init.{$_ENV['DB_CONNECTION']}.sql";

foreach (explode(';', file_get_contents($sqlFilePath)) as $statement) {
  if (!$statement || $statement === "\n") {
    continue;
  }

  $pdo->query($statement);
}

echo "✔️ DB \"{$_ENV['DB_DATABASE']}\" installed correctly!";
