<?php

declare(strict_types=1);

include __DIR__ . '/partials/header.php';

$user = auth()['usuario'];
$conn = require_once __DIR__ . '/conexion_be.php';

// Obtener el ID del periodo activo
$query_periodo_activo = "SELECT id FROM periodos WHERE estado = 'activo' LIMIT 1";
$result_periodo_activo = $conn->query($query_periodo_activo);
$periodo_activo = $result_periodo_activo->fetch_assoc();
$id_periodo_activo = $periodo_activo['id'] ?? null;

if (!$id_periodo_activo) {
  die("Error: No hay un periodo activo.");
}

// Obtener la cantidad de lapsos registrados en el periodo activo
$query_cantidad_lapsos = "SELECT COUNT(*) AS cantidad_lapsos FROM momentos WHERE id_periodo = ?";
$stmt_lapsos = $conn->prepare($query_cantidad_lapsos);
$stmt_lapsos->bind_param('i', $id_periodo_activo);
$stmt_lapsos->execute();
$result_lapsos = $stmt_lapsos->get_result()->fetch_assoc();
$cantidad_lapsos = $result_lapsos['cantidad_lapsos'];

$niveles = [];
$estudiantes_aprobados = [];
$estudiantes_reprobados = [];
$total_estudiantes_nivel = []; // Para la gráfica general de estudiantes por nivel

// Query para obtener el total de estudiantes por nivel de estudio
$query_estudiantes_nivel = "
WITH estudiante_nivel AS (
    SELECT
        inscripciones.id_estudiante,
        niveles_estudio.id AS id_nivel,
        niveles_estudio.nombre AS nivel,
        COUNT(DISTINCT asignaciones.id_materia) AS total_materias
    FROM inscripciones
    JOIN secciones ON inscripciones.id_seccion = secciones.id
    JOIN niveles_estudio ON secciones.id_nivel_estudio = niveles_estudio.id
    JOIN asignaciones ON secciones.id = asignaciones.id_seccion
    WHERE inscripciones.id_periodo = ?
    GROUP BY inscripciones.id_estudiante, niveles_estudio.id, niveles_estudio.nombre
),
estudiante_calificaciones AS (
    SELECT
        inscripciones.id_estudiante,
        niveles_estudio.id AS id_nivel,
        niveles_estudio.nombre AS nivel,
        SUM(COALESCE(calificaciones.calificacion, 1)) AS suma_calificaciones
    FROM calificaciones
    JOIN boletines ON calificaciones.id_boletin = boletines.id
    JOIN inscripciones ON boletines.id_estudiante = inscripciones.id_estudiante
    JOIN secciones ON inscripciones.id_seccion = secciones.id
    JOIN niveles_estudio ON secciones.id_nivel_estudio = niveles_estudio.id
    WHERE inscripciones.id_periodo = ? AND boletines.id_momento = ?
    GROUP BY inscripciones.id_estudiante, niveles_estudio.id, niveles_estudio.nombre
)
SELECT
    en.nivel,
    COUNT(DISTINCT en.id_estudiante) AS total_estudiantes_nivel,  -- Total estudiantes por nivel
    COUNT(CASE WHEN (ec.suma_calificaciones / en.total_materias) >= 10 THEN 1 END) AS estudiantes_aprobados,
    COUNT(CASE WHEN (ec.suma_calificaciones / en.total_materias) < 10 OR ec.suma_calificaciones IS NULL THEN 1 END) AS estudiantes_reprobados_o_sin_notas
FROM estudiante_nivel en
LEFT JOIN estudiante_calificaciones ec ON en.id_estudiante = ec.id_estudiante AND en.id_nivel = ec.id_nivel
GROUP BY en.nivel
";

$stmt_estudiantes_nivel = $conn->prepare($query_estudiantes_nivel);
$stmt_estudiantes_nivel->bind_param('iii', $id_periodo_activo, $id_periodo_activo, $cantidad_lapsos);
$stmt_estudiantes_nivel->execute();
$result_estudiantes_nivel = $stmt_estudiantes_nivel->get_result();

while ($row = $result_estudiantes_nivel->fetch_assoc()) {
    // Llenar los arrays para los gráficos
    $niveles[] = $row['nivel'];
    $total_estudiantes_nivel[] = (int)$row['total_estudiantes_nivel']; // Total estudiantes por nivel
    $estudiantes_aprobados[] = (int)$row['estudiantes_aprobados'];
    $estudiantes_reprobados[] = (int)$row['estudiantes_reprobados_o_sin_notas'];

}

$stmt_estudiantes_nivel->close();

$etiqueta_grafica = $cantidad_lapsos == 3 ? "Estudiantes Promovidos/No Promovidos" : "Estudiantes Aprobados/Reprobados en el Lapso $cantidad_lapsos";
?>

<div class="container card card-body">
  <h1>Bienvenido, <?= htmlspecialchars($user) ?></h1>
  <p>¡Gracias por iniciar sesión!</p>
  <div class="row">
    <!-- Gráfico 1: Total de estudiantes por nivel de estudio -->
    <div class="col-md-6">
      <canvas id="chartEstudiantes"></canvas>
    </div>
    <!-- Gráfico 2: Aprobados/Reprobados por nivel de estudio -->
    <div class="col-md-6">
      <canvas id="chartAprobadosReprobados"></canvas>
    </div>
  </div>
</div>

<script>
  // Generar colores aleatorios
  function getRandomColor() {
    const letters = '0123456789ABCDEF';
    let color = '#';
    for (let i = 0; i < 6; i++) {
      color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
  }

  // Asignar un color diferente para cada nivel
  const colors = <?= json_encode($niveles) ?>.map(() => getRandomColor());

  // Gráfico 1: Total de Estudiantes por Nivel de Estudio
  const ctxEstudiantes = document.getElementById('chartEstudiantes').getContext('2d');
  new Chart(ctxEstudiantes, {
    type: 'bar',
    data: {
      labels: <?= json_encode($niveles) ?>, // Niveles como etiquetas
      datasets: [{
        label: 'Total de Estudiantes',
        data: <?= json_encode($total_estudiantes_nivel) ?>, // Datos de estudiantes por nivel
        borderWidth: 1,
        backgroundColor: colors // Usar colores dinámicos
      }]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      },
      responsive: true,
      maintainAspectRatio: false
    }
  });

  // Gráfico 2: Aprobados y Reprobados por Nivel de Estudio
  const ctxAprobadosReprobados = document.getElementById('chartAprobadosReprobados').getContext('2d');
  new Chart(ctxAprobadosReprobados, {
    type: 'bar',
    data: {
      labels: <?= json_encode($niveles) ?>, // Niveles como etiquetas
      datasets: [{
          label: '<?= $etiqueta_grafica ?> - Aprobados',
          data: <?= json_encode($estudiantes_aprobados) ?>,
          borderWidth: 1,
          backgroundColor: '#76B041'
        },
        {
          label: '<?= $etiqueta_grafica ?> - Reprobados o Sin Notas',
          data: <?= json_encode($estudiantes_reprobados) ?>,
          borderWidth: 1,
          backgroundColor: '#D32F2F'
        }
      ]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      },
      responsive: true,
      maintainAspectRatio: false
    }
  });
</script>
<?php include __DIR__ . '/partials/footer.php' ?>
