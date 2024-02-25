<?php
session_start();

if (!key_exists('usuario_id', $_SESSION)) {
    exit(header('Location: salir.php'));
}

$nombre_usuario = $_SESSION['usuario_id'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SACADN</title>
    <link rel="icon" type="image/ico" href="../Sacadn.ico"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            padding: 0;
            margin: 0;
            text-decoration: none;
            list-style: none;
            box-sizing: border-box;
        }

        .navbar {
            background: #2fcdcd;
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
            color: #fff;
            font-size: 18px;
            padding: 10px;
            border-radius: 3px;
            transition: background 0.3s ease;
        }

        .nav-links a:hover {
            background: #000090;
            color: #fff;
        }

        .nav-submenu {
            display: none;
            position: absolute;
            background: #2fcdcd;
            padding: 10px;
            border-radius: 3px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .nav-links li:hover .nav-submenu {
            display: block;
        }

        .nav-submenu a {
            display: block;
            color: #fff;
            padding: 5px 0;
        }

        .menu-toggle {
            display: none; /* Oculta el botón de menú por defecto */
        }

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

            .nav-links li:hover .nav-submenu {
                display: none;
            }

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

        menuToggle.addEventListener("click", function () {
            navLinks.classList.toggle("active");

            // Mostrar u ocultar los submenús cuando se hace clic en el botón de menú
            subMenus.forEach(subMenu => {
                subMenu.classList.remove("active");
            });
        });

        // Mostrar u ocultar los submenús cuando se hace clic en los elementos principales del menú
        document.querySelectorAll(".nav-links > ul > li").forEach(link => {
            link.addEventListener("click", function () {
                const subMenu = this.querySelector(".nav-submenu");
                if (subMenu) {
                    subMenu.classList.toggle("active");
                }
            });
        });
    });
</script>
</body>
</html>
