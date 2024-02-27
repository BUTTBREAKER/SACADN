<?php
   // Inicia una sesión de PHP
   session_start();

   // Comprueba si la clave 'usuario_id' existe en el array $_SESSION
   if (!key_exists('usuario_id', $_SESSION)) {
       // Si no existe, redirige al usuario a la página 'salir.php'
       exit(header('Location: salir.php'));
   }

   // Asigna el valor de la clave 'usuario_id' en $_SESSION a la variable $nombre_usuario
   $nombre_usuario = $_SESSION['usuario_id'];
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

       /* Establece los estilos para el encabezado de navegación */
       .navbar {
           background: #2fcdcd;
           height: 80px;
           width: 100%;
           display: flex;
           align-items: center;
           justify-content: space-between;
           padding: 0 20px;
       }

       /* Establece los estilos para el logotipo */
       .logo img {
           height: 80px;
       }

       /* Establece los estilos para los enlaces de navegación */
       .nav-links ul {
           display: flex;
           align-items: center;
       }

       .nav-links li {
           margin: 0 10px;
       }

       .nav-links a {
           color: #000; /* Cambia el color del texto del menú a negro */
           font-size: 18px;
           padding: 10px;
           border-radius: 3px;
           transition: background 0.3s ease;
       }

       .nav-links a:hover {
           background: #000090;
           color: #fff;
       }

       /* Establece los estilos para el submenú */
       .nav-submenu {
           display: none; /* Oculta el submenú por defecto */
           position: absolute;
           background: #2fcdcd;
           padding: 10px;
           border-radius: 3px;
           box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
       }

       /* Muestra el submenú cuando el usuario hace hover sobre el elemento de navegación */
       .nav-links li:hover .nav-submenu {
           display: block;
       }

       .nav-submenu a {
           display: block;
           color: #000; /* Cambia el color del texto del submenú a negro */
           padding: 5px 0;
       }

       /* Establece los estilos para el botón de menú */
       .menu-toggle {
           display: none; /* Oculta el botón de menú por defecto */
       }

       /* Establece los estilos para el menú desplegable en pantallas pequeñas */
       @media screen and (max-width: 768px) {
           .navbar {
               flex-direction: column;
               height: auto;
           }

           .nav-links {
               display: none;
               width: 100%;
               text-align: center;
               margin-top: 20px;
           }

           /* Muestra el menú desplegable cuando el usuario hace clic en el botón de menú */
           .nav-links.active {
               display: flex;
               justify-content: space-between;
           }

           .nav-links ul {
               flex-direction: column;
           }

           .nav-links li {
               margin: 10px 0;
           }

           .nav-links a {
               padding: 10px 0;
           }

           /* Oculta el submenú cuando el usuario hace clic en el botón de menú */
           .nav-links li:hover .nav-submenu {
               display: none;
           }

           /* Muestra el submenú cuando el usuario hace clic en el botón de menú */
           .nav-links li:hover .nav-submenu.active {
               display: block;
           }

           .menu-toggle {
               display: block; /* Muestra el botón de menú en pantallas pequeñas */
               position: absolute;
               top: 20px;
               right: 20px;
               background: transparent;
               border: none;
               cursor: pointer;
           }

           .menu-toggle i {
               font-size: 24px; /* Tamaño del ícono */
               color: #fff; /* Color del ícono */
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
            <i class="fas fa-bars"></i> <!-- Ícono de hamburguesa de Font Awesome -->
        </button>
        <div class="nav-links">
            <ul>
                <li><a class="active" href="#">Inicio</a></li>
                <li>
                    <a href="#">Notas</a>
                    <div class="nav-submenu">
                        <a href="#">Año Escolar - Semestres</a>
                        <a href="#">Ver Periodos</a>
                    </div>
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
    <<script>
    document.addEventListener("DOMContentLoaded", function () {
        const menuToggle = document.querySelector(".menu-toggle");
        const navLinks = document.querySelector(".nav-links");
        const subMenus = document.querySelectorAll(".nav-submenu");

           // Agrega un event listener al botón de menú
           menuToggle.addEventListener("click", function () {
               // Alterna la clase 'active' en el contenedor de enlaces de navegación
               navLinks.classList.toggle("active");

               // Oculta los submenús cuando se hace clic en el botón de menú
               subMenus.forEach(subMenu => {
                   subMenu.classList.remove("active");
               });
           });

           // Agrega un event listener a cada elemento de navegación
           document.querySelectorAll(".nav-links > ul > li").forEach(link => {
               link.addEventListener("click", function () {
                   // Selecciona el submenú asociado al elemento de navegación
                   const subMenu = this.querySelector(".nav-submenu");

                   // Alterna la clase 'active' en el submenú
                   if (subMenu) {
                       subMenu.classList.toggle("active");
                   }
               });
           });
       });
   </script>
</body>
</html>
