<?php
include_once 'db/conexion.php';

header('Access-Control-Allow-Origin: *');
$objeto = new Conexion();
$conexion = $objeto->Conectar();
if($_SERVER['REQUEST_METHOD']=='GET'){
    if(isset($_GET['id'])){
        $id=$_GET['id'];
        
        //Traemos todas las ramas
        $consultaRamas = "SELECT id, nombre FROM pruebas WHERE id_deporte = $id ORDER BY id";
        $resultadoRamas = $conexion->prepare($consultaRamas);
        $resultadoRamas->execute();
        if($resultadoRamas->rowCount() >= 1){
            $categorias = $resultadoRamas->fetchAll(PDO::FETCH_ASSOC);
        }else{
            $errors = $resultadoRamas->errorInfo();
            echo $errors[2] . ", " . $errors[1] . " ," . $errors[0];
            $categorias=null;
        }
        // 
        $d = array('datos' => $categorias);
        header("HTTP/1.1 200 OK");
        return print json_encode($d);
        $conexion = NULL;
    }else{
        $d = array('menssage' => 'Error al obtener las categorias');
        header("HTTP/1.1 500 OK");
        return print json_encode($d);
        $conexion = NULL;
        
    }
}
?>