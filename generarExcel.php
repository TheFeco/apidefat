<?php
include_once 'db/conexion.php';
$objeto = new Conexion();
$conexion = $objeto->Conectar();
header('Access-Control-Allow-Origin: *');
$_POST['METHOD']='POST';
if($_POST['METHOD']=='POST'){
    //Variables del post
    $id_usuario = isset($_POST['usuario']) ? $_POST['usuario'] : 2;
    $id_ciclo = isset($_POST['ciclo']) ? $_POST['ciclo'] : 1;
    $id_funcion = isset($_POST['funcion']) ? $_POST['funcion'] : 1;
    $id_deporte = isset($_POST['deporte']) ? $_POST['deporte'] : 3;
    $id_rama = isset($_POST['rama']) ? $_POST['rama'] : 0;
    $id_categoria = isset($_POST['categoria']) ? $_POST['categoria'] : 0;
    $id_peso = isset($_POST['peso']) ? $_POST['peso'] : 0;
    $id_prueba = isset($_POST['prueba']) ? $_POST['prueba'] : 0;

    //Consultas de Mysql que trae
    $consulta = "SELECT d.folio, d.nombre, d.apellidos, d.curp, d.foto,DATE_FORMAT(d.fh_nacimiento,'%d/%m/%Y') AS fh_nacimeinto, d.cct, d.escuela, d.zona, CASE WHEN turno = 1 THEN 'Matutino' WHEN turno = 2 THEN 'vespertino' END AS turno,c.nombre AS ciclo, m.nombre AS municipio, f.nombre AS funcion, dp.nombre AS deporte, r.nombre AS rama, cat.nombre AS categoria, peso.nombre AS peso, pruebas.nombre AS prueba, CONCAT_WS(':, ', cat.nombre,peso.nombre,pruebas.nombre) AS array_pruebas
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
    // Obtenemos las columnas
    $columns = [];
    for ($i = 0; $i < $resultado->columnCount(); $i++) {
        $columns[] = $resultado->getColumnMeta($i)['name'];
    }
    header("Content-Type:$contentType"); 
    header("Content-Disposition:attachment;filename=output.$outputFileExtension");
    require 'vendor/autoload.php';
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setCellValue('A1', 'Hello World !');

    $writer = new Xlsx($spreadsheet);
    $writer->save('hello world.xlsx');
}else{
    header("HTTP/1.1 500 Ok");
    $d = array('menssage' => "Error no es usuario administrador");
    return print json_encode($d);
    $conexion = NULL;
}