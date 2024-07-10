<?php

require __DIR__ . '/../vendor/autoload.php';
include __DIR__ . '/partials/header.php';
// Incluir el archivo de conexión a la base de datos
/** @var mysqli */
$db = require_once __DIR__ . '/conexion_be.php';



$sql = <<<SQL
  SELECT id, anio_inicio AS periodo FROM periodos
SQL;

$result = $db->query($sql);
?>

<div class="row mx-0 justify-content-center pb-4">
    <form class="card col-md-5 py-3" method="post" action="./guardar_momento.php" autocomplete="off">
      <h1 class="card-title h3 text-center">Nuevo Registro de Lapso</h1>
      <div class="card-body row">

  
        <div class="col-md-12 form-floating mb-3"> 
      <input class="form-control" type="number" id="numero_momento" placeholder=" " name="numero_momento" pattern="[0-9]+"required>
       <label class="ms-2" for="numero_matriculas">
       Numero lapso:
     </label>
     </div>

        <div class="col-md-6 form-floating mb-3">
          <input class="form-control" type="number" id="mes_inicio" placeholder="" name="mes_inicio" pattern="[0-9]+" required>
          <label class="ms-2" for="anio">
           <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 2048 2048">
            <path fill="currentColor" d="M1664 512h256v1536H256V512h256V384h128v128h896V384h128zm128 128h-128v128h128zm-256 0H640v128h896zm-1024 0H384v128h128zM384 1920h1408V896H384zM256 384V256H128v1408H0V128h256V0h128v128h896V0h128v128h256v128h-256v128h-128V256H384v128zm384 1024v-128h128v128zm256 0v-128h128v128zm256 0v-128h128v128zm256 0v-128h128v128zm-768 256v-128h128v128zm256 0v-128h128v128zm256 0v-128h128v128zm-256-512v-128h128v128zm256 0v-128h128v128zm256 0v-128h128v128z" />
          </svg>
          Mes inicio
        </label>
        </div>

        <div class="col-md-6 form-floating mb-3">
          <input class="form-control" type="number" id="dia_inicio" placeholder="" name="dia_inicio" pattern="[0-9]+" required>
          <label class="ms-2" for="anio">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 2048 2048">
            <path fill="currentColor" d="M1664 512h256v1536H256V512h256V384h128v128h896V384h128zm128 128h-128v128h128zm-256 0H640v128h896zm-1024 0H384v128h128zM384 1920h1408V896H384zM256 384V256H128v1408H0V128h256V0h128v128h896V0h128v128h256v128h-256v128h-128V256H384v128zm384 1024v-128h128v128zm256 0v-128h128v128zm256 0v-128h128v128zm256 0v-128h128v128zm-768 256v-128h128v128zm256 0v-128h128v128zm256 0v-128h128v128zm-256-512v-128h128v128zm256 0v-128h128v128zm256 0v-128h128v128z" />
          </svg>
           Dia inicio</label>
        </div>

        <div class="col-md-12 form-floating mb-3">
          <select class="form-select" id="genero" name="id_periodo" required placeholder=" "required>
            <option value="">Selecciona una opción</option>
            <?php while ($mostrar = $result->fetch_assoc()) { ?>
              <option value="<?= $mostrar['id'] ?>"><?= $mostrar['periodo'] ?></option>
            <?php } ?>
          </select>
          <label class="ms-2" for="anio">
             <i class="ri-calendar-line"></i>
           Periodo</label>
        </div>
        
      <div class="btn-group btn-group-lg mx-3">
      <button class="btn btn-success w-75">Guardar</button>
      <a href="javascript:history.back()" class="btn btn-outline-secondary">Regresar</a>
    </div>
  </form>
</div>
<?php include __DIR__ . '/partials/footer.php' ?>
