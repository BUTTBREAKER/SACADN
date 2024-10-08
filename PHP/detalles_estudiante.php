<?php
require __DIR__ . '/../vendor/autoload.php';
include __DIR__ . '/partials/header.php';

use Dompdf\Dompdf;
use Dompdf\Options;

function generarPDF($html) {

}

// Captura de salida para evitar envío prematuro
ob_start();

/// Obtener el ID del estudiante de la URL
$estudiante_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$estudiante_id) {
  echo "No se ha proporcionado un ID de estudiante válido.";
  exit;
}

// Realizar la conexión a la base de datos
$db = require_once __DIR__ . '/conexion_be.php';

try {
  // Consultar los detalles del estudiante
  $sql_estudiante = "SELECT e.*, r.nombre AS nombre_representante, r.apellido AS apellido_representante
                     FROM estudiantes e
                     JOIN representantes r ON e.id_representante = r.id
                     WHERE e.id = ?";
  $stmt_estudiante = $db->prepare($sql_estudiante);
  $stmt_estudiante->bind_param('i', $estudiante_id);
  $stmt_estudiante->execute();
  $result_estudiante = $stmt_estudiante->get_result();

  if ($result_estudiante->num_rows === 0) {
    echo "No se encontraron detalles para este estudiante.";
    exit;
  }

  // Obtener los detalles del estudiante
  $estudiante = $result_estudiante->fetch_assoc();

  // Consultar el nivel de estudio y la sección actual del estudiante
  $sql_inscripcion = "SELECT ne.nombre AS nivel_estudio, s.nombre AS seccion, p.anio_inicio
                      FROM inscripciones i
                      JOIN secciones s ON i.id_seccion = s.id
                      JOIN niveles_estudio ne ON s.id_nivel_estudio = ne.id
                      JOIN periodos p ON s.id_periodo = p.id
                      WHERE i.id_estudiante = ?
                      ORDER BY i.fecha_registro DESC
                      LIMIT 1";
  $stmt_inscripcion = $db->prepare($sql_inscripcion);
  $stmt_inscripcion->bind_param('i', $estudiante_id);
  $stmt_inscripcion->execute();
  $result_inscripcion = $stmt_inscripcion->get_result();
  $inscripcion = $result_inscripcion->fetch_assoc();

  // Consultar todas las calificaciones del estudiante
  $sql_calificaciones = "SELECT m.nombre AS materia, mo.numero_momento AS momento,
                                c.calificacion, p.nombre AS nombre_profesor,
                                p.apellido AS apellido_profesor
                         FROM calificaciones c
                         JOIN boletines b ON c.id_boletin = b.id
                         JOIN materias m ON c.id_materia = m.id
                         JOIN momentos mo ON b.id_momento = mo.id
                         JOIN asignaciones a ON m.id = a.id_materia
                         AND a.id_nivel_estudio = (SELECT id_nivel_estudio FROM secciones WHERE id = (SELECT id_seccion FROM inscripciones WHERE id_estudiante = ? ORDER BY fecha_registro DESC LIMIT 1))
                         AND a.id_seccion = (SELECT id_seccion FROM inscripciones WHERE id_estudiante = ? ORDER BY fecha_registro DESC LIMIT 1)
                         JOIN profesores p ON a.id_profesor = p.id
                         WHERE b.id_estudiante = ?
                         ORDER BY m.nombre, mo.numero_momento";
  $stmt_calificaciones = $db->prepare($sql_calificaciones);
  $stmt_calificaciones->bind_param('iii', $estudiante_id, $estudiante_id, $estudiante_id);
  $stmt_calificaciones->execute();
  $result_calificaciones = $stmt_calificaciones->get_result();

  // Organizar las calificaciones por materia y momento
  $calificaciones = [];
  while ($row = $result_calificaciones->fetch_assoc()) {
    $calificaciones[$row['materia']]['profesor'] = $row['nombre_profesor'] . ' ' . $row['apellido_profesor'];
    $calificaciones[$row['materia']]['momentos'][$row['momento']] = $row['calificacion'];
  }

  // Calcular promedios y definitivas
  foreach ($calificaciones as $materia => &$detalles) {
    $momentos = $detalles['momentos'];
    $detalles['promedio'] = array_sum($momentos) / count($momentos);
    $detalles['def'] = round($detalles['promedio']); // Calificación definitiva redondeada
  }

  // HTML para el contenido del boletín
?>
  <div id="contenido-pdf">
    <div class="container card card-body">
      <h1 class="mt-5 mb-4 text-center">BOLETIN INFORMATIVO</h1>
      <div class="text-center">
        <p>REPUBLICA BOLIVARIANA DE VENEZUELA</p>
        <p>MINISTERIO DEL PODER POPULAR PARA LA EDUCACION</p>
        <p>U. E. N. CREACION V CAJA SECA</p>
        <p>AÑO ESCOLAR: <?= htmlspecialchars($inscripcion['anio_inicio']) ?>-<?= htmlspecialchars($inscripcion['anio_inicio'] + 1) ?></p>
      </div>
      <div class="card my-4">
        <div class="card-body">
          <h5 class="card-title">Información del Estudiante</h5>
          <p><strong>Apellidos y Nombres:</strong> <?= htmlspecialchars($estudiante['apellidos']) . ' ' . htmlspecialchars($estudiante['nombres']) ?></p>
          <p><strong>C.I.:</strong> <?= htmlspecialchars($estudiante['cedula']) ?></p>
          <p><strong>Año y Sección:</strong> <?= htmlspecialchars($inscripcion['nivel_estudio'] ?? '') . ' ' . htmlspecialchars($inscripcion['seccion'] ?? '') ?></p>
        </div>
      </div>
      <div class="card my-4">
        <div class="card-body">
          <h5 class="card-title">Calificaciones</h5>
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>Áreas de Formación</th>
                <th style="width: 10%;">1er Lapso</th>
                <th style="width: 10%;">2do Lapso</th>
                <th style="width: 10%;">3er Lapso</th>
                <th>Def.</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($calificaciones as $materia => $detalles) { ?>
                <tr>
                  <td><?= htmlspecialchars($materia) ?></td>
                  <td><?= htmlspecialchars($detalles['momentos'][1] ?? '') ?></td>
                  <td><?= htmlspecialchars($detalles['momentos'][2] ?? '') ?></td>
                  <td><?= htmlspecialchars($detalles['momentos'][3] ?? '') ?></td>
                  <td><?= htmlspecialchars($detalles['def']) ?></td>
                </tr>
              <?php } ?>
            </tbody>
            <tfoot>
              <tr>
                <th>Promedio</th>
                <?php
                $promedios = array_fill(1, 3, 0);
                $conteos = array_fill(1, 3, 0);
                foreach ($calificaciones as $detalles) {
                  for ($i = 1; $i <= 3; $i++) {
                    if (isset($detalles['momentos'][$i])) {
                      $promedios[$i] += $detalles['momentos'][$i];
                      $conteos[$i]++;
                    }
                  }
                }
                ?>
                <?php for ($i = 1; $i <= 3; $i++) { ?>
                  <td><?= $conteos[$i] ? round($promedios[$i] / $conteos[$i], 1) : '' ?></td>
                <?php } ?>
                <td><?= round(array_sum($promedios) / (array_sum($conteos) ?: 1), 1) ?></td>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
      <div class="row my-4">
        <div class="col-md-6">
          <p>DOCENTE GUIA:____________________________</p>
        </div>
        <div class="col-md-6 text-right">
          <p>FIRMA:____________________</p>
        </div>
      </div>
      <div class="text-center">
        <p>SELLO DEL PLANTEL</p>
      </div>
    </div>
  </div>

<?php
  $html = ob_get_clean();

  // Generar el PDF al hacer clic en el botón
  if (isset($_POST['generar_pdf'])) {
    generarPDF($html);
  }
} catch (Exception $e) {
  echo 'Error: ' . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Boletín Informativo</title>
  <!-- Estilos CSS para pantalla y para impresión -->
  <link rel="stylesheet" href="styles.css">
  <style>
    @media print {
      /* Ocultar botón de impresión */
      .print-button {
        display: none;
      }
    }
  </style>
</head>

<body>
  <form method="post">
    <?= $html ?>
    <div class="text-center mt-4">
      <button class="btn btn-success" onclick="window.print()">Imprimir</button>
    </div>
  </form>

  <?php include __DIR__ . '/partials/footer.php'; ?>
</body>

</html>
