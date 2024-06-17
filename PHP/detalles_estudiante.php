

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
$sql_estudiante = "SELECT e.*, r.nombre AS nombre_representante, r.apellido AS apellido_representante
                   FROM estudiantes e
                   JOIN representantes r ON e.id_representante = r.id
                   WHERE e.id = ?";
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
                              concat('Momento ', m.numero_momento) AS momento,
                              ma.nombre AS materia,
                              c.calificacion
                       FROM calificaciones c
                       JOIN materias ma ON c.id_materia = ma.id
                       JOIN boletines b ON c.id_boletin = b.id
                       JOIN estudiantes e ON b.id_estudiante = e.id
                       JOIN momentos m ON b.id_momento = m.id
                       JOIN periodos p ON m.id_periodo = p.id
                       WHERE e.id = ?
                       ORDER BY p.anio_inicio, m.numero_momento, ma.nombre";
$stmt_calificaciones = $db->prepare($sql_calificaciones);
$stmt_calificaciones->bind_param('i', $estudiante_id);
$stmt_calificaciones->execute();
$result_calificaciones = $stmt_calificaciones->get_result();

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Estudiante</title>
    <!-- Agrega los estilos de Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h1 class="mt-5 mb-4">Detalles del Estudiante</h1>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Información del Estudiante</h5>
                <p><strong>Nombre:</strong> <?= htmlspecialchars($estudiante['nombre']) ?></p>
                <p><strong>Apellido:</strong> <?= htmlspecialchars($estudiante['apellido']) ?></p>
                <p><strong>Cédula:</strong> <?= htmlspecialchars($estudiante['cedula']) ?></p>
                <p><strong>Fecha de Nacimiento:</strong> <?= htmlspecialchars($estudiante['fecha_nacimiento']) ?></p>
                <p><strong>Estado de Nacimiento:</strong> <?= htmlspecialchars($estudiante['estado_nacimiento']) ?></p>
                <p><strong>Lugar de Nacimiento:</strong> <?= htmlspecialchars($estudiante['lugar_nacimiento']) ?></p>
                <p><strong>Género:</strong> <?= htmlspecialchars($estudiante['genero']) ?></p>
                <p><strong>Representante:</strong> <?= htmlspecialchars($estudiante['nombre_representante']) . ' ' . htmlspecialchars($estudiante['apellido_representante']) ?></p>

                <h5 class="card-title mt-4">Detalles Académicos</h5>
                <p><strong>Nivel de Estudio:</strong> <?= htmlspecialchars($asignacion['nivel_estudio']) ?></p>
                <p><strong>Sección:</strong> <?= htmlspecialchars($asignacion['seccion']) ?></p>

                <h5 class="card-title mt-4">Calificaciones</h5>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Periodo</th>
                                <th>Momento</th>
                                <th>Materia</th>
                                <th>Calificación</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($calificacion = $result_calificaciones->fetch_assoc()) { ?>
                                <tr>
                                    <td><?= htmlspecialchars($calificacion['periodo']) ?></td>
                                    <td><?= htmlspecialchars($calificacion['momento']) ?></td>
                                    <td><?= htmlspecialchars($calificacion['materia']) ?></td>
                                    <td><?= htmlspecialchars($calificacion['calificacion']) ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>

<?php include __DIR__ . '/partials/footer.php' ?>

