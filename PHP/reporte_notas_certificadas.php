<?php
require __DIR__ . '/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Incluir el archivo de conexión a la base de datos
/** @var mysqli */
$db = require_once __DIR__ . '/conexion_be.php';

$cedula = $_POST['cedula'];

$nombreArchivo = "Notas_certificadas.xlsx";

// Función para convertir números a letras (limitado a 20)
function numeroALetras($numero)
{
    $numerosEnLetras = array(
        0 => 'CERO',
        1 => 'UNO',
        2 => 'DOS',
        3 => 'TRES',
        4 => 'CUATRO',
        5 => 'CINCO',
        6 => 'SEIS',
        7 => 'SIETE',
        8 => 'OCHO',
        9 => 'NUEVE',
        10 => 'DIEZ',
        11 => 'ONCE',
        12 => 'DOCE',
        13 => 'TRECE',
        14 => 'CATORCE',
        15 => 'QUINCE',
        16 => 'DIECISÉIS',
        17 => 'DIECISIETE',
        18 => 'DIECIOCHO',
        19 => 'DIECINUEVE',
        20 => 'VEINTE'
    );

    return $numerosEnLetras[$numero];
}

// Verificar si la cédula existe en la base de datos
$query = $db->prepare("SELECT * FROM estudiantes WHERE cedula = ?");
$query->bind_param('i', $cedula);
$query->execute();
$result = $query->get_result();
$estudiante = $result->fetch_assoc();

if (!$estudiante) {
    header('Content-Type: text/plain; charset=utf-8');
    echo "Estudiante no encontrado.";
    exit;
}

// Consulta para obtener la dirección del representante
$query = $db->prepare("
    SELECT representantes.direccion
    FROM representantes
    INNER JOIN estudiantes ON representantes.id = estudiantes.id_representante
    WHERE estudiantes.cedula = ?
");
$query->bind_param('i', $cedula);
$query->execute();
$result = $query->get_result();
$representante = $result->fetch_assoc();
$direccionRepresentante = $representante ? $representante['direccion'] : 'Dirección no encontrada';

// Consulta para obtener las notas de los tres momentos
$query = $db->prepare("
    SELECT
        materias.nombre AS materia,
        AVG(calificaciones.calificacion) AS definitiva
    FROM calificaciones
    INNER JOIN boletines ON calificaciones.id_boletin = boletines.id
    INNER JOIN materias ON calificaciones.id_materia = materias.id
    WHERE boletines.id_estudiante = ?
    GROUP BY materias.nombre
");
$query->bind_param('i', $estudiante['id']);
$query->execute();
$result = $query->get_result();

// Cargar la plantilla de Excel
$hojaCalculo = IOFactory::load('../Assets/templates_reports/plantilla_notas_certificada.xlsx');
$hojaCalculoActiva = $hojaCalculo->getActiveSheet();

// Colocar los datos del estudiante en la plantilla
$hojaCalculoActiva->setCellValue('D11', "V-" . $estudiante['cedula']);
$hojaCalculoActiva->setCellValue('B12', $estudiante['nombres']);
$hojaCalculoActiva->setCellValue('N12', $estudiante['apellidos']);
$hojaCalculoActiva->setCellValue('C8', $direccionRepresentante); // Agregar la dirección del representante en la celda C8
$hojaCalculoActiva->setCellValue('N11', $estudiante['fecha_nacimiento']); // Agregar la fecha de nacimiento en la celda N11

// Colocar las notas en la plantilla de Excel
$filaInicio = 22; // Fila donde comienzan las notas en la plantilla
while ($row = $result->fetch_assoc()) {
    $notaNumerica = round($row['definitiva']);
    $notaEnLetras = numeroALetras($notaNumerica);
    $hojaCalculoActiva->setCellValue('A' . $filaInicio, $row['materia']);
    $hojaCalculoActiva->setCellValue('D' . $filaInicio, $notaNumerica);
    $hojaCalculoActiva->setCellValue('E' . $filaInicio, $notaEnLetras);
    $filaInicio++;
}

// Configurar las cabeceras de la respuesta HTTP
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment;filename=\"$nombreArchivo\"");
header('Cache-Control: max-age=0');

$escribir = new Xlsx($hojaCalculo);
$escribir->save('php://output');
exit;
?>
