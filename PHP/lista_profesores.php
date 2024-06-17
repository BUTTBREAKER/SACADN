<?php 
require __DIR__ . '/../vendor/autoload.php';
// Incluir el archivo de conexión a la base de datos
/** @var mysqli */
$db = require_once __DIR__ . '/conexion_be.php';
include __DIR__ . '/partials/header.php';

$sql = <<<SQL
  SELECT p.id AS idProfesor, p.cedula AS cedulaProfesor, p.nombre AS nombreProfesor, apellido AS apellidoProfesor, m.id AS idMateria, m.nombre AS materia,
   ne.id AS idNivel_estudio, ne.nombre As nivel_estudio, s.id idSeccion, s.nombre AS seccion FROM asignaciones a
JOIN profesores p ON p.id = a.id_profesor
JOIN materias m ON m.id = a.id_materia
JOIN niveles_estudio ne ON ne.id = a.id_nivel_estudio
JOIN secciones s ON s.id = a.id_seccion
SQL;

$result = $db->query($sql);

if (!$result) {
    echo "Error en la consulta: " . $db->error;
    exit;
}

?>

<div class="container card card-body table-responsive">
  <h3>Materias Asignadas a Profesores</h3>
  <table id="tablaEstudiantes" class="table table-striped datatable">
    <thead>
      <tr>
        <th>Cédula</th>
        <th>Nombres</th>
        <th>Apellidos</th>
        <th>Materia</th>
        <th>Nivel de Estudio</th>
        <th>Seccion</th>
      </tr>
    </thead>
    <tbody> 

      <?php while ($mostrar = $result->fetch_assoc()) { ?>

        <td>
              <?= htmlspecialchars($mostrar['cedulaProfesor']) ?>
          </td>
        <td>
            <a href="detalles-profesor.php?id=<?= htmlspecialchars($mostrar['idProfesor']) ?>">
              <?= htmlspecialchars($mostrar['nombreProfesor']) ?>
            </a>
          </td>
         <td>
              <?= htmlspecialchars($mostrar['apellidoProfesor']) ?>
          </td>  
         <td>
              <?= htmlspecialchars($mostrar['materia']) ?>
         </td>
         <td>
              <?= htmlspecialchars($mostrar['nivel_estudio']) ?>
         </td>
         <td>
              <?= htmlspecialchars($mostrar['seccion']) ?>
          </td>
            
      <?php } ?>
    </tbody>
  </table>
</div>

<script src="../Assets/simple-datatables/simple-datatables.min.js"></script>
<script>
  const tablaEstudiantes = new simpleDatatables.DataTable("#tablaEstudiantes");
</script>

<?php include __DIR__ . '/partials/footer.php' ?>
