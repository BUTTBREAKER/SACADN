<?php
// Inicia una sesión de PHP
session_start();
// Comprueba si la clave 'usuario_id' existe en el array $_SESSION
if (!key_exists('usuario_id', $_SESSION)) {
   // Si no existe, redirige al usuario a la página 'salir.php'
   exit(header('Location: salir.php'));
}

require_once __DIR__ . '/../../PHP/conexion_be.php';
$rol = $conexion
   ->query("SELECT rol FROM usuarios WHERE id={$_SESSION['usuario_id']}")
   ->fetch_assoc()['rol'];
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

?>
<!DOCTYPE html>
<html lang="es">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge" />
   <meta name="viewport" content="width=device-width, initial-scale=1.0" />
   <title>SACADN</title>
   <link rel="icon" type="image/ico" href="../Sacadn.ico" />
   <!--=============== REMIXICONS ===============-->
   <link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet">
   <!--=============== CSS ===============-->
   <link rel="stylesheet" href="../Assets/Menu/css/styles.css">

</head>

<body>
   <!--=============== HEADER ===============-->
   <header class="header">
      <nav class="nav">
         <div class="nav__data">
            <a href="#" class="nav__logo">
               SACADN <img src="../Sacadn.ico" alt="Logo">
            </a>

            <div class="nav__toggle" id="nav-toggle">
               <i class="ri-menu-line nav__burger"></i>
               <i class="ri-close-line nav__close"></i>
            </div>
         </div>

         <!--=============== NAV MENU ===============-->
         <ul class="nav__list">
            <!-- Notas -->
            <li class="dropdown__item">
               <div class="nav__link">
                  Notas <i class="ri-arrow-down-s-line dropdown__arrow"></i>
               </div>
               <ul class="dropdown__menu">
                  <li>
                     <a href="Notas.php" class="dropdown__link">
                        <i class="ri-pie-chart-line"></i> Ver Notas
                     </a>
                  </li>
                  <li>
                     <a href="Periodos.php" class="dropdown__link">
                        <i class="ri-arrow-up-down-line"></i> Consultar Notas
                     </a>
                  </li>
               </ul>
            </li>

            <!-- Años Escolares -->
            <li class="dropdown__item">
               <div class="nav__link">
                  Años Escolares <i class="ri-arrow-down-s-line dropdown__arrow"></i>
               </div>
               <ul class="dropdown__menu">
                  <li>
                     <a href="#" class="dropdown__link">
                        <i class="ri-pie-chart-line"></i> Año Escolar-Semestre
                     </a>
                  </li>
                  <li>
                     <a href="Periodos.php" class="dropdown__link">
                        <i class="ri-arrow-up-down-line"></i> Ver Periodo
                     </a>
                  </li>
               </ul>
            </li>

            <!-- Asignaturas -->
            <li class="dropdown__item">
               <div class="nav__link">
                  Asignaturas <i class="ri-arrow-down-s-line dropdown__arrow"></i>
               </div>
               <ul class="dropdown__menu">
                  <li>
                     <a href="Asignaturas.php" class="dropdown__link">
                        <i class="ri-pie-chart-line"></i> Ver Asignaturas
                     </a>
                  </li>
                  <li>
                     <a href="./asignar-materias.php" class="dropdown__link">
                        <i class="ri-pie-chart-line"></i> Asignar materias
                     </a>
                  </li>
                  <li>
                     <a href="./materias.php" class="dropdown__link">
                        <i class="ri-arrow-up-down-line"></i> Materias
                     </a>
                  </li>
               </ul>
            </li>

            <!-- Profesores -->
            <li class="dropdown__item">
               <div class="nav__link">
                  Profesores <i class="ri-arrow-down-s-line dropdown__arrow"></i>
               </div>
               <ul class="dropdown__menu">
                  <li>
                     <a href="profesores.php" class="dropdown__link">
                        <i class="ri-pie-chart-line"></i> Lista de Profesores
                     </a>
                     <?php if ($rol === "A") : ?>
                     <a href="nuevo_profesor.php" class="dropdown__link">
                        <i class="ri-pie-chart-line"></i> Registrar Profesor
                     </a>
                     <?php endif ?>
                  </li>
               </ul>
            </li>

            <!-- Estudiantes -->
            <li class="dropdown__item">
               <div class="nav__link">
                  Estudiantes <i class="ri-arrow-down-s-line dropdown__arrow"></i>
               </div>
               <ul class="dropdown__menu">
                  <li>
                     <a href="Estudiantes.php" class="dropdown__link">
                        <i class="ri-pie-chart-line"></i> Lista De Estudiantes
                     </a>
                     <?php if ($rol === "A") : ?>
                     <a href="nuevo_estudiante.php" class="dropdown__link">
                        <i class="ri-pie-chart-line"></i> Registrar Estudiante
                     </a>
                     <?php endif ?>
                  </li>
               </ul>
            </li>

            <!-- Configuración -->
            <?php if ($rol === "A") : ?>
            <li class="dropdown__item">
               <div class="nav__link">
                  Configuración <i class="ri-arrow-down-s-line dropdown__arrow"></i>
               </div>
               <ul class="dropdown__menu">
                  <li>
                     <a href="respaldo.php" class="dropdown__link">
                        <i class="ri-pie-chart-line"></i> Respaldo
                     </a>
                  </li>
                  <li>
                     <a href="restauracion.php" class="dropdown__link">
                        <i class="ri-arrow-up-down-line"></i> Restauración
                     </a>
                  </li>
               </ul>
            </li>
            <?php endif ?>

            <!-- Representantes -->
            <li class="dropdown__item">
               <div class="nav__link">
                  Representantes <i class="ri-arrow-down-s-line dropdown__arrow"></i>
               </div>
               <ul class="dropdown__menu">
                  <li>
                     <a href="Representantes.php" class="dropdown__link">
                        <i class="ri-pie-chart-line"></i> Lista De Representantes
                     </a>
                     <?php if ($rol === "A") : ?>
                     <a href="nuevo_representante.php" class="dropdown__link">
                        <i class="ri-pie-chart-line"></i> Registrar Representante
                     </a>
                     <?php endif ?>
                  </li>
               </ul>
            </li>

            <!-- Salir -->
            <li><a href="salir.php" class="nav__link">Salir</a></li>
         </ul>
      </nav>
   </header>

   <!--=============== MAIN JS ===============-->
   <script src="../Assets/Menu/js/main.js"></script>
</body>
</html>
