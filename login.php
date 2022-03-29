<?php
//session_start();

include_once 'db/conexion.php';
//include 'db/db.php';

header('Access-Control-Allow-Origin: *');
$objeto = new Conexion();
$conexion = $objeto->Conectar();

//recepción de datos enviados mediante POST desde ajax
$usuario = (isset($_POST['usuario'])) ? $_POST['usuario'] : '';
$password = (isset($_POST['password'])) ? $_POST['password'] : '';

$pass = md5($password); //encripto la clave enviada por el usuario para compararla con la clava encriptada y almacenada en la BD

$consulta = "SELECT id, id_rol, usuario, sn_actualizar FROM usuarios WHERE usuario='$usuario' AND password='$pass' ";

$resultado = $conexion->prepare($consulta);
$resultado->execute();

//$resultado=metodoGet($consulta); 
if($resultado->rowCount() >= 1){
    $data = $resultado->fetch(PDO::FETCH_ASSOC);
    $_SESSION["s_usuario"] = $usuario;
    $_SESSION["s_id"] = $data['id'];
}else{
    $_SESSION["s_usuario"] = null;
    $_SESSION["s_id"] = null;
    $data=null;
}

print json_encode($data);
$conexion=null;

//usuarios de pruebaen la base de datos
//usuario:admin pass:12345
//usuario:demo pass:demo