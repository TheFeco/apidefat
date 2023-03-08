<?php
require_once __DIR__ . '/vendor/autoload.php';

// Crear objeto mpdf
$mpdf = new \Mpdf\Mpdf();

// Generar el contenido del PDF
$mpdf->WriteHTML('<h1>Hola, mundo!</h1>');

// Verificar si hay errores
if ($mpdf->getError()) {
    echo 'Error al crear el PDF: ' . $mpdf->getError();
}

// Descargar el archivo PDF
$mpdf->Output();