<?php

require __DIR__ . '/../vendor/autoload.php';
// Incluir el archivo de conexión a la base de datos
/** @var mysqli */
$db = require_once __DIR__ . '/conexion_be.php';
include __DIR__ . '/partials/header.php';

/* Selecciona campo ci_repr y cambiale el nombre a cedula, ..., de la tabla representantes */
$sql = <<<SQL
  SELECT ci_repr AS cedula, nombre_completo AS nombres, apellido AS apellidos,
  fecha_nac AS fecha_nacimiento, estado as estado_nacimiento, lugar AS lugar_nacimiento,
  genero AS sexo, telefono, direccion, fech_repr AS fecha_registro FROM representantes
SQL;

$result = $db->query($sql);

?>

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
    padding-top: 50px;
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
    width: 540px;
    margin: 0 auto;
    padding: 20px 10px 5px 10px;
    background-color: white;
    border: 4px solid;
    border-color: #2DA0FB;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 16px
  }

  .formulario .input-contenedor {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 14px;
    justify-content: center;
  }

  .input-contenedor .contenedor-input:nth-last-child(1) {
    grid-column: 1/3;
  }

  .contenedor-label {
    display: flex;
    position: relative;
    top: 0.5rem;
    margin: 0 0 0 7px;
    padding: 0 3px;
  }

  .contenedor-input label.texto,
  .icono {
    color: #818CF8;
    background: white;

  }

  .contenedor-input label.texto {
    font-size: 14px;
    font-weight: 700;
    width: fit-content;
  }

  .icono {
    width: 13px;
    height: 13px;
  }

  .contenedor-input input,
  .contenedor-input select,
  .contenedor-input textarea {
    padding: 11px 10px;
    font-size: 16px;
    border: 2px #818CF8 solid;
    border-radius: 5px;
    background: white;
    width: 100%;
  }

  .textAreaDireccion {
    max-width: 475px;
    max-height: 70px;
  }

  .contenedor-input input:focus,
  .contenedor-input select {
    outline: none;
  }

  .formulario input[type="submit"]:hover {
    background-color: #72D6EE;
    border: 1px solid #2DA0FA;
    border-radius: 10px;
  }

  .contenedor1 select {
    width: 100%;
    padding: 5px;
    border: 1px solid #ccc;
    border-radius: 10px;
  }

  .container-botones {
    display: flex;
    width: 100%;
    justify-content: space-evenly;
  }

  .cancelar:hover {
    background-color: #da190b;
  }

  .boton-registrar,
  .boton-cancelar {
    padding: 0.7em 1.7em;
    font-size: 18px;
    border-radius: 0.5em;
    cursor: pointer;
    border: 1px solid #e8e8e8;
    transition: all 0.3s;
    box-shadow: 6px 6px 12px #c5c5c5, -6px -6px 12px #ffffff;
    color: white;
    font-weight: 700;
  }

  .boton-registrar {
    background: rgb(0, 255, 0, .7);

  }

  .boton-registrar:hover {
    border: 1px solid rgb(0, 255, 0);
    ;
  }

  .boton-cancelar {
    background: rgb(244, 67, 54, .8);

  }

  .boton-cancelar:hover {
    border: 1px solid rgb(244, 67, 54);
    ;
  }
</style>

<div class="pagina-content">
  <div class="contenedor">
    <form class="formulario" method="post" action="./guardar_representante.php" autocomplete="off">

      <div>
        <h3 style="text-align: center;">Nuevo Registro de Representante</h3>
      </div>


      <div class="input-contenedor">

        <div class="contenedor-input">
          <div class="contenedor-label">
            <i class="icono"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                <path fill="currentColor" d="M360 73c-14.43 0-27.79 7.71-38.055 21.395c-10.263 13.684-16.943 33.2-16.943 54.94c0 21.74 6.68 41.252 16.943 54.936c10.264 13.686 23.625 21.396 38.055 21.396s27.79-7.71 38.055-21.395C408.318 190.588 415 171.075 415 149.335c0-21.74-6.682-41.255-16.945-54.94C387.79 80.71 374.43 73 360 73m-240 96c-10.012 0-19.372 5.32-26.74 15.145C85.892 193.968 81 208.15 81 224c0 15.85 4.892 30.032 12.26 39.855C100.628 273.68 109.988 279 120 279c10.012 0 19.374-5.32 26.742-15.145c7.368-9.823 12.256-24.006 12.256-39.855c0-15.85-4.888-30.032-12.256-39.855C139.374 174.32 130.012 169 120 169m188.805 47.674a77.568 77.568 0 0 0-4.737 3.974c-13.716 12.524-23.816 31.052-31.53 54.198c-14.59 43.765-20.404 103.306-30.063 164.154h235.05c-9.66-60.848-15.476-120.39-30.064-164.154c-7.714-23.146-17.812-41.674-31.528-54.198a76.795 76.795 0 0 0-4.737-3.974c-12.84 16.293-30.942 26.994-51.195 26.994s-38.355-10.7-51.195-26.994zM81.27 277.658c-.573.485-1.143.978-1.702 1.488c-9.883 9.024-17.315 22.554-23.03 39.7c-10.6 31.8-15.045 75.344-22.063 120.154h171.048c-7.017-44.81-11.462-88.354-22.062-120.154c-5.714-17.146-13.145-30.676-23.028-39.7a59.378 59.378 0 0 0-1.702-1.488C148.853 289.323 135.222 297 120 297c-15.222 0-28.852-7.678-38.73-19.342" />
              </svg></i>
            <label class='texto' for="nom_repr">C.I</label>
          </div>
          <input type="number-tlf" id="registrarCedula" placeholder="Cédula" name="ci_repr" maxlength="    ttern=" [0-9]+" title="Número de cédula invalido, mínimo tener 8 carácteres" required>
        </div>

        <div class="contenedor-input">
          <div class="contenedor-label"> <i class="icono"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                <path fill="currentColor" d="M360 73c-14.43 0-27.79 7.71-38.055 21.395c-10.263 13.684-16.943 33.2-16.943 54.94c0 21.74 6.68 41.252 16.943 54.936c10.264 13.686 23.625 21.396 38.055 21.396s27.79-7.71 38.055-21.395C408.318 190.588 415 171.075 415 149.335c0-21.74-6.682-41.255-16.945-54.94C387.79 80.71 374.43 73 360 73m-240 96c-10.012 0-19.372 5.32-26.74 15.145C85.892 193.968 81 208.15 81 224c0 15.85 4.892 30.032 12.26 39.855C100.628 273.68 109.988 279 120 279c10.012 0 19.374-5.32 26.742-15.145c7.368-9.823 12.256-24.006 12.256-39.855c0-15.85-4.888-30.032-12.256-39.855C139.374 174.32 130.012 169 120 169m188.805 47.674a77.568 77.568 0 0 0-4.737 3.974c-13.716 12.524-23.816 31.052-31.53 54.198c-14.59 43.765-20.404 103.306-30.063 164.154h235.05c-9.66-60.848-15.476-120.39-30.064-164.154c-7.714-23.146-17.812-41.674-31.528-54.198a76.795 76.795 0 0 0-4.737-3.974c-12.84 16.293-30.942 26.994-51.195 26.994s-38.355-10.7-51.195-26.994zM81.27 277.658c-.573.485-1.143.978-1.702 1.488c-9.883 9.024-17.315 22.554-23.03 39.7c-10.6 31.8-15.045 75.344-22.063 120.154h171.048c-7.017-44.81-11.462-88.354-22.062-120.154c-5.714-17.146-13.145-30.676-23.028-39.7a59.378 59.378 0 0 0-1.702-1.488C148.853 289.323 135.222 297 120 297c-15.222 0-28.852-7.678-38.73-19.342" />
              </svg></i>
            <label class='texto' for="">Nombre completo</label>
          </div>
          <input type="text" id="registerNombreCompleto" placeholder="Nombre Completo" name="nombre_completo" pattern="[A-ZÁÉÍÓÚ],[a-záéíóú]" title="Por favor llenar los campos vacíos" required>
        </div>


        <div class="contenedor-input">
          <div class="contenedor-label">
            <i class="icono"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                <path fill="currentColor" d="M360 73c-14.43 0-27.79 7.71-38.055 21.395c-10.263 13.684-16.943 33.2-16.943 54.94c0 21.74 6.68 41.252 16.943 54.936c10.264 13.686 23.625 21.396 38.055 21.396s27.79-7.71 38.055-21.395C408.318 190.588 415 171.075 415 149.335c0-21.74-6.682-41.255-16.945-54.94C387.79 80.71 374.43 73 360 73m-240 96c-10.012 0-19.372 5.32-26.74 15.145C85.892 193.968 81 208.15 81 224c0 15.85 4.892 30.032 12.26 39.855C100.628 273.68 109.988 279 120 279c10.012 0 19.374-5.32 26.742-15.145c7.368-9.823 12.256-24.006 12.256-39.855c0-15.85-4.888-30.032-12.256-39.855C139.374 174.32 130.012 169 120 169m188.805 47.674a77.568 77.568 0 0 0-4.737 3.974c-13.716 12.524-23.816 31.052-31.53 54.198c-14.59 43.765-20.404 103.306-30.063 164.154h235.05c-9.66-60.848-15.476-120.39-30.064-164.154c-7.714-23.146-17.812-41.674-31.528-54.198a76.795 76.795 0 0 0-4.737-3.974c-12.84 16.293-30.942 26.994-51.195 26.994s-38.355-10.7-51.195-26.994zM81.27 277.658c-.573.485-1.143.978-1.702 1.488c-9.883 9.024-17.315 22.554-23.03 39.7c-10.6 31.8-15.045 75.344-22.063 120.154h171.048c-7.017-44.81-11.462-88.354-22.062-120.154c-5.714-17.146-13.145-30.676-23.028-39.7a59.378 59.378 0 0 0-1.702-1.488C148.853 289.323 135.222 297 120 297c-15.222 0-28.852-7.678-38.73-19.342" />
              </svg></i>
            <label class='texto' for="apell_repr">Apellidos</label>
          </div>
          <input type="text" id="registerApellido" placeholder="Apellidos" name="apellido" required>
        </div>


        <div class="contenedor-input">
          <div class="contenedor-label">
            <i class="icono"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 2048 2048">
                <path fill="currentColor" d="M1664 512h256v1536H256V512h256V384h128v128h896V384h128zm128 128h-128v128h128zm-256 0H640v128h896zm-1024 0H384v128h128zM384 1920h1408V896H384zM256 384V256H128v1408H0V128h256V0h128v128h896V0h128v128h256v128h-256v128h-128V256H384v128zm384 1024v-128h128v128zm256 0v-128h128v128zm256 0v-128h128v128zm256 0v-128h128v128zm-768 256v-128h128v128zm256 0v-128h128v128zm256 0v-128h128v128zm-256-512v-128h128v128zm256 0v-128h128v128zm256 0v-128h128v128z" />
              </svg></i>
            <label class='texto' for="f/n_repr">Fecha de nacimiento</label>
          </div>
          <input type="date" id="registerFecha_nac" placeholder="dia/mes/año" name="fecha_nac" required>
        </div>

        <div class="contenedor-input">
          <div class="contenedor-label">
            <i class="icono"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path fill="currentColor" d="M12 19.35q3.05-2.8 4.525-5.087T18 10.2q0-2.725-1.737-4.462T12 4Q9.475 4 7.738 5.738T6 10.2q0 1.775 1.475 4.063T12 19.35m0 1.975q-.35 0-.7-.125t-.625-.375Q9.05 19.325 7.8 17.9t-2.087-2.762q-.838-1.338-1.275-2.575T4 10.2q0-3.75 2.413-5.975T12 2q3.175 0 5.588 2.225T20 10.2q0 1.125-.437 2.363t-1.275 2.575Q17.45 16.475 16.2 17.9t-2.875 2.925q-.275.25-.625.375t-.7.125M12 12q.825 0 1.413-.587T14 10q0-.825-.587-1.412T12 8q-.825 0-1.412.588T10 10q0 .825.588 1.413T12 12" />
              </svg></i>
            <label class='texto' for="edo_repr">Estado</label>
          </div>
          <input type="text" id="registerEstado" placeholder="Estado" name="estado" required>
        </div>


        <div class="contenedor-input">
          <div class="contenedor-label"> <i class="icono"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path fill="currentColor" d="M12 21.325q-.35 0-.7-.125t-.625-.375Q9.05 19.325 7.8 17.9t-2.087-2.762q-.838-1.338-1.275-2.575T4 10.2q0-3.75 2.413-5.975T12 2q3.175 0 5.588 2.225T20 10.2q0 1.125-.437 2.363t-1.275 2.575Q17.45 16.475 16.2 17.9t-2.875 2.925q-.275.25-.625.375t-.7.125M12 12q.825 0 1.413-.587T14 10q0-.825-.587-1.412T12 8q-.825 0-1.412.588T10 10q0 .825.588 1.413T12 12" />
              </svg></i>
            <label class='texto' for="lug_repr">Lugar</label>
          </div>
          <input type="text" id="registerLugar" placeholder="Lugar" name="lugar" required>
        </div>

        <div class="contenedor-input">
          <div class="contenedor-label">
            <i class="icono"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32">
                <path fill="currentColor" d="M22 3v2h3.563l-3.375 3.406A6.962 6.962 0 0 0 18 7c-1.87 0-3.616.74-4.938 2.063a6.94 6.94 0 0 0 .001 9.875c.87.87 1.906 1.495 3.062 1.812c.114-.087.242-.178.344-.28a3.45 3.45 0 0 0 .874-1.532a4.906 4.906 0 0 1-2.875-1.407C13.524 16.588 13 15.336 13 14s.525-2.586 1.47-3.53C15.412 9.523 16.664 9 18 9s2.587.525 3.53 1.47A4.956 4.956 0 0 1 23 14c0 .865-.245 1.67-.656 2.406c.096.516.156 1.058.156 1.594c0 .498-.042.99-.125 1.47c.2-.163.378-.348.563-.532C24.26 17.614 25 15.87 25 14c0-1.53-.504-2.984-1.406-4.188L27 6.438V10h2V3zm-6.125 8.25c-.114.087-.242.178-.344.28c-.432.434-.714.96-.874 1.533c1.09.14 2.085.616 2.875 1.406c.945.943 1.47 2.195 1.47 3.53s-.525 2.586-1.47 3.53C16.588 22.477 15.336 23 14 23s-2.587-.525-3.53-1.47A4.948 4.948 0 0 1 9 18c0-.865.245-1.67.656-2.406A8.789 8.789 0 0 1 9.5 14c0-.498.042-.99.125-1.47c-.2.163-.377.348-.563.533C7.742 14.384 7 16.13 7 18c0 1.53.504 2.984 1.406 4.188L6.72 23.875l-2-2l-1.44 1.406l2 2l-2 2l1.44 1.44l2-2l2 2l1.405-1.44l-2-2l1.688-1.686A6.932 6.932 0 0 0 14 25c1.87 0 3.616-.74 4.938-2.063C20.26 21.616 21 19.87 21 18s-.74-3.614-2.063-4.938c-.87-.87-1.906-1.495-3.062-1.812" />
              </svg></i>
            <label class='texto' for="gen_repr">Género:</label>
          </div>
          <select id="genero" name="genero" required>
            <option value="">Selecciona una opción</option>
            <option value="masculino">Masculino</option>
            <option value="femenino">Femenino</option>
          </select>
        </div>

        <div class="contenedor-input">
          <div class="contenedor-label">
            <i class="icono"> <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path fill="currentColor" d="m16.556 12.906l-.455.453s-1.083 1.076-4.038-1.862s-1.872-4.014-1.872-4.014l.286-.286c.707-.702.774-1.83.157-2.654L9.374 2.86C8.61 1.84 7.135 1.705 6.26 2.575l-1.57 1.56c-.433.432-.723.99-.688 1.61c.09 1.587.808 5 4.812 8.982c4.247 4.222 8.232 4.39 9.861 4.238c.516-.048.964-.31 1.325-.67l1.42-1.412c.96-.953.69-2.588-.538-3.255l-1.91-1.039c-.806-.437-1.787-.309-2.417.317" />
              </svg></i>
            <label class='texto' for="tlf_repr">Teléfono</label>
          </div>
          <input type="number-tlf" id="registerTelefono" placeholder="Teléfono" name="telefono" required>
        </div>


        <div class="contenedor-input">
          <div class="contenedor-label">
            <i class="icono"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path fill="currentColor" d="M5 20v-9.15L2.2 13L1 11.4L12 3l4 3.05V4h3v4.35l4 3.05l-1.2 1.6l-2.8-2.15V20h-6v-6h-2v6zm2-2h2v-6h6v6h2V9.325l-5-3.8l-5 3.8zm3-7.975h4q0-.8-.6-1.313T12 8.2q-.8 0-1.4.513t-.6 1.312M9 18v-6h6v6v-6H9z" />
              </svg></i>
            <label class='texto' for="direcc_repr">Dirección</label>
          </div>
          <textarea class="textAreaDireccion" cols="6" id="registerDireccion" placeholder="Dirección" name="direccion" required></textarea>
        </div>
      </div>
      <div class="container-botones">
        <button class="boton-registrar" type="submit">Guardar</button>
        <!-- Cambiado a un enlace para regresar -->
        <a href="javascript:history.back()" class="boton-cancelar">Regresar</a>
      </div>
      <br>
  </div>
</div>
</form>
</div>

<?php include __DIR__ . '/partials/footer.php' ?>
