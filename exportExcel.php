<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: text/csv');

include_once 'db/conexion.php';
$objeto = new Conexion();
$conexion = $objeto->Conectar();

$baseURL = $objeto->baseUrl();

if($_POST['METHOD']=='POST'){
    print_r($_POST);
    die();
    //Variables del post
    $id_usuario = isset($_POST['usuario']);
    $cct = isset($_POST['cct']) ? $_POST['cct'] : 0;
    $id_ciclo = isset($_POST['id_ciclo']) ? $_POST['id_ciclo'] : 1;
    $id_funcion = isset($_POST['id_funcion']) ? $_POST['id_funcion'] : 0;
    $id_deporte = isset($_POST['id_deporte']) ? $_POST['id_deporte'] : 0;
    $id_rama = isset($_POST['id_rama']) ? $_POST['id_rama'] : 0;
    $id_categoria = isset($_POST['categoria']) ? $_POST['categoria'] : 0;
    $id_peso = isset($_POST['peso']) ? $_POST['peso'] : 0;
    $id_prueba = isset($_POST['prueba']) ? $_POST['prueba'] : 0;

    //Consultas de Mysql que trae
    $consulta = "SELECT d.folio, UPPER(d.nombre) AS nombre, UPPER(d.apellidos) AS apellidos , d.curp, DATE_FORMAT(d.fh_nacimiento,'%d/%m/%Y') AS fh_nacimeinto, d.cct, d.escuela, d.zona, CASE WHEN turno = 1 THEN 'Matutino' WHEN turno = 2 THEN 'vespertino' END AS turno,c.nombre AS ciclo, m.nombre AS municipio, f.nombre AS funcion, dp.nombre AS deporte, r.nombre AS rama, cat.nombre AS categoria, peso.nombre AS peso, pruebas.nombre AS prueba,
    CONCAT('$baseURL', d.acta_nacimiento) AS acta_nacimiento, 
    CONCAT('$baseURL', d.curp_pdf) AS curp_pdf, 
    CONCAT('$baseURL', d.cert_medico) AS cert_medico, 
    CONCAT('$baseURL', d.carta_responsiva) AS carta_responsiva, 
    CONCAT('$baseURL', d.ine) AS ine, 
    CONCAT('$baseURL', d.constancia_autorizacion) AS constancia_autorizacion, 
    CONCAT('$baseURL', d.constancia_servicio) AS constancia_servicio
    FROM deportistas AS d 
    INNER JOIN ciclos AS c ON (d.id_ciclo = c.id) 
    INNER JOIN funciones AS f  ON (d.id_funcion = f.id) 
    INNER JOIN municipios AS m ON( d.id_municipio = m.id) 
    LEFT JOIN deportes AS dp ON (d.id_deporte = dp.id) 
    LEFT JOIN ramas AS r ON( d.id_rama = r.id )
    LEFT JOIN categorias AS cat ON ( d.id_categoria = cat.id)
    LEFT JOIN peso ON (d.id_peso = peso.id)
    LEFT JOIN pruebas ON (d.id_prueba = pruebas.id)
    WHERE d.id_usuairo = '$id_usuario'
    AND d.id_ciclo = '$id_ciclo'
    AND d.id_funcion = '$id_funcion' 
    AND d.cct = '$cct'
    ";
    if($id_deporte != 0){
        $consulta .= "AND d.id_deporte = '$id_deporte' "; 
    }
    if($id_rama != 0){
        $consulta .= "AND d.id_rama = '$id_rama' "; 
    }
    if($id_categoria != 0){
        $consulta .= "AND d.id_categoria = '$id_categoria' "; 
    }
    if($id_peso != 0){
        $consulta .= "AND d.id_peso = '$id_peso' "; 
    }
    if($id_prueba != 0){
        $consulta .= "AND d.id_prueba = '$id_prueba' "; 
    }
    print_r($consulta);
    die();
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
        $temp_file = 'files/tmp/Excel.csv';
        
        outputCsv($data, $temp_file);
        
        exit;
        
        

}else{
    header("HTTP/1.1 500 Ok");
    $d = array('menssage' => "Error no es usuario administrador");
    return print json_encode($d);
    $conexion = NULL;
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