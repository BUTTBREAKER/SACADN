<?php

require __DIR__ . '/../vendor/autoload.php';

/** @var mysqli */
$db = require_once __DIR__ . '/conexion_be.php';

if (isset($_GET['anioSeleccionado'])) {
  $sentencia = $db->prepare('SELECT seccion FROM secciones_anios WHERE anio=?');
  $sentencia->execute([$_GET['anioSeleccionado']]);

  $secciones = array_map(function (array $info): string {
    return $info['seccion'];
  }, $sentencia->get_result()->fetch_all(MYSQLI_ASSOC));

  exit(json_encode($secciones));
}

include __DIR__ . '/partials/header.php';

$añosEscolares = array_map(function (array $registro): string {
  return $registro['anio'];
}, $db->query('select DISTINCT anio from secciones_anios')->fetch_all(MYSQLI_ASSOC));

$sql = 'SELECT ID_mater as id, codigo, nombre, fech_mater as fecha_registro FROM materias';

$result = $db->query($sql);

$materias = $result->fetch_all(MYSQLI_ASSOC);

?>

<form action="procesar-asignacion.php" method="post">
  <select name="anio" required>
    <option selected disabled>Seleccione un año</option>
    <?php foreach ($añosEscolares as $año) : ?>
      <option><?= $año ?></option>
    <?php endforeach ?>
  </select>
  <div id="seleccionador-de-secciones"></div>
  <select name="materias[]" required multiple>
    <option selected disabled>Selecciona las materias</option>
    <?php foreach ($materias as $materia): ?>
      <option value="<?= $materia['id'] ?>"><?= $materia['nombre'] ?></option>
    <?php endforeach ?>
  </select>
  <div id="contenedor-boton"></div>
</form>

<script>
  const seleccionadorDeAños = document.querySelector('[name="anio"]')
  const seleccionadorDeSecciones = document.getElementById('seleccionador-de-secciones')
  const contenedorBoton = document.getElementById('contenedor-boton')

  seleccionadorDeAños.addEventListener('change', () => {
    const opcionSeleccionada = seleccionadorDeAños.value

    fetch(`${location.href}?anioSeleccionado=${opcionSeleccionada}`)
      .then(respuesta => respuesta.json())
      .then(secciones => {
        seleccionadorDeSecciones.innerHTML = `
          <select name="seccion">
            <option disabled selected>Seleccione una sección</option>
            ${secciones.map(seccion => `<option>${seccion}</option>`).join('')}
          </select>
        `

        contenedorBoton.innerHTML = `<button type="submit">Asignar</button>`
      })
  })
</script>

<?php include __DIR__ . '/partials/footer.php' ?>
