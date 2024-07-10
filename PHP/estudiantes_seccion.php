<?php
require __DIR__ . '/../vendor/autoload.php';
include __DIR__ . '/partials/header.php';

$db = require_once __DIR__ . '/conexion_be.php';

// Consulta SQL con JOIN
//        e.nombre AS nombreEstudiante, e.apellido AS apellidoEstudiante, ne.id As id_nivel_estudio,
//       ne.nombre AS nivel_estudio, s.id AS idSeccion, s.nombre AS seccion
//       FROM asignaciones_estudiantes ae
//      INNER JOIN estudiantes e ON e.id = ae.id_estudiante
//     INNER JOIN niveles_estudio ne ON ne.id = ae.id_nivel_estudio
//    INNER JOIN secciones s ON s.id = ae.id_seccion";

$sql_estudiante =
  "SELECT e.id, e.cedula, e.nombre, e.apellido, r.id AS idRepresentante, r.nombre AS nombresRepresentante,
  r.apellido AS apellidosRepresentante
  FROM estudiantes e
  JOIN representantes r ON r.id = e.id_representante";
$result_estudiante = $db->query($sql_estudiante);
while ($estudiante = $result_estudiante->fetch_assoc());


$sql_nivel = "SELECT id, nombre FROM niveles_estudio ";
$result_nivel = $db->query($sql_nivel);
while ($nivel = $result_nivel->fetch_assoc());

$sql_secciones = "SELECT id, nombre FROM secciones";
$result_secciones = $db->query($sql_secciones);
while ($seccion = $result_secciones->fetch_assoc());

//if ($result->num_rows > 0) {
// Inicio de la lista HTML
//echo "<ul>";
// Salida de datos de cada fila como elementos de la lista
//while($fila = $result->fetch_assoc()) {
//echo "<li>Cedula: " . $fila["cedulaEstudiante"]. " - Nombres: " . $fila["nombreEstudiante"]. " - Apellidos: " . $fila["apellidoEstudiante"].
//"- Nivel de estudio: " . $fila["nivel_estudio"]. " - Sección: " . $fila["seccion"]. "</li>";
//}
// Fin de la lista HTML
// echo "</ul>";
//} else {
// echo "0 resultados";
//}
//$conexion->close();


?>

<body>
  <div class="container card card-body table-responsive">
    <h3 value="<?= $nivel['nombre'] ?>"></h3>
    <h3 value="<?= $seccion['nombre'] ?>"></h3>
    <div class="table-responsive">
      <table id="tablaEstudiantesPorSeciones" class="table table-striped datatable">
        <thead>
          <tr>
            <th>Cedula</th>
            <th>Nombres</th>
            <th>Apellidos</th>
            <th>Representante</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($estudiante = $result_estudiante->fetch_assoc(MYSQLI_ASSOC)) { ?>
            <tr>
              <td><?= htmlspecialchars($estudiante['cedulaEstudiante']) ?></td>
              <td><?= htmlspecialchars($estudiante['nombreEstudiante']) ?></td>
              <td><?= htmlspecialchars($estudiante['apellidoEstudiante']) ?></td>
              <td>
                <a href="detalles-representante.php?id=<?= htmlspecialchars($estudiante['idRepresentante']) ?>">
                  <?= htmlspecialchars($estudiante['nombresRepresentante'] . ' ' . $estudiante['apellidosRepresentante']) ?>
                </a>
              </td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
</body>


<?php include __DIR__ . '/partials/footer.php' ?>
