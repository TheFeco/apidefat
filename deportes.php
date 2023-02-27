<?php
include_once 'db/conexion.php';
$objeto = new Conexion();
$conexion = $objeto->Conectar();

header('Access-Control-Allow-Origin: *');


$consultaCiclo = "SELECT id, nombre FROM ciclos ORDER BY id DESC LIMIT 1";
$resultado = $conexion->prepare($consultaCiclo);
$resultado->execute();
$ciclos=$resultado->fetchAll(PDO::FETCH_ASSOC);

$consulta = "SELECT id, nombre FROM deportes ORDER BY id";
$resultado = $conexion->prepare($consulta);
$resultado->execute();

$data=$resultado->fetchAll(PDO::FETCH_ASSOC);

// 
$d = array('deportes' => $data, 'ciclos' => $ciclos,);
print json_encode($d)
?>