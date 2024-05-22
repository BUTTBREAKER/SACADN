<?php

// Incluir el archivo de conexión a la base de datos
require_once __DIR__ . '/../conexion_be.php';

// Verificar si el formulario se envió por el método POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar que se hayan seleccionado materias y secciones
    if (isset($_POST['materias']) && isset($_POST['seccion'])) {
        // Obtener los datos del formulario
        $anio = $_POST['anio'];
        $materias = $_POST['materias'];
        $seccion = $_POST['seccion'];

        // Iterar sobre las materias seleccionadas para asignarlas a la sección
        foreach ($materias as $materia) {
            // Realizar la inserción en la base de datos
            $stmt = $db->prepare("INSERT INTO asignaciones_materias (id_periodo, id_materia, id_nivel_estudio, id_seccion) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iiii", $id_periodo, $materia, $id_nivel_estudio, $seccion);
            $stmt->execute();
        }

        // Redirigir a la página de origen con un mensaje de éxito
        header("Location: ../asignar-materias.php?success=true");
        exit();
    } else {
        // Redirigir con un mensaje de error si no se seleccionaron materias o secciones
        header("Location: ../asignar-materias.php?error=true");
        exit();
    }
} else {
    // Si el formulario no se envió por POST, redirigir a la página de origen
    header("Location: ../asignar-materias.php");
    exit();
}

?>
