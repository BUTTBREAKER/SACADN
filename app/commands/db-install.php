<?php

$_ENV += require __DIR__ . '/../../.env.php';

switch (strtolower($_ENV['DB_CONNECTION'])) {
  case 'sqlite':
    $pdo = new PDO("sqlite:{$_ENV['DB_DATABASE']}");
    break;

  case 'mysql':
    $mysql = new mysqli($_ENV['DB_HOST'], $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD']);

    try {
      $mysql->select_db($_ENV['DB_DATABASE']);
    } catch (mysqli_sql_exception) {
      $mysql->query("CREATE DATABASE " . $_ENV['DB_DATABASE']);
    }

    $mysql->close();
    $dsn = "mysql:host={$_ENV['DB_HOST']}; dbname={$_ENV['DB_DATABASE']}; charset=utf8";

    try {
      $pdo = new PDO($dsn, $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD']);
    } catch (PDOException $error) {
      exit($error->getMessage());
    }

    break;

  default:
    exit("❌ DB_CONNECTION \"{$_ENV['DB_CONNECTION']}\" not supported!");
}

$sqlFilePath = dirname(__DIR__) . "/database/init.{$_ENV['DB_CONNECTION']}.sql";

foreach (explode(';', file_get_contents($sqlFilePath)) as $statement) {
  $statement = trim($statement);

  if (!$statement || $statement === "\n") {
    continue;
  }

  $pdo->query($statement);
}

echo "✔️ DB \"{$_ENV['DB_DATABASE']}\" installed correctly!";
