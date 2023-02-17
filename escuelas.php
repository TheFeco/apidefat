<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

include_once "clases/escuelas.class.php";
include_once 'clases/respuestas.class.php';

$_respuestas = new respuestas;
$_escuelas =  new escuelas;

if($_SERVER['REQUEST_METHOD']=='GET'){
    if(isset($_GET["escuela"])){
        $escuela = $_GET["escuela"];
        $nomEscuela = $_escuelas->getNombre($escuela);
        header("Content-Type: application/json");
        echo json_encode($nomEscuela);
        http_response_code(200);
    }else{
        header('Content-Type: application/json');
        $datosArray = $_respuestas->response;
        echo json_encode($datosArray);
    }
    
}

if($_SERVER['REQUEST_METHOD']=='POST'){

}

?>