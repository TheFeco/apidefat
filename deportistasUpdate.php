<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

include_once "clases/deportistas.class.php";
include_once 'clases/respuestas.class.php';

$_respuestas = new respuestas;
$_deportistas =  new deportistas;

if($_SERVER['REQUEST_METHOD'] == "GET"){

    if(isset($_GET['id_deportista'])){
        $id_deportista = $_GET['id_deportista'];
        $token = $_GET['token'];
        $datosDeportista = $_deportistas->obtenerById($id_deportista,$token);
        header("Content-Type: application/json");
        echo json_encode($datosDeportista);
        http_response_code(200);
    }else{
        header('Content-Type: application/json');
        $datosArray = $_respuestas->error_405();
        echo json_encode($datosArray);
    }
}else if($_SERVER['REQUEST_METHOD']=='POST'){   
    
    //recibimos los datos enviados
    $postBody = file_get_contents("php://input");
    //enviamos datos al manejador
    $datosArray = $_pacientes->put($postBody);
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

?>