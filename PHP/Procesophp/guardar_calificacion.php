<?php
require_once __DIR__ . '/../conexion_be.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id_asignacion = $_POST['id_asignacion'] ?? null;
  $id_estudiante = $_POST['id_estudiante'] ?? null;
  $id_momento = $_POST['id_momento'] ?? null;
  $calificacion = $_POST['calificacion'] ?? null;

  // Obtener el id_usuario de la sesión
  $id_usuario = $_SESSION['usuario_id'] ?? null;

  if ($id_asignacion && $id_estudiante && $id_momento && $calificacion !== null && $id_usuario) {
    // Validar el rango de la calificación
    if ($calificacion < 0 || $calificacion > 20) {
      echo json_encode(['status' => 'error', 'message' => 'La calificación debe estar entre 0 y 20']);
      exit;
    }

    // Obtener el periodo activo
    $stmt_periodo = $conexion->prepare("SELECT id FROM periodos WHERE estado = 'activo' LIMIT 1");
    $stmt_periodo->execute();
    $result_periodo = $stmt_periodo->get_result();
    $periodo = $result_periodo->fetch_assoc();
    $stmt_periodo->close();

    if (!$periodo) {
      echo json_encode(['status' => 'error', 'message' => 'No hay un periodo activo']);
      exit;
    }

    $id_periodo = $periodo['id'];

    // Iniciar transacción
    $conexion->begin_transaction();

    try {
      // Verificar si existe un boletín para el estudiante en este momento y periodo
      $stmt_check_boletin = $conexion->prepare("
        SELECT id FROM boletines
        WHERE id_estudiante = ? AND id_momento = ? AND id_periodo = ?
        LIMIT 1
      ");
      $stmt_check_boletin->bind_param("iii", $id_estudiante, $id_momento, $id_periodo);
      $stmt_check_boletin->execute();
      $result_check_boletin = $stmt_check_boletin->get_result();
      $boletin_existente = $result_check_boletin->fetch_assoc();
      $stmt_check_boletin->close();

      if ($boletin_existente) {
        $boletin_id = $boletin_existente['id'];
      } else {
        // Crear nuevo boletín si no existe
        $stmt_crear_boletin = $conexion->prepare("
          INSERT INTO boletines (id_estudiante, id_momento, id_periodo)
          VALUES (?, ?, ?)
        ");
        $stmt_crear_boletin->bind_param("iii", $id_estudiante, $id_momento, $id_periodo);
        $stmt_crear_boletin->execute();
        $boletin_id = $stmt_crear_boletin->insert_id;
        $stmt_crear_boletin->close();
      }

      // Obtener el id_materia de la asignación
      $stmt_materia = $conexion->prepare("SELECT id_materia FROM asignaciones WHERE id = ?");
      $stmt_materia->bind_param("i", $id_asignacion);
      $stmt_materia->execute();
      $result_materia = $stmt_materia->get_result();
      $materia = $result_materia->fetch_assoc();
      $stmt_materia->close();

      if (!$materia) {
        throw new Exception("No se encontró la asignación especificada.");
      }

      $id_materia = $materia['id_materia'];

      // Verificar si existe una calificación para este boletín y materia
      $stmt_check_calificacion = $conexion->prepare("
        SELECT id FROM calificaciones
        WHERE id_boletin = ? AND id_materia = ?
        LIMIT 1
      ");
      $stmt_check_calificacion->bind_param("ii", $boletin_id, $id_materia);
      $stmt_check_calificacion->execute();
      $result_check_calificacion = $stmt_check_calificacion->get_result();
      $calificacion_existente = $result_check_calificacion->fetch_assoc();
      $stmt_check_calificacion->close();

      if ($calificacion_existente) {
        // Actualizar la calificación existente
        $stmt_actualizar_calificacion = $conexion->prepare("
          UPDATE calificaciones
          SET calificacion = ?, id_usuario = ?
          WHERE id = ?
        ");
        $stmt_actualizar_calificacion->bind_param("dii", $calificacion, $id_usuario, $calificacion_existente['id']);
        $stmt_actualizar_calificacion->execute();
        $stmt_actualizar_calificacion->close();
      } else {
        // Insertar nueva calificación
        $stmt_insertar_calificacion = $conexion->prepare("
          INSERT INTO calificaciones (id_boletin, id_materia, calificacion, id_periodo, id_usuario)
          VALUES (?, ?, ?, ?, ?)
        ");
        $stmt_insertar_calificacion->bind_param("iidii", $boletin_id, $id_materia, $calificacion, $id_periodo, $id_usuario);
        $stmt_insertar_calificacion->execute();
        $stmt_insertar_calificacion->close();
      }

      // Confirmar transacción
      $conexion->commit();

      echo json_encode(['status' => 'success', 'message' => 'Calificación guardada correctamente']);
    } catch (Exception $e) {
      // Revertir transacción en caso de error
      $conexion->rollback();
      echo json_encode(['status' => 'error', 'message' => 'Error al guardar la calificación: ' . $e->getMessage()]);
    }
  } else {
    echo json_encode(['status' => 'error', 'message' => 'Datos incompletos o usuario no autenticado']);
  }
} else {
  echo json_encode(['status' => 'error', 'message' => 'Método no permitido']);
}
