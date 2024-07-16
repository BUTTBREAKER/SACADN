<?php
require __DIR__ . '/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$cedula = $_POST['cedula'];

$nombreArchivo="Notas certificadas.xlsx";

// Crear o cargar un hoja de cálculo:
$hojaCalculo = IOFactory::load('../Assets/templates_reports/plantilla_notas_certificada.xlsx');

$hojaCalculoActiva = $hojaCalculo->getActiveSheet();

//edición celda
$hojaCalculoActiva->setCellValue('D11', "V-$cedula");

// Redirigir al cliente a la página web un archivo (xlsx)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment;filename=$nombreArchivo");
header('Cache-Control: max-age=0'); 

$escribir = new Xlsx($hojaCalculo);
$escribir->save('php://output'); 
