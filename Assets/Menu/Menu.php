<?php
   // Inicia una sesión de PHP
   session_start();

   // Comprueba si la clave 'usuario_id' existe en el array $_SESSION
   if (!key_exists('usuario_id', $_SESSION)) {
       // Si no existe, redirige al usuario a la página 'salir.php'
       exit(header('Location: salir.php'));
   }

   require_once __DIR__ . '/../../PHP/conexion_be.php';
// Prepara la consulta SQL para obtener el nombre del usuario
$query = "SELECT Usuario FROM usuarios WHERE id = ?";
if ($sentencia = $conexion->prepare($query)) {
    // Vincula los parámetros
    $sentencia->bind_param("i", $_SESSION['usuario_id']);

    // Ejecuta la consulta
    $sentencia->execute();

    // Almacena el resultado de la consulta
    $sentencia->bind_result($usuario);

    // Obtiene el resultado
    $sentencia->fetch();

    // Cierra la sentencia
    $sentencia->close();
}

// Cierra la conexión a la base de datos
$conexion->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge" />
   <meta name="viewport" content="width=device-width, initial-scale=1.0" />
   <title>SACADN</title>
   <link rel="icon" type="image/ico" href="../Sacadn.ico"/>
   <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" rel="stylesheet"/>
   <style>
       /* Establece los estilos por defecto para todos los elementos */
       * {
           padding: 0;
           margin: 0;
           text-decoration: none;
           list-style: none;
           box-sizing: border-box;
       }

       .navbar {
           background: #2DA0FA;
           height: 80px;
           width: 100%;
           display: flex;
           align-items: center;
           justify-content: space-between;
           padding: 0 20px;
       }

       .logo img {
           height: 80px;
       }

       .nav-links ul {
           display: flex;
           align-items: center;
       }

       .nav-links li {
           margin: 0 10px;
       }

       .nav-links a {
           color: #000;
           font-size: 18px;
           padding: 10px;
           border-radius: 3px;
           transition: background 0.3s ease;
       }

       .nav-links a:hover {
           background: #89BFFB;
           color: #fff;
       }

       .nav-submenu {
           display: none;
           position: absolute;
           background: #72D6EE;
           padding: 10px;
           border-radius: 3px;
           box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
       }

       .nav-links li:hover .nav-submenu {
           display: block;
       }

       .nav-submenu a {
           display: block;
           color: #000;
           padding: 5px 0;
       }

       .menu-toggle {
           display: none;
       }

       @media screen and (max-width: 768px) {
           .navbar {
               flex-direction: column;
               height: auto;
               justify-content: center; /* Centra los elementos del menú */
           }

           .nav-links {
               display: none;
               width: 100%;
               text-align: center;
               margin-top: 20px;
           }

           .nav-links.active {
               display: flex;
               flex-direction: column; /* Cambia la dirección del menú desplegado */
           }

           .nav-links ul {
               flex-direction: column;
               align-items: center; /* Centra los elementos del menú desplegado */
           }

           .nav-links li {
               margin: 10px 0;
           }

           .nav-links a {
               padding: 10px 0;
           }

           .nav-links li:hover .nav-submenu {
               display: block;
               position: static; /* Cambia la posición del submenú al mostrarlo */
           }

           .menu-toggle {
               display: block;
               position: absolute;
               top: 20px;
               right: 20px;
               background: transparent;
               border: none;
               cursor: pointer;
           }

           .menu-toggle i {
               font-size: 24px;
               color: #fff;
           }
       }
   </style>
</head>
<body>
    <nav class="navbar">
        <div class="logo">
            <img src="../Sacadn.ico" alt="SACADN Logo">
        </div>
        <button class="menu-toggle">
            <i class="fas fa-bars"></i>
        </button>
        <div class="nav-links">
            <ul>
                <li><a class="active" href="#">Inicio</a></li>
                <li>
                    <a href="#">Notas</a>
                </li>
                <li>
                    <a href="#">Años escolares</a>
                    <div class="nav-submenu">
                        <a href="#">Año Escolar - Semestres</a>
                        <a href="#">Ver Periodos</a>
                    </div>
                </li>
                <li>
                    <a href="#">Asignaturas</a>
                    <div class="nav-submenu">
                        <a href="#">Ver Asignaturas</a>
                        <a href="#">Materias</a>
                    </div>
                </li>
                <li>
                    <a href="#">Estudiantes</a>
                    <div class="nav-submenu">
                        <a href="#">Lista Estudiantes</a>
                    </div>
                </li>
                <li>
                    <a href="#">Profesores</a>
                    <div class="nav-submenu">
                        <a href="#">Lista Profesores</a>
                    </div>
                </li>
                <li>
                    <a href="#">Representantes</a>
                    <div class="nav-submenu">
                        <a href="#">Lista Representantes</a>
                    </div>
                </li>
                <li><a href="salir.php">Salir</a></li>
            </ul>
        </div>
    </nav>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const menuToggle = document.querySelector(".menu-toggle");
            const navLinks = document.querySelector(".nav-links");

            menuToggle.addEventListener("click", function () {
                navLinks.classList.toggle("active");
            });
        });
   </script>
</body>
</html>
