<div align="center">
  <h1>SACADN - Sistema de Gestión Escolar</h1>
  <img src="https://raw.githubusercontent.com/BUTTBREAKER/SACADN/main/Sacadn.ico" alt="Logo de la aplicación" height="150" />
</div>

---

## Requisitos

- PHP >= 8.2
- Composer >= 2
- Git >= 2

## Preparación del entorno

1. Clona este repositorio
```bash
git clone https://github.com/BUTTBREAKER/SACADN
```

2. Instala las dependencias.
> Nota: se harán unas verificaciones del entorno de PHP.
```bash
composer install
```

3. Configura el archivo `.env.php` con tu configuración de la base de datos de conexión `(tipo: mysql|sqlite, host, puerto, nombre, usuario y contraseña)`:
> Nota: Para SQLite se requiere una ruta absoluta válida.
```php
return [
  "DB_CONNECTION" => "mysql",
  // "DB_CONNECTION" => "sqlite",
  "DB_HOST" => "localhost",
  "DB_PORT" => 3306,
  "DB_DATABASE" => "sacadn",
  # ^ mysql
  // "DB_DATABASE" => __DIR__ . "/app/database/sacadn.db",
  # ^ sqlite
  "DB_USERNAME" => "root",
  "DB_PASSWORD" => ""
];
```

Luego ejecuta el comando:
```bash
composer db:install
```

y debería darte algo parecido a la siguiente salida:
```
✔️ DB "sacadn" installed correctly!
```
