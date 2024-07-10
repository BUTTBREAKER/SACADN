<?php

require __DIR__ . '/../vendor/autoload.php';
// Incluir el archivo de conexión a la base de datos
/** @var mysqli */
$db = require_once __DIR__ . '/conexion_be.php';
include __DIR__ . '/partials/header.php';

// Obtener el ID del representante de la URL
$idRepresentante = $_GET['id'] ?? null;

if (!$idRepresentante) {
    echo "No se ha proporcionado un ID de representante.";
    exit;
}

/* Seleccionar los detalles del representante */
$sql = "SELECT * FROM representantes WHERE id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param('i', $idRepresentante);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "No se encontraron detalles para este representante.";
    exit;
}

$representante = $result->fetch_assoc();

/* Seleccionar los estudiantes representados por este representante junto con su nivel de estudio y sección */
/* TODO: en nuevo_estudiante.php y guardar_estudiante.php, seleccionar a qué
período asignar la inscripción y a qué sección asignar, para al momento de
consultar, saber cual es la sección y nivel de estudio del período actual
del estudiante */
$sqlEstudiantes = <<<SQL
  SELECT e.cedula, e.nombres, e.apellidos, e.fecha_nacimiento,
  e.fecha_registro AS fecha
  FROM estudiantes e
  WHERE e.id_representante = ?
SQL;

$stmtEstudiantes = $db->prepare($sqlEstudiantes);
$stmtEstudiantes->bind_param('i', $idRepresentante);
$stmtEstudiantes->execute();
$resultEstudiantes = $stmtEstudiantes->get_result();

?>

<div class="container card card-body">
  <h2>Detalles del Representante</h2>
  <p><strong>Nombre:</strong> <?= htmlspecialchars($representante['nombre']) ?></p>
  <p><strong>Apellido:</strong> <?= htmlspecialchars($representante['apellido']) ?></p>
  <p><strong>Teléfono:</strong> <?= htmlspecialchars($representante['telefono']) ?></p>
  <p><strong>Dirección:</strong> <?= htmlspecialchars($representante['direccion']) ?></p>

  <h3>Estudiantes Representados</h3>
  <?php if ($resultEstudiantes->num_rows > 0) { ?>
    <div class="table-responsive mb-5">
      <table class="table table-striped">
        <thead>
          <tr>
            <th>Cédula</th>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Fecha de Nacimiento</th>
            <th>Nivel de Estudio</th>
            <th>Sección</th>
            <th>Fecha de Inscripción/Hora</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($estudiante = $resultEstudiantes->fetch_assoc()) { ?>
            <tr>
              <td><?= htmlspecialchars($estudiante['cedula']) ?></td>
              <td><?= htmlspecialchars($estudiante['nombres']) ?></td>
              <td><?= htmlspecialchars($estudiante['apellidos']) ?></td>
              <td><?= htmlspecialchars(formatearFecha($estudiante['fecha_nacimiento'])) ?></td>
              <td><?= htmlspecialchars($estudiante['nivel_estudio'] ?? '') ?></td>
              <td><?= htmlspecialchars($estudiante['seccion'] ?? '') ?></td>
              <td><?= date('d/m/Y', strtotime($estudiante['fecha'])) ?></td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  <?php } else { ?>
    <p>Este representante no tiene estudiantes asignados.</p>
  <?php } ?>
  <div >
    <button type="button" class="btn-group btn-group-lg mx-3"><a href="javascript:history.back()" class="btn btn-outline-secondary">Regresar</a></button>
  </div>
</div>

<?php include __DIR__ . '/partials/footer.php' ?>
