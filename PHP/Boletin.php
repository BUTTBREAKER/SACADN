<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Boletin</title>
  <link rel="stylesheet" href="../Assets/simple-datatables/simple-datatables.css">
</head>


<style type="text/css">
  .contenedor {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 0;
    padding: 10px 5px 10px 5px;
    width: 700px;
    box-shadow: 10px 10px 10px 10px rgba(0, 0, 0, 0.1);
    border: 1px solid;
    margin: 0 auto;

  }
  .pagina-content {
      padding-top: 50px;}

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
    width: 640px;
    margin: 0 auto;
    padding: 20px 10px 5px 10px;
    background-color: #E7F7EC;
    border: 4px solid;
    border-color: #2DA0FB;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);

  }

  .formulario input[type="text"] {
    width: 400px;
    padding: 5px 5px;
    border-radius: 10px;
  }

  .formulario input[type="date"] {
    width: 150px;
    padding: 3px 3px;
   border-radius: 10px;
  }

  .formulario input[type="number"] {
    width: 150px;
    padding: 3px 3px;
   border-radius: 10px;
  }

  .formulario input[type="number-tlf"] {
    width: 150px;
    padding: 3px 3px;
   border-radius: 10px;
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
    border-radius: 10px;
  }


  .formulario input[type="submit"]:hover {
    background-color: #72D6EE;
    border: 1px solid #2DA0FA;
    border-radius: 10px;
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
  <div class="pagina-content">
    <div class="contenedor">
      <form class="formulario" method="post" action="./guardar_representante.php" autocomplete="off">
        <div class="input-contenedor">
          <header class="header">
            <nav class="title">
              <div>
                <h3 style="text-align: center;">Calificaciones</h3>
              </div>
            </nav>
          </header>
          <br>
          <div class="contenedor1">
            <i><svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 512 512">
                <path fill="#000000" d="M360 73c-14.43 0-27.79 7.71-38.055 21.395c-10.263 13.684-16.943 33.2-16.943 54.94c0 21.74 6.68 41.252 16.943 54.936c10.264 13.686 23.625 21.396 38.055 21.396s27.79-7.71 38.055-21.395C408.318 190.588 415 171.075 415 149.335c0-21.74-6.682-41.255-16.945-54.94C387.79 80.71 374.43 73 360 73m-240 96c-10.012 0-19.372 5.32-26.74 15.145C85.892 193.968 81 208.15 81 224c0 15.85 4.892 30.032 12.26 39.855C100.628 273.68 109.988 279 120 279c10.012 0 19.374-5.32 26.742-15.145c7.368-9.823 12.256-24.006 12.256-39.855c0-15.85-4.888-30.032-12.256-39.855C139.374 174.32 130.012 169 120 169m188.805 47.674a77.568 77.568 0 0 0-4.737 3.974c-13.716 12.524-23.816 31.052-31.53 54.198c-14.59 43.765-20.404 103.306-30.063 164.154h235.05c-9.66-60.848-15.476-120.39-30.064-164.154c-7.714-23.146-17.812-41.674-31.528-54.198a76.795 76.795 0 0 0-4.737-3.974c-12.84 16.293-30.942 26.994-51.195 26.994s-38.355-10.7-51.195-26.994zM81.27 277.658c-.573.485-1.143.978-1.702 1.488c-9.883 9.024-17.315 22.554-23.03 39.7c-10.6 31.8-15.045 75.344-22.063 120.154h171.048c-7.017-44.81-11.462-88.354-22.062-120.154c-5.714-17.146-13.145-30.676-23.028-39.7a59.378 59.378 0 0 0-1.702-1.488C148.853 289.323 135.222 297 120 297c-15.222 0-28.852-7.678-38.73-19.342" />
              </svg></i>
            <label for="nom_repr">C.I</label>
            <input type="number" id="registrarCedula" placeholder="Cédula" name="ci_repr" maxlength="8" pattern="[0-9]+" title="Número de cédula invalido, mínimo tener 8 caracteres" required>
          </div>
          <div class="contenedor1">
            <i><svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 512 512">
                <path fill="#000000" d="M360 73c-14.43 0-27.79 7.71-38.055 21.395c-10.263 13.684-16.943 33.2-16.943 54.94c0 21.74 6.68 41.252 16.943 54.936c10.264 13.686 23.625 21.396 38.055 21.396s27.79-7.71 38.055-21.395C408.318 190.588 415 171.075 415 149.335c0-21.74-6.682-41.255-16.945-54.94C387.79 80.71 374.43 73 360 73m-240 96c-10.012 0-19.372 5.32-26.74 15.145C85.892 193.968 81 208.15 81 224c0 15.85 4.892 30.032 12.26 39.855C100.628 273.68 109.988 279 120 279c10.012 0 19.374-5.32 26.742-15.145c7.368-9.823 12.256-24.006 12.256-39.855c0-15.85-4.888-30.032-12.256-39.855C139.374 174.32 130.012 169 120 169m188.805 47.674a77.568 77.568 0 0 0-4.737 3.974c-13.716 12.524-23.816 31.052-31.53 54.198c-14.59 43.765-20.404 103.306-30.063 164.154h235.05c-9.66-60.848-15.476-120.39-30.064-164.154c-7.714-23.146-17.812-41.674-31.528-54.198a76.795 76.795 0 0 0-4.737-3.974c-12.84 16.293-30.942 26.994-51.195 26.994s-38.355-10.7-51.195-26.994zM81.27 277.658c-.573.485-1.143.978-1.702 1.488c-9.883 9.024-17.315 22.554-23.03 39.7c-10.6 31.8-15.045 75.344-22.063 120.154h171.048c-7.017-44.81-11.462-88.354-22.062-120.154c-5.714-17.146-13.145-30.676-23.028-39.7a59.378 59.378 0 0 0-1.702-1.488C148.853 289.323 135.222 297 120 297c-15.222 0-28.852-7.678-38.73-19.342" />
              </svg></i>
              <label for="">Nombres</label>
            <input type="text" id="registerNombreCompleto" placeholder="Nombres" name="nombre_completo" title="Por favor llenar los campos vacíos" required>
          </div>
          <div class="contenedor1">
            <i><svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 512 512">
                <path fill="#000000" d="M360 73c-14.43 0-27.79 7.71-38.055 21.395c-10.263 13.684-16.943 33.2-16.943 54.94c0 21.74 6.68 41.252 16.943 54.936c10.264 13.686 23.625 21.396 38.055 21.396s27.79-7.71 38.055-21.395C408.318 190.588 415 171.075 415 149.335c0-21.74-6.682-41.255-16.945-54.94C387.79 80.71 374.43 73 360 73m-240 96c-10.012 0-19.372 5.32-26.74 15.145C85.892 193.968 81 208.15 81 224c0 15.85 4.892 30.032 12.26 39.855C100.628 273.68 109.988 279 120 279c10.012 0 19.374-5.32 26.742-15.145c7.368-9.823 12.256-24.006 12.256-39.855c0-15.85-4.888-30.032-12.256-39.855C139.374 174.32 130.012 169 120 169m188.805 47.674a77.568 77.568 0 0 0-4.737 3.974c-13.716 12.524-23.816 31.052-31.53 54.198c-14.59 43.765-20.404 103.306-30.063 164.154h235.05c-9.66-60.848-15.476-120.39-30.064-164.154c-7.714-23.146-17.812-41.674-31.528-54.198a76.795 76.795 0 0 0-4.737-3.974c-12.84 16.293-30.942 26.994-51.195 26.994s-38.355-10.7-51.195-26.994zM81.27 277.658c-.573.485-1.143.978-1.702 1.488c-9.883 9.024-17.315 22.554-23.03 39.7c-10.6 31.8-15.045 75.344-22.063 120.154h171.048c-7.017-44.81-11.462-88.354-22.062-120.154c-5.714-17.146-13.145-30.676-23.028-39.7a59.378 59.378 0 0 0-1.702-1.488C148.853 289.323 135.222 297 120 297c-15.222 0-28.852-7.678-38.73-19.342" />
              </svg></i>
            <label for="apell_repr">Apellidos</label>
            <input type="text" id="registerApellido" placeholder="Apellidos" name="apellido" required>
          </div>
          
          <div style="">
          <table id=tablaCalificaciones class="datatable"></table>     
         <thead>
        <tr>
          <!-- <td>ID</td> -->
          <th>Materias</th>
          <th>Evaluaciones</th>
          <th>Definitva</th>
        </tr>
      </thead>

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