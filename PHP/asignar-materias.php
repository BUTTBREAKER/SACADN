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

include __DIR__ . '/../Assets/Menu/Menu.php';

$añosEscolares = array_map(function (array $registro): string {
  return $registro['anio'];
}, $db->query('select DISTINCT anio from secciones_anios')->fetch_all(MYSQLI_ASSOC));

$materias = array_map(function (array $registro): string {
  return $registro['nombre'];
}, $db->query('select DISTINCT anio from secciones_anios')->fetch_all(MYSQLI_ASSOC));

$sql = 'SELECT ID_mater as id, codigo, nombre, fech_mater as fecha_registro FROM materias';

$result = $db->query($sql);

$materias = $result->fetch_all(MYSQLI_ASSOC);

?>
<style type="text/css">
.anio{
  position: 10px 20px 10px 2px;
  background-color: lightblue;
  border-radius: 10px 30px 10px 30px;
  justify-content: center;
  width: 200px;
  padding: 10px 5px 10px 5px;
  margin: 10px 10px 10px 10px;
  text-align: center;

}  
.seccion{
  position: none;
  background-color: lightblue;
  border-radius: 10px 30px 10px 30px;
  justify-content: center;
  width: 200px;
  padding: 10px 5px 10px 5px;
  margin: 10px 10px 10px 10px ;
  text-align: center;
} 
.seccion1{
  position: relative;
  background-color: none;
  border-radius: none;
  justify-content: center;
  width: 200px;
  padding: 10px 5px 10px 5px;
  margin: 10px 10px 20px 10p ;
  
} 
  
.materias{
  background-color: lightblue;
  border-radius: none;
  justify-content: center;
  width: 600px;
  padding: 0%;
  border: none;


}
.asignacion{
  justify-content: center;
  position: absolute;
  padding: 50px 40px 50px 300px; 
  width: 20px 30px 20px 30px; 
}
.option{
  border-color: black;
  border: 1px solid;
  width: 50px 500px 50px 500px;
  padding: 5px 5px 5px 5px;
  text-justify: 50px;
}

.seleccionador{
  color: black;
  background-color: #72D6EE;
  border: 1px solid #2DA0FA;
  padding: 5px;
  position: center;
  width: 600px;

</style>
 <form class="asignacion" action="procesar-asignacion.php" method="post">
  <select class="anio" name="anio" required>
    <option class=" selecionador" selected disabled>Seleccione un año</option>
       <option value=1ero>1ero</option>
        <option value=2do>2do</option>
        <option value=3ero>3ero</option>
        <option value=4to>4to</option>
        <option value=5to>5to</option>
    <?php foreach ($añosEscolares as $año) : ?>
      <option><?= $año ?></option>
    <?php endforeach ?>
  </select>
  <div class="seccion1" id="seleccionador-de-secciones"></div>
    <option class="seleccionador" selected disabled>Selecciona las materias</option>
      <select class="materias" name="materias[]" required multiple>

        <option class="option" value=Castellano href="nuevo.php">Castellano</option>
        <option class="option" value=Ingles>Ingles y otras lenguas</option>
        <option class="option" value=Matemáticas>Matemáticas</option>
        <option class="option" value=Educ.física>Educación física</option>
        <option class="option" value=Art.Patrimonio>Arte y Patrimonio</option>
        <option class="option" value=C.Naturales>Ciencias Naturales</option>
        <option class="option" value=GHC>Geografía, Historia y Ciudadanía</option>
        <option class="option" value=Física>Física</option>
        <option class="option" value=Química>Química</option>
        <option class="option" value=´Bioligía>Bioligía</option>
        <option class="option" value=Soberania>Formacion para la soberanía</option>
        <option class="option" value=C.tierra>Ciencias de la tierra</option>
        <option class="option" value=Nacional>Nacional</option>
        <option class="option" value=Orientación>Orientación y Convivencia</option>
   
    <?php foreach ($materias as $materia): ?>
      <option value="<?= $materia['id'] ?>"><?= $materia['codigo'] ?><?= $materia['nombre'] ?><?= $materia['fecha_registro'] ?></option>
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
          <select class="seccion" name="seccion" required>
            <option class="seleccionador" disabled selected >Seleccione una sección</option>
                        <option value=A>A</option>
                        <option value=B>B</option>
                        <option value=C>C</option>
                        <option value=D>D</option>
                        <option value=E>E</option>
                        <option value=F>F</option>
                        <option value=G>G</option>
                        <option value=H>H</option>
                        <option value=I>I</option>
                        <option value=J>J</option>
                        <option value=K>K</option>
            ${secciones.map(seccion => `<option>${seccion}</option>`).join('')}
          </select>
        `

        contenedorBoton.innerHTML = `<button type="submit">Asignar</button>`
      })
  })
</script>
 