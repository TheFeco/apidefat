<?php
include_once 'db/conexion.php';
$objeto = new Conexion();
$conexion = $objeto->Conectar();
header('Access-Control-Allow-Origin: *');
$_POST['METHOD']='POST';
$baseURL = $objeto->baseUrl();
if($_POST['METHOD']=='POST'){
    //Variables del post
    $id_usuario = $_POST['usuario'];
    $id_ciclo = isset($_POST['ciclo']) ? $_POST['ciclo'] : 1;
    $id_funcion = isset($_POST['funcion']) ? $_POST['funcion'] : 0;
    $id_deporte = isset($_POST['deporte']) ? $_POST['deporte'] : 0;
    $id_rama = isset($_POST['rama']) ? $_POST['rama'] : 0;
    $id_categoria = isset($_POST['categoria']) ? $_POST['categoria'] : 0;
    $id_peso = isset($_POST['peso']) ? $_POST['peso'] : 0;
    $id_prueba = isset($_POST['prueba']) ? $_POST['prueba'] : 0;

    //Consultas de Mysql que trae
    $consulta = "SELECT d.folio, UPPER(d.nombre) AS nombre, UPPER(d.apellidos) AS apellidos , d.curp, DATE_FORMAT(d.fh_nacimiento,'%d/%m/%Y') AS fh_nacimeinto, d.cct, d.escuela, d.zona, 
    CASE WHEN turno = 1 THEN 'Matutino' 
         WHEN turno = 2 THEN 'vespertino' 
         WHEN turno = 3 THEN 'Nocturno'
         WHEN turno = 4 THEN 'Discontinuo'
         WHEN turno = 5 THEN 'Continuo'
    END AS turno,
    c.nombre AS ciclo, m.nombre AS municipio, f.nombre AS funcion, dp.nombre AS deporte, r.nombre AS rama, cat.nombre AS categoria, peso.nombre AS peso, pruebas.nombre AS prueba,
    CONCAT(CASE WHEN d.curp_pdf <> '' THEN '$baseURL' ELSE '' END, d.curp_pdf) AS curp_pdf,
    CONCAT(CASE WHEN d.cert_medico <> '' THEN '$baseURL' ELSE '' END, d.cert_medico) AS cert_medico,
    CONCAT(CASE WHEN d.carta_responsiva <> '' THEN '$baseURL' ELSE '' END, d.carta_responsiva) AS carta_responsiva,
    CONCAT(CASE WHEN d.ine <> '' THEN '$baseURL' ELSE '' END, d.ine) AS ine,
    CONCAT(CASE WHEN d.constancia_autorizacion <> '' THEN '$baseURL' ELSE '' END, d.constancia_autorizacion) AS constancia_autorizacion,
    CONCAT(CASE WHEN d.constancia_servicio <> '' THEN '$baseURL' ELSE '' END, d.constancia_servicio) AS constancia_servicio,
    user.usuario
    FROM deportistas AS d 
    INNER JOIN ciclos AS c ON (d.id_ciclo = c.id) 
    INNER JOIN funciones AS f  ON (d.id_funcion = f.id) 
    INNER JOIN municipios AS m ON( d.id_municipio = m.id) 
    LEFT JOIN deportes AS dp ON (d.id_deporte = dp.id) 
    LEFT JOIN ramas AS r ON( d.id_rama = r.id )
    LEFT JOIN categorias AS cat ON ( d.id_categoria = cat.id)
    LEFT JOIN peso ON (d.id_peso = peso.id)
    LEFT JOIN pruebas ON (d.id_prueba = pruebas.id)
    INNER JOIN usuarios AS user ON (d.id_usuairo = user.id)
    WHERE d.id_usuairo = '$id_usuario'
    AND d.id_ciclo = '$id_ciclo'
    AND d.id_funcion = '$id_funcion' 
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
        $temp_file = 'files/tmp/ExportExcel.csv';
        
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