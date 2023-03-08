<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

include_once 'db/conexion.php';
$objeto = new Conexion();
$conexion = $objeto->Conectar();

$baseURL = $objeto->baseUrl();

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    $consulta = "SELECT d.folio, UPPER(d.nombre) AS nombre, UPPER(d.apellidos) AS apellidos, d.curp,
    DATE_FORMAT(d.fh_nacimiento, '%d/%m/%Y') AS fh_nacimiento, d.cct, d.escuela, d.zona, 
    CASE WHEN turno = 1 THEN 'Matutino'
         WHEN turno = 2 THEN 'Vespertino' 
    END AS turno, c.nombre AS ciclo, m.nombre AS municipio, f.nombre AS funcion,
    dp.nombre AS deporte, r.nombre AS rama, cat.nombre AS categoria, 
    peso.nombre AS peso, pruebas.nombre AS prueba,
    CONCAT('$baseURL', d.acta_nacimiento) AS acta_nacimiento, 
    CONCAT('$baseURL', d.curp_pdf) AS curp_pdf, 
    CONCAT('$baseURL', d.cert_medico) AS cert_medico, 
    CONCAT('$baseURL', d.carta_responsiva) AS carta_responsiva, 
    CONCAT('$baseURL', d.ine) AS ine, 
    CONCAT('$baseURL', d.constancia_autorizacion) AS constancia_autorizacion, 
    CONCAT('$baseURL', d.constancia_servicio) AS constancia_servicio
    FROM deportistas AS d
    INNER JOIN ciclos AS c ON d.id_ciclo = c.id
    INNER JOIN funciones AS f ON d.id_funcion = f.id
    INNER JOIN municipios AS m ON d.id_municipio = m.id
    LEFT JOIN deportes AS dp ON d.id_deporte = dp.id
    LEFT JOIN ramas AS r ON d.id_rama = r.id
    LEFT JOIN categorias AS cat ON d.id_categoria = cat.id
    LEFT JOIN peso ON d.id_peso = peso.id
    LEFT JOIN pruebas ON d.id_prueba = pruebas.id";

    $resultado = $conexion->prepare($consulta);
    $resultado->execute();
    $rows = $resultado->rowCount();
    if($resultado->rowCount() >= 1){
        $data = $resultado->fetchAll(PDO::FETCH_ASSOC);

    }else{
        $d = array();
        header("HTTP/1.1 200 OK");
        return print json_encode($d);
        $conexion = NULL;
    }
        $temp_file = 'files/tmp/ExportarTodo.csv';
        var_dump($temp_file);
        die();
         $output= outputCsv($data, $temp_file);
         var_dump($output);
        
        exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
}