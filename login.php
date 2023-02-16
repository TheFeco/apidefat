<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

include_once 'db/conexion.php';

$objeto = new Conexion();
$conexion = $objeto->Conectar();

//recepción de datos enviados mediante POST desde ajax
$usuario = (isset($_POST['usuario'])) ? $_POST['usuario'] : '';
$password = (isset($_POST['password'])) ? $_POST['password'] : '';

$pass = md5($password); //encripto la clave enviada por el usuario para compararla con la clava encriptada y almacenada en la BD

$consulta = "SELECT id, id_rol, id_nivel, usuario, Estado FROM usuarios WHERE usuario='$usuario' AND password='$pass' ";
// echo $consulta;
$resultado = $conexion->prepare($consulta);
$resultado->execute();
// if ($resultado->execute()) {
//     // La consulta se ejecutó correctamente, se puede continuar con el procesamiento
//     echo $resultado->rowCount() ;
//   } else {
//     // Ocurrió un error en la consulta, se puede obtener información sobre el error
//     $errorInfo = $conexion->errorInfo();
//     // El mensaje de error se encuentra en la posición 2 del arreglo errorInfo
//     $mensajeError = $errorInfo[2];
//     // Puedes imprimir o enviar este mensaje de error al cliente para que lo vea
//     echo "Ocurrió un error al ejecutar la consulta: " . $mensajeError;
//     // También puedes hacer alguna otra acción para manejar el error, como registrar en un archivo de logs, enviar un correo electrónico al administrador, etc.
//   }

//$resultado=metodoGet($consulta); 
if($resultado->rowCount() >= 1){
    $data = $resultado->fetch(PDO::FETCH_ASSOC);
     // Llamada a la función insertarToken para generar y almacenar el token en la base de datos
     $token = insertarToken($data['id']);
     $data['token'] = $token;
    $_SESSION["s_usuario"] = $usuario;
    $_SESSION["s_id"] = $data['id'];
}else{
    $_SESSION["s_usuario"] = null;
    $_SESSION["s_id"] = null;
    $data=null;
}

print json_encode($data);
$conexion=null;

function insertarToken($idUsuario) {
    $val = true; // valor true
    $token = bin2hex(openssl_random_pseudo_bytes(16, $val)); // genera un token aleatorio
    $fecha = date("Y-m-d H:i"); // fecha actual
    $estado = "Activo"; // estado activo

    $objeto = new Conexion();
    $conexion = $objeto->Conectar();
    
    $consulta = "INSERT INTO usuarios_token (UsuarioId, Token, Estado, Fecha) VALUES ('$idUsuario', '$token', '$estado', '$fecha')";
    $resultado = $conexion->prepare($consulta);
    $resultado->execute();
  
    return $token;
  }

//usuarios de pruebaen la base de datos
//usuario:admin pass:12345
//usuario:demo pass:demo