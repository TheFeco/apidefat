<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

include_once "clases/usuarios.class.php";
include_once 'clases/respuestas.class.php';

$_respuestas = new respuestas;
$_usuarios = new usuarios;

if($_SERVER['REQUEST_METHOD']=='POST'){
    if($_POST['METHOD']=='PUT'){
        $postBody = array(
            'usuarioId' => $_POST['id'],
            'password' => $_POST['password'],
            'token' => $_POST['token']
        );

        $datosArray = $_usuarios->putPassword($postBody);
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
    else{
        header('Content-Type: application/json');
        $datosArray = $_respuestas->error_405();
        echo json_encode($datosArray);
    }
}

?>