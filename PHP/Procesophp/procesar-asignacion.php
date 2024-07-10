<?php

$db = require_once __DIR__ . '/../conexion_be.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_POST['materias']) && isset($_POST['seccion']) && isset($_POST['anio'])) {
    $anio = (int) $_POST['anio'];
    $materias = $_POST['materias'];
    $seccion = (int) $_POST['seccion'];

    // Validar que el ID del nivel de estudio sea válido
    $stmt_nivel = $db->prepare("SELECT id FROM niveles_estudio WHERE id = ?");
    $stmt_nivel->bind_param("i", $anio);
    $stmt_nivel->execute();
    $result_nivel = $stmt_nivel->get_result();
    if ($result_nivel->num_rows === 0) {
      $stmt_nivel->close();
      header("Location: ../asignar-materias.php?error=invalid_level");
      exit();
    }
    $stmt_nivel->close();

    // Validar que el ID de la sección sea válido
    $stmt_seccion = $db->prepare("SELECT id FROM secciones WHERE id = ?");
    $stmt_seccion->bind_param("i", $seccion);
    $stmt_seccion->execute();
    $result_seccion = $stmt_seccion->get_result();
    if ($result_seccion->num_rows === 0) {
      $stmt_seccion->close();
      header("Location: ../asignar-materias.php?error=invalid_section");
      exit();
    }
    $stmt_seccion->close();

    // Obtener el periodo actual (aquí asumo que solo hay un periodo activo)
    $stmt_periodo = $db->prepare("SELECT id FROM periodos WHERE anio_inicio = ?");
    $anio_actual = date('Y');
    $stmt_periodo->bind_param("i", $anio_actual);
    $stmt_periodo->execute();
    $result_periodo = $stmt_periodo->get_result();
    if ($result_periodo->num_rows === 0) {
      $stmt_periodo->close();
      header("Location: ../asignar-materias.php?error=no_period");
      exit();
    }
    $periodo = $result_periodo->fetch_assoc()['id'];
    $stmt_periodo->close();

    // Insertar las asignaciones de materias
    $stmt_asignacion = $db->prepare("INSERT INTO asignaciones_materias (id_periodo, id_materia, id_nivel_estudio, id_seccion) VALUES (?, ?, ?, ?)");
    foreach ($materias as $materia) {
      $materia = (int) $materia;
      $stmt_asignacion->bind_param("iiii", $periodo, $materia, $anio, $seccion);
      $stmt_asignacion->execute();
    }
    $stmt_asignacion->close();

    header("Location: ../asignar-materias.php?success=true");
    exit();
  } else {
    header("Location: ../asignar-materias.php?error=missing_data");
    exit();
  }
} else {
  header("Location: ../asignar-materias.php");
  exit();
}
