<?php
include_once 'db/conexion.php';

header('Access-Control-Allow-Origin: *');
$objeto = new Conexion();
$conexion = $objeto->Conectar();

header('Access-Control-Allow-Origin: *');
if($_SERVER['REQUEST_METHOD']=='GET'){
    if(isset($_GET['id'])){
        $id=$_GET['id'];
        
        //Traemos todas las Pruebas que estan asignadas al peso
        $consulta = "SELECT p.id, p.nombre FROM peso_pruebas AS pp INNER JOIN pruebas AS p ON (pp.id_prueba = p.id) WHERE pp.id_peso = $id ORDER BY id";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        if($resultado->rowCount() >= 1){
            $peso = $resultado->fetchAll(PDO::FETCH_ASSOC);
        }else{
            $errors = $resultado->errorInfo();
            echo $errors[2] . ", " . $errors[1] . " ," . $errors[0];
            $peso=null;
        }
        // 
        $d = array('datos' => $peso);
        header("HTTP/1.1 200 OK");
        return print json_encode($d);
        $conexion = NULL;
    }else{
        $d = array('menssage' => 'Error al obtener las información');
        header("HTTP/1.1 500 OK");
        return print json_encode($d);
        $conexion = NULL;
        
    }
}
?>