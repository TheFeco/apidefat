<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: text/csv');

include_once 'db/conexion.php';
$objeto = new Conexion();
$conexion = $objeto->Conectar();

$baseURL = $objeto->baseUrl();

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    $consulta = "SELECT d.folio, UPPER(d.nombre) AS nombre, UPPER(d.apellidos) AS apellidos, d.curp,
    DATE_FORMAT(d.fh_nacimiento, '%d/%m/%Y') AS fh_nacimiento, d.cct, d.escuela, d.zona, 
    CASE WHEN turno = 1 THEN 'Matutino' 
         WHEN turno = 2 THEN 'vespertino' 
         WHEN turno = 3 THEN 'Nocturno'
         WHEN turno = 4 THEN 'Discontinuo'
         WHEN turno = 5 THEN 'Continuo'
    END AS turno,
    c.nombre AS ciclo, m.nombre AS municipio, f.nombre AS funcion,
    dp.nombre AS deporte, r.nombre AS rama, cat.nombre AS categoria, 
    peso.nombre AS peso, pruebas.nombre AS prueba,
    CONCAT(CASE WHEN d.curp_pdf <> '' THEN '$baseURL' ELSE '' END, d.curp_pdf) AS curp_pdf,
    CONCAT(CASE WHEN d.cert_medico <> '' THEN '$baseURL' ELSE '' END, d.cert_medico) AS cert_medico,
    CONCAT(CASE WHEN d.carta_responsiva <> '' THEN '$baseURL' ELSE '' END, d.carta_responsiva) AS carta_responsiva,
    CONCAT(CASE WHEN d.ine <> '' THEN '$baseURL' ELSE '' END, d.ine) AS ine,
    CONCAT(CASE WHEN d.constancia_autorizacion <> '' THEN '$baseURL' ELSE '' END, d.constancia_autorizacion) AS constancia_autorizacion,
    CONCAT(CASE WHEN d.constancia_servicio <> '' THEN '$baseURL' ELSE '' END, d.constancia_servicio) AS constancia_servicio,
    user.usuario
    FROM deportistas AS d
    INNER JOIN ciclos AS c ON d.id_ciclo = c.id
    INNER JOIN funciones AS f ON d.id_funcion = f.id
    INNER JOIN municipios AS m ON d.id_municipio = m.id
    LEFT JOIN deportes AS dp ON d.id_deporte = dp.id
    LEFT JOIN ramas AS r ON d.id_rama = r.id
    LEFT JOIN categorias AS cat ON d.id_categoria = cat.id
    LEFT JOIN peso ON d.id_peso = peso.id
    LEFT JOIN pruebas ON d.id_prueba = pruebas.id
    INNER JOIN usuarios AS user ON (d.id_usuairo = user.id)
    WHERE d.id_usuairo NOT IN ( 35,38 )
    ORDER BY user.id";

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
        
        outputcsv($data, $temp_file);
        
        exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
}

function outputCsv( $assocDataArray, $temp_file ) {
    if ( !empty( $assocDataArray ) ):
        $fp = fopen( $temp_file, 'w' );
        fputs( $fp, $bom = chr(0xEF) . chr(0xBB) . chr(0xBF) );
        fputcsv( $fp, array_keys( reset($assocDataArray) ) );

        foreach ( $assocDataArray AS $values ):
            fputcsv( $fp, $values );
        endforeach;

        fclose( $fp );
    endif;
    $d = array("file" => $temp_file, "name" => 'ExportExcel.csv' );
    header("HTTP/1.1 200 OK");
    return print json_encode($d);
    exit();
}