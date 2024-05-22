<?php

require __DIR__ . '/../vendor/autoload.php';

/** @var mysqli */
$db = require_once __DIR__ . '/conexion_be.php';

if (isset($_GET['nivel_estudio'])) {
  $sentencia = $db->prepare('SELECT nombre FROM secciones WHERE id_nivel_estudio = ?');
  $sentencia->execute([$_GET['nivel_estudio']]);

  $secciones = array_map(function (array $info): string {
    return $info['nombre'];
  }, $sentencia->get_result()->fetch_all(MYSQLI_ASSOC));

  exit(json_encode($secciones));
}

include __DIR__ . '/partials/header.php';

$stmt_niveles = $conexion->prepare("SELECT id, nombre FROM niveles_estudio");
$stmt_niveles->execute();
$result_niveles = $stmt_niveles->get_result();
$stmt_niveles->close();


$sql = 'SELECT id, nombre FROM materias';
$result = $db->query($sql);
$materias = $result->fetch_all(MYSQLI_ASSOC);

?>

<style>
  /* Estilos CSS */
  .container_asignacion {
    margin: 0 auto;
    padding: 20px;
    background-color: aliceblue;
  }

  body {
    font-family: Arial, sans-serif;
  }

  form {
    max-width: 400px;
    margin: 0 auto;
    padding: 20px;
    border: 1px solid #ccc;
    border-radius: 8px;
    background-color: #f9f9f9;
  }

  input[type="text"],
  select {
    width: 100%;
    padding: 10px;
    margin: 5px 0;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box;
    font-size: 16px;
  }

  input[type="submit"] {
    width: 100%;
    padding: 10px;
    margin-top: 10px;
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
  }
</style>

<form class="container_asignacion" action="./Procesophp/procesar-asignacion.php" method="post">
  <select class="anio" name="anio" required>
    <option value="" selected disabled>Seleccione un nivel de estudio</option>
    <?php while ($row = $result_niveles->fetch_assoc()) : ?>
      <option value="<?= $row['id'] ?>"><?= $row['nombre'] ?></option>
    <?php endwhile; ?>
  </select>
  <div class="seccion1" id="seleccionador-de-secciones"></div>
  <select class="materias" name="materias[]" required multiple>
    <?php foreach ($materias as $materia) : ?>
      <option value="<?= $materia['id'] ?>"><?= $materia['nombre'] ?></option>
    <?php endforeach ?>
  </select>
  <div id="contenedor-boton"></div>
</form>

<script>
  const nivel_estudio = document.querySelector('[name="anio"]')
  const seleccionadorDeSecciones = document.getElementById('seleccionador-de-secciones')
  const contenedorBoton = document.getElementById('contenedor-boton')

  nivel_estudio.addEventListener('change', () => {
    const opcionSeleccionada = nivel_estudio.value

    fetch(`${location.href}?nivel_estudio=${opcionSeleccionada}`)
      .then(respuesta => respuesta.json())
      .then(secciones => {
        seleccionadorDeSecciones.innerHTML = `
          <select class="seccion" name="seccion" required>
            <option class="seleccionador" disabled selected >Seleccione una sección</option>

            ${secciones.map(seccion => `<option>${seccion}</option>`).join('')}
          </select>
        `

        contenedorBoton.innerHTML = `<button type="submit">Asignar</button>`
      })
  })
</script>

<?php include __DIR__ . '/partials/footer.php' ?>
