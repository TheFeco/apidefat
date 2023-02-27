<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

include_once "clases/ciclos.class.php";
include_once 'clases/respuestas.class.php';

$_respuestas = new respuestas;
$_ciclos =  new ciclos;

if($_SERVER['REQUEST_METHOD']=='GET'){   
    var_dump($_GET["token"]);
    $datosArray = $_ciclos->obtener($_GET["token"]);
    //delvovemos una respuesta 
     header('Content-Type: application/json');
     if(isset($datosArray["result"]["error_id"])){
         $responseCode = $datosArray["result"]["error_id"];
         http_response_code($responseCode);
     }else{
         http_response_code(200);
     }

     echo json_encode($datosArray);
    
}

if($_SERVER['REQUEST_METHOD']=='POST'){
    if($_POST['METHOD']=='POST'){
        unset($_POST['METHOD']);
        $postBody = array(
            'nombre' => $_POST['nombre'],
            'token' => $_POST['token']
        );
        //enviamos los datos al manejador
        $datosArray = $_ciclos->post($postBody);
        //delvovemos una respuesta 
        header('Content-Type: application/json');
        if(isset($datosArray["result"]["error_id"])){
            $responseCode = $datosArray["result"]["error_id"];
            http_response_code($responseCode);
        }else{
            http_response_code(200);
        }
        echo json_encode($datosArray);
    }else{
        header('Content-Type: application/json');
        $datosArray = $_respuestas->error_405();
        echo json_encode($datosArray);
    }
}
