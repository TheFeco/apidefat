<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

echo "ptos";

die();

include_once "clases/usuarios.class.php";
include_once 'clases/respuestas.class.php';

$_respuestas = new respuestas;
$_usuarios =  new usuarios;

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    if (isset($_GET['id'])) {
        $datosArray = $_usuarios->obtenerConId($_GET["id"], $_GET["token"]);
    } else {
        $datosArray = $_usuarios->obtener($_GET["token"]);
    }
    //delvovemos una respuesta 
    header('Content-Type: application/json');
    if (isset($datosArray["result"]["error_id"])) {
        $responseCode = $datosArray["result"]["error_id"];
        http_response_code($responseCode);
    } else {
        http_response_code(200);
    }

    echo json_encode($datosArray);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_POST['METHOD'] == 'POST') {
        unset($_POST['METHOD']);
        $postBody = array(
            'usuario' => $_POST['usuario'],
            'password' => $_POST['password'],
            'rol' => $_POST['rol'],
            'nivel' => $_POST['nivel'],
            'token' => $_POST['token']
        );
        //enviamos los datos al manejador
        $datosArray = $_usuarios->post($postBody);
        //delvovemos una respuesta 
        header('Content-Type: application/json');
        if (isset($datosArray["result"]["error_id"])) {
            $responseCode = $datosArray["result"]["error_id"];
            http_response_code($responseCode);
        } else {
            http_response_code(200);
        }
        echo json_encode($datosArray);

    } else if ($_POST['METHOD'] == 'PUT') {

        //recibimos los datos enviados
        $postBody = array(
            'id' => $_POST['id'],
            'usuario' => $_POST['usuario'],
            'password' => $_POST['password'],
            'rol' => $_POST['rol'],
            'nivel' => $_POST['nivel'],
            'token' => $_POST['token']
        );
        //enviamos datos al manejador
        $datosArray = $_usuarios->put($postBody);
            //delvovemos una respuesta 
        header('Content-Type: application/json');
        if(isset($datosArray["result"]["error_id"])){
            $responseCode = $datosArray["result"]["error_id"];
            http_response_code($responseCode);
        }else{
            http_response_code(200);
        }
        echo json_encode($datosArray);

    } else if ($_POST['METHOD'] == 'DELETE') {

        $headers = getallheaders();
        if (isset($headers["token"]) && isset($headers["id"])) {
            //recibimos los datos enviados por el header
            $send = [
                "token" => $headers["token"],
                "id" => $headers["id"]
            ];
            $postBody = json_encode($send);
        } else {
            //recibimos los datos enviados
            $postBody = array(
                'id' => $_POST['id'],
                'token' => $_POST['token']
            );
        }

        //enviamos datos al manejador
        $datosArray = $_usuarios->delete($postBody);
        //delvovemos una respuesta 
        header('Content-Type: application/json');
        if (isset($datosArray["result"]["error_id"])) {
            $responseCode = $datosArray["result"]["error_id"];
            http_response_code($responseCode);
        } else {
            http_response_code(200);
        }
        echo json_encode($datosArray);

    } else {
        header('Content-Type: application/json');
        $datosArray = $_respuestas->error_405();
        echo json_encode($datosArray);
    }
}
