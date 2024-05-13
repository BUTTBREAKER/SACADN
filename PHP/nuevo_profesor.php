<?php

require __DIR__ . '/../vendor/autoload.php';
// Incluir el archivo de conexión a la base de datos
/** @var mysqli */
$db = require_once __DIR__ . '/conexion_be.php';
include_once __DIR__ . '/../Assets/Menu/Menu.php';

/* Selecciona campo ci_prof y cambiale el nombre a cedula, ..., de la tabla proferoser */
$sql = <<<SQL
  SELECT ci_prof AS cedula, nombre_completo AS nombres, apellido AS apellidos,
  fecha_nac AS fecha_nacimiento, estado as estado_nacimiento, lugar AS lugar_nacimiento,
  genero AS sexo, telefono, direccion, fech_prof AS fecha_registro FROM profesores
SQL;

$result = $db->query($sql);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Registro profesor</title>  
    <link rel="stylesheet" type="text/css" href="registro profesor.css">
</head>
<style type="text/css">
    .contenedor {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 0;
    padding: 10px 5px 5px 5px;
    width: 750px;
    box-shadow: 10px 10px 10px 10px rgba(0, 0, 0, 0.1);
    border: 1px solid;
    margin: 0 auto;

  }

  .title {
    justify-content: center;
    width: 100%;
    height: 1px;
    padding: 0px 1px 25px 1px;
    position: relative;
    color: black;
    font-size: 20px;
  }

  .title1 {
    display: flex;
    margin-block-start: none;
    margin-block-end: 11px;
    margin-inline-start: 10px;
    margin-inline-end: 10px;
    font-weight: none;
  }

  .formulario {
    width: 700px;
    margin: 0 auto;
    padding: 20px 10px 20px 10px;
    background-color: #E7F7EC;
    border: 4px solid;
    border-color: #2DA0FB;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);

  }

  .formulario input[type="text"] {
    width: 400px;
    padding: 5px 5px;
    border-radius: 30px;
  }

  .formulario input[type="date"] {
    width: 150px;
    padding: 3px 3px;
  }

  .formulario input[type="number"] {
    width: 150px;
    padding: 3px 3px;
    border-radius: 30px;
  }

  .formulario input[type="number-tlf"] {
    width: 150px;
    padding: 3px 3px;
    border-radius: 30px;
  }

  .formulario input [type="sudmit"] {
    padding: 15px;
    margin-top: 20px;
    background-color: #A1CFFF;
    color: white;
    border: none;
    border-radius: 30px;
    cursor: pointer;
    transition: background-color 0.3s ease;
  }


  .formulario input[type="submit"]:hover {
    background-color: #72D6EE;
    border: 1px solid #2DA0FA
  }

  .contenedor1 select {
    width: 35%;
    padding: 5px;
    border: 1px solid #ccc;
    border-radius: 10px;
  }

  .input-contenedor label {

    position: adsolute;
    top: -10px;
    left: 10px;
    background-color: #E7F7EC;
    padding: 0 5px;
    font-size: x-large;

  }

  .contenedor1 label {
    position: adsolute;
    top: 20px;
    left: 20px;
    color: black;
    transition: top 0.5s, font-size 0.5s, color 0.5s;
  }

  .formulario input:focus+label,
  .formulario input:valid+label {
    top: 20px;
    font-size: 20px;
    color: E7F7EC;

  }

  .representante {
    padding: 2px 3px;
  }

  .cancelar {
    padding: 15px;
    margin-top: 20px;
    color: white;
    border: none;
    border-radius: 30px;
    background-color: #72D6EE;
    border: 1px solid #2DA0FA;
    cursor: pointer;
    padding-inline-end: 10px;
    transition: background-color 0.3s ease
  }

  .representante {
    padding: 2px 3px;
  }

  .formulario button[type="submit"] {
    background-color: #4CAF50;
    border: none;
    color: white;
    padding: 15px 32px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin: 4px 2px;
    transition-duration: 0.4s;
    cursor: pointer;
    border-radius: 8px;
  }

  .formulario button[type="submit"]:hover {
    background-color: #45a049;
  }

  .cancelar {
    background-color: #f44336;
    border: none;
    color: white;
    padding: 15px 32px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin: 4px 2px;
    transition-duration: 0.4s;
    cursor: pointer;
    border-radius: 8px;
  }

  .cancelar:hover {
    background-color: #da190b;
  }
</style>
 <body>
     <div class="contenedor">
    <form class="formulario" method="post" action="./guardar_profesor.php" autocomplete="off">
     <div class="input-contenedor">
        <header class="header">
           <nav class="title">
     <h4 class="title1">Nuevo registro de Profesor</h4>
           </nav>
        </header>

           <div class="contenedor1">
              <i><svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 256 256"><path fill="#000000" d="M216 40H40a16 16 0 0 0-16 16v144a16 16 0 0 0 16 16h13.39a8 8 0 0 0 7.23-4.57a48 48 0 0 1 86.76 0a8 8 0 0 0 7.23 4.57H216a16 16 0 0 0 16-16V56a16 16 0 0 0-16-16M104 168a32 32 0 1 1 32-32a32 32 0 0 1-32 32m112 32h-56.57a63.93 63.93 0 0 0-13.16-16H192a8 8 0 0 0 8-8V80a8 8 0 0 0-8-8H64a8 8 0 0 0-8 8v96a8 8 0 0 0 6 7.75A63.72 63.72 0 0 0 48.57 200H40V56h176Z"/></svg></i>
              <label for="ci_prof">C.I</label>
          <input type="number-tlf" id="profesor" placeholder="Cédula" name="ci_prof" required>
               </div>
               
        <div class="contenedor1">
              <i><svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 256 256"><path fill="#000000" d="M216 40H40a16 16 0 0 0-16 16v144a16 16 0 0 0 16 16h13.39a8 8 0 0 0 7.23-4.57a48 48 0 0 1 86.76 0a8 8 0 0 0 7.23 4.57H216a16 16 0 0 0 16-16V56a16 16 0 0 0-16-16M104 168a32 32 0 1 1 32-32a32 32 0 0 1-32 32m112 32h-56.57a63.93 63.93 0 0 0-13.16-16H192a8 8 0 0 0 8-8V80a8 8 0 0 0-8-8H64a8 8 0 0 0-8 8v96a8 8 0 0 0 6 7.75A63.72 63.72 0 0 0 48.57 200H40V56h176Z"/></svg></i>
              <label for="nombre_completo">Nombres</label>
          <input type="text" id="profesor" placeholder="Nombres" name="nombre_completo" required>
               </div>
               

               <div class="contenedor1">
                <i><svg xmlns="http://www.w3.org/2000/svg" widthprofesor="25" height="25" viewBox="0 0 256 256"><path fill="#000000" d="M216 40H40a16 16 0 0 0-16 16v144a16 16 0 0 0 16 16h13.39a8 8 0 0 0 7.23-4.57a48 48 0 0 1 86.76 0a8 8 0 0 0 7.23 4.57H216a16 16 0 0 0 16-16V56a16 16 0 0 0-16-16M104 168a32 32 0 1 1 32-32a32 32 0 0 1-32 32m112 32h-56.57a63.93 63.93 0 0 0-13.16-16H192a8 8 0 0 0 8-8V80a8 8 0 0 0-8-8H64a8 8 0 0 0-8 8v96a8 8 0 0 0 6 7.75A63.72 63.72 0 0 0 48.57 200H40V56h176Z"/></svg></i>
                <label for="apellido">Apellidos</label>
          <input type="text" id="profesor" placeholder="Apellidos" name="apellido" required>
               </div>


               <div class="contenedor1">
                <i><svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 2048 2048"><path fill="#000000" d="M1664 512h256v1536H256V512h256V384h128v128h896V384h128zm128 128h-128v128h128zm-256 0H640v128h896zm-1024 0H384v128h128zM384 1920h1408V896H384zM256 384V256H128v1408H0V128h256V0h128v128h896V0h128v128h256v128h-256v128h-128V256H384v128zm384 1024v-128h128v128zm256 0v-128h128v128zm256 0v-128h128v128zm256 0v-128h128v128zm-768 256v-128h128v128zm256 0v-128h128v128zm256 0v-128h128v128zm-256-512v-128h128v128zm256 0v-128h128v128zm256 0v-128h128v128z"/></svg></i>
           <label for="fecha_nac">Fecha de nacimiento</label>
          <input type="date" id="profesor" placeholder="dia/mes/año" name="fecha_nac" required>
        </div>
      
          
               <div class="contenedor1">
                <i><svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24"><path fill="#000000" d="M12 19.35q3.05-2.8 4.525-5.087T18 10.2q0-2.725-1.737-4.462T12 4Q9.475 4 7.738 5.738T6 10.2q0 1.775 1.475 4.063T12 19.35m0 1.975q-.35 0-.7-.125t-.625-.375Q9.05 19.325 7.8 17.9t-2.087-2.762q-.838-1.338-1.275-2.575T4 10.2q0-3.75 2.413-5.975T12 2q3.175 0 5.588 2.225T20 10.2q0 1.125-.437 2.363t-1.275 2.575Q17.45 16.475 16.2 17.9t-2.875 2.925q-.275.25-.625.375t-.7.125M12 12q.825 0 1.413-.587T14 10q0-.825-.587-1.412T12 8q-.825 0-1.412.588T10 10q0 .825.588 1.413T12 12"/></svg></i>
                 <label for="estado">Estado de nacimiento</label>
          <input type="text" id="profesor" placeholder="Estado de nacimiento" name="estado" required>
               </div>
              

               <div class="contenedor1">
                <i><svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24"><path fill="#000000" d="M12 21.325q-.35 0-.7-.125t-.625-.375Q9.05 19.325 7.8 17.9t-2.087-2.762q-.838-1.338-1.275-2.575T4 10.2q0-3.75 2.413-5.975T12 2q3.175 0 5.588 2.225T20 10.2q0 1.125-.437 2.363t-1.275 2.575Q17.45 16.475 16.2 17.9t-2.875 2.925q-.275.25-.625.375t-.7.125M12 12q.825 0 1.413-.587T14 10q0-.825-.587-1.412T12 8q-.825 0-1.412.588T10 10q0 .825.588 1.413T12 12"/></svg></i>
                <label for="lugar">Lugar de nacimiento</label>
          <input type="text" id="profesor" placeholder="Lugar de nacimiento" name="lugar" required>
               </div>
              
               <div class="contenedor1">
                <i><svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 32 32"><path fill="#000000" d="M22 3v2h3.563l-3.375 3.406A6.962 6.962 0 0 0 18 7c-1.87 0-3.616.74-4.938 2.063a6.94 6.94 0 0 0 .001 9.875c.87.87 1.906 1.495 3.062 1.812c.114-.087.242-.178.344-.28a3.45 3.45 0 0 0 .874-1.532a4.906 4.906 0 0 1-2.875-1.407C13.524 16.588 13 15.336 13 14s.525-2.586 1.47-3.53C15.412 9.523 16.664 9 18 9s2.587.525 3.53 1.47A4.956 4.956 0 0 1 23 14c0 .865-.245 1.67-.656 2.406c.096.516.156 1.058.156 1.594c0 .498-.042.99-.125 1.47c.2-.163.378-.348.563-.532C24.26 17.614 25 15.87 25 14c0-1.53-.504-2.984-1.406-4.188L27 6.438V10h2V3zm-6.125 8.25c-.114.087-.242.178-.344.28c-.432.434-.714.96-.874 1.533c1.09.14 2.085.616 2.875 1.406c.945.943 1.47 2.195 1.47 3.53s-.525 2.586-1.47 3.53C16.588 22.477 15.336 23 14 23s-2.587-.525-3.53-1.47A4.948 4.948 0 0 1 9 18c0-.865.245-1.67.656-2.406A8.789 8.789 0 0 1 9.5 14c0-.498.042-.99.125-1.47c-.2.163-.377.348-.563.533C7.742 14.384 7 16.13 7 18c0 1.53.504 2.984 1.406 4.188L6.72 23.875l-2-2l-1.44 1.406l2 2l-2 2l1.44 1.44l2-2l2 2l1.405-1.44l-2-2l1.688-1.686A6.932 6.932 0 0 0 14 25c1.87 0 3.616-.74 4.938-2.063C20.26 21.616 21 19.87 21 18s-.74-3.614-2.063-4.938c-.87-.87-1.906-1.495-3.062-1.812"/></svg></i>  
          <label for="genero">Sexo del profesor:</label>
                   <select id="profesor" name="genero" required>
                        <option value="">Selecciona una opción</option>
                        <option value="masculino">Masculino</option>
                        <option value="femenino">Femenino</option>
                    </select>
               </div>
               
               <div class="contenedor1">
                 <i> <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24"><path fill="#000000" d="m16.556 12.906l-.455.453s-1.083 1.076-4.038-1.862s-1.872-4.014-1.872-4.014l.286-.286c.707-.702.774-1.83.157-2.654L9.374 2.86C8.61 1.84 7.135 1.705 6.26 2.575l-1.57 1.56c-.433.432-.723.99-.688 1.61c.09 1.587.808 5 4.812 8.982c4.247 4.222 8.232 4.39 9.861 4.238c.516-.048.964-.31 1.325-.67l1.42-1.412c.96-.953.69-2.588-.538-3.255l-1.91-1.039c-.806-.437-1.787-.309-2.417.317"/></svg></i>
             <label for="telefono">Teléfono</label>
          <input type="number-tlf" id="profesor" placeholder="Tlf" name="telefono" required>
               </div>
               

               <div class="contenedor1">
                <i><svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24"><path fill="#000000" d="M5 20v-9.15L2.2 13L1 11.4L12 3l4 3.05V4h3v4.35l4 3.05l-1.2 1.6l-2.8-2.15V20h-6v-6h-2v6zm2-2h2v-6h6v6h2V9.325l-5-3.8l-5 3.8zm3-7.975h4q0-.8-.6-1.313T12 8.2q-.8 0-1.4.513t-.6 1.312M9 18v-6h6v6v-6H9z"/></svg></i>
              <label for="direccion">Dirección</label>
          <input type="text" id="profesor" placeholder="Dirección" name="direccion" required>
               </div>
         <br>
            <div class="row">
              <button type="submit">Guardar</button>
              <!-- Cambiado a un enlace para regresar -->
              <a href="javascript:history.back()" class="cancelar">Regresar</a>
            </div>
          </div>
          <br>
        </div>
          </div>
           </form>
           </div>

 </body>
 </html>