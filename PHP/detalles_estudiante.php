<?php
require __DIR__ . '/../vendor/autoload.php';
include __DIR__ . '/partials/header.php';

// Obtener el ID del estudiante de la URL
$estudiante_id = $_GET['id'] ?? null;

// Verificar si se proporcionó un ID de estudiante válido
if (!$estudiante_id) {
    echo "No se ha proporcionado un ID de estudiante.";
    exit;
}

// Realizar la conexión a la base de datos (requiere el archivo de conexión)
$db = require_once __DIR__ . '/conexion_be.php';
// Consultar los detalles del estudiante
$sql_estudiante = "SELECT e.* ,r.nombre AS nombre_representante, r.apellido AS apellido_representante
                          FROM estudiantes e
                          JOIN representantes r ON e.id_representante =r.id
                          WHERE e.id = ?";
$stmt_estudiante = $db->prepare($sql_estudiante);
$stmt_estudiante->bind_param('i', $estudiante_id);
$stmt_estudiante->execute();
$result_estudiante = $stmt_estudiante->get_result();

 
$stmt_estudiante = $db->prepare($sql_estudiante);
$stmt_estudiante->bind_param('i', $estudiante_id);
$stmt_estudiante->execute();
$result_estudiante = $stmt_estudiante->get_result();

// Verificar si se encontraron detalles del estudiante
if ($result_estudiante->num_rows === 0) {
    echo "No se encontraron detalles para este estudiante.";
    exit;
}

// Obtener los detalles del estudiante
$estudiante = $result_estudiante->fetch_assoc();

// Consultar el nivel de estudio y la sección actual del estudiante
$sql_asignacion = "SELECT n.nombre AS nivel_estudio, s.nombre AS seccion
                   FROM asignaciones_estudiantes ae
                   JOIN niveles_estudio n ON ae.id_nivel_estudio = n.id
                   JOIN secciones s ON ae.id_seccion = s.id
                   WHERE ae.id_estudiante = ?";
$stmt_asignacion = $db->prepare($sql_asignacion);
$stmt_asignacion->bind_param('i', $estudiante_id);
$stmt_asignacion->execute();
$result_asignacion = $stmt_asignacion->get_result();

// Obtener los detalles de la asignación del estudiante
$asignacion = $result_asignacion->fetch_assoc();

// Consultar todas las calificaciones del estudiante
$sql_calificaciones = "SELECT concat(p.anio_inicio, '-', p.anio_inicio + 1) AS periodo,
                              m.numero_momento AS momento,
                              ma.nombre AS materia,
                              c.calificacion,
                              pr.nombre AS nombre_profesor,
                              pr.apellido AS apellido_profesor
                       FROM calificaciones c
                       JOIN materias ma ON c.id_materia = ma.id
                       JOIN boletines b ON c.id_boletin = b.id
                       JOIN estudiantes e ON b.id_estudiante = e.id
                       JOIN momentos m ON b.id_momento = m.id
                       JOIN periodos p ON m.id_periodo = p.id
                       JOIN asignaciones a ON ma.id = a.id_materia
                       AND a.id_periodo = p.id
                       AND a.id_nivel_estudio = (SELECT id_nivel_estudio FROM asignaciones_estudiantes WHERE id_estudiante = e.id)
                       AND a.id_seccion = (SELECT id_seccion FROM asignaciones_estudiantes WHERE id_estudiante = e.id)
                       JOIN profesores pr ON a.id_profesor = pr.id
                       WHERE e.id = ?
                       ORDER BY ma.nombre, m.numero_momento";
$stmt_calificaciones = $db->prepare($sql_calificaciones);
$stmt_calificaciones->bind_param('i', $estudiante_id);
$stmt_calificaciones->execute();
$result_calificaciones = $stmt_calificaciones->get_result();

// Organizar las calificaciones por materia y momento
$calificaciones = [];
while ($row = $result_calificaciones->fetch_assoc()) {
    $calificaciones[$row['materia']]['profesor'] = $row['nombre_profesor'] . ' ' . $row['apellido_profesor'];
    $calificaciones[$row['materia']]['momentos'][$row['momento']] = $row['calificacion'];
}

?>

<body>
    <div class="container">
        <h1 class="mt-5 mb-4">Detalles del Estudiante</h1>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Información del Estudiante</h5>
                <p><strong>Nombre Completo:</strong> <?= htmlspecialchars($estudiante['nombre']) . ' ' . htmlspecialchars($estudiante['apellido']) ?></p>
                <p><strong>Cédula:</strong> <?= htmlspecialchars($estudiante['cedula']) ?></p>
<<<<<<< HEAD
                <p><strong>Fecha de Nacimiento:</strong> <?= htmlspecialchars($estudiante['fecha_nacimiento']) ?></p>
                <p><strong>Estado de Nacimiento:</strong> <?= htmlspecialchars($estudiante['estado_nacimiento']) ?></p>
                <p><strong>Municipio de Nacimiento:</strong> <?= htmlspecialchars($estudiante['lugar_nacimiento']) ?></p>
                <p><strong>Género:</strong> <?= htmlspecialchars($estudiante['genero']) ?></p>
                <p><strong>Representante:</strong> <?= htmlspecialchars($estudiante['nombre_representante']) . ' ' . htmlspecialchars($estudiante['apellido_representante']) ?></p>
=======
>>>>>>> 86bcf7288f04f619f8b846cec92468e6fd2c3942

                <h5 class="card-title mt-4">Detalles Académicos</h5>
                <p><strong>Nivel de Estudio:</strong> <?= htmlspecialchars($asignacion['nivel_estudio']) ?></p>
                <p><strong>Sección:</strong> <?= htmlspecialchars($asignacion['seccion']) ?></p>

                <h5 class="card-title mt-4">Calificaciones</h5>
                <form id="printForm">
                    <div class="form-group mb-3">
                        <label for="momento">Selecciona el Momento:</label>
                        <select id="momento" class="form-select">
                            <option value="1">Momento 1</option>
                            <option value="2">Momento 2</option>
                            <option value="3">Momento 3</option>
                        </select>
                    </div>
                    <button type="button" class="btn btn-primary mb-3" onclick="printNotas()">Imprimir Notas</button>
                </form>
                <div id="notas-section" class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Materia</th>
                                <th>Profesor</th>
                                <th class="momento-header" data-momento="1">Momento 1</th>
                                <th class="momento-header" data-momento="2">Momento 2</th>
                                <th class="momento-header" data-momento="3">Momento 3</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($calificaciones as $materia => $detalles) { ?>
                                <tr>
                                    <td><?= htmlspecialchars($materia) ?></td>
                                    <td><a href="detalles-profesor.php?id=<?= $mostrar['id'] ?>"><?= htmlspecialchars($detalles['profesor']) ?></a></td>
                                    <td class="momento-1"><?= htmlspecialchars($detalles['momentos'][1] ?? '') ?></td>
                                    <td class="momento-2"><?= htmlspecialchars($detalles['momentos'][2] ?? '') ?></td>
                                    <td class="momento-3"><?= htmlspecialchars($detalles['momentos'][3] ?? '') ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        function printNotas() {
            var selectedMomento = document.getElementById('momento').value;
            var momentoHeaders = document.querySelectorAll('.momento-header');
            var momentoCells = document.querySelectorAll('[class^="momento-"]');

            momentoHeaders.forEach(header => {
                if (header.getAttribute('data-momento') > selectedMomento) {
                    header.style.display = 'none';
                } else {
                    header.style.display = '';
                }
            });

            momentoCells.forEach(cell => {
                var cellClass = cell.className.split(' ')[0];
                var cellMomento = cellClass.split('-')[1];

                if (cellMomento > selectedMomento) {
                    cell.style.display = 'none';
                } else {
                    cell.style.display = '';
                }
            });

            window.print();
        }
    </script>
</body>

<?php include __DIR__ . '/partials/footer.php' ?>
