<?php
session_start();

if (!key_exists('usuario_id', $_SESSION)) {
  exit(header('Location: salir.php'));
}

// Obtener el nombre de usuario de la sesión
$nombre_usuario = $_SESSION['usuario_id'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SACADN</title>
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
}

.enlace {
    position: absolute;
    padding: -px 40px 20px ;
}

.logo {
    height: 100px;
    display: block; /* Asegura que el logo se comporte como un bloque */
    margin: 0 auto; /* Centra horizontalmente */
}

.nav-links {
    float: right;
    margin-right: 20px;
}

.nav-links li {
    display: inline-block;
    line-height: 80px;
    margin: 0 5px;
}

.nav-links li a {
    color: #fff;
    font-size: 18px;
    padding: 7px 13px;
    border-radius: 3px;
    text-transform: uppercase;
}

.nav-links li a.active,
.nav-links li a:hover {
    background: #000090;
    transition: .5s;
}

.checkbtn {
    font-size: 30px;
    color: #fff;
    float: right;
    line-height: 80px;
    margin-right: 40px;
    cursor: pointer;
    display: none;
}

#check {
    display: none;
}

section {
    background: url(fondo.jpg) no-repeat;
    background-size: cover;
    background-position: center center;
    height: calc(100vh - 80px);
}

@media (max-width: 858px) {
    .enlace {
        padding-left: 20px;
    }

    .checkbtn {
        display: block;
    }

    .nav-links {
        position: fixed;
        width: 100%;
        height: 100vh;
        background: #2c3e50;
        top: 80px;
        left: -100%;
        text-align: center;
        transition: all .5s;
    }

    .nav-links li {
        display: block;
        margin: 50px 0;
        line-height: 30px;
    }

    .nav-links li a {
        font-size: 20px;
    }

    .nav-links li a:hover,
    .nav-links li a.active {
        background: none;
        color: red;
    }

    .cerrar-sesion {
        color: #fff;
        background-color: #ff0000;
        padding: 10px 20px;
        border-radius: 5px;
        margin-top: 20px;
    }

    #check:checked ~ .nav-links {
        left: 0;
    }
}

    </style>
</head>
<body>
    <nav class="navbar">
        <input type="checkbox" id="check">
        <label for="check" class="checkbtn">
            <i class="fas fa-bars"></i>
        </label>
        
        <a href="#" class="enlace">
            <img src="../Sacadn.ico" alt="SACADN Logo" class="logo">
        </a>

        <ul class="nav-links">
            <li><a class="active" href="#">Inicio</a></li>
            <li><a href="#">Notas</a></li>
            <li><a href="#">Estudiantes</a></li>
            <li><a href="#">Profesores</a></li> 
            <li><a href="#">Acerca de</a></li>
            <li><a href="#">Contacto</a></li>
            <li><a href="salir.php">Salir</a></li>
        </ul>
    </nav>
</body>
</html>
