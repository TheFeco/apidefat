<?php
include_once 'db/conexion.php';
$objeto = new Conexion();
$conexion = $objeto->Conectar();
header('Access-Control-Allow-Origin: *');

if($_SERVER['REQUEST_METHOD']=='POST'){
    $token = $_POST['token'];
    // Aquí puedes validar el token en tu base de datos
    $consulta = "SELECT * FROM usuarios_token WHERE Token = :token";
    $resultado = $conexion->prepare($consulta);
    $resultado->bindValue(':token', $token);
    $resultado->execute();
    
    if($resultado->rowCount() > 0){
        // El token es válido
        echo json_encode(array('valido' => true));
    } else {
        // El token es inválido
        echo json_encode(array('valido' => false));
    }
}
?>