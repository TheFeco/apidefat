<?php
include_once 'db/conexion.php';

header('Access-Control-Allow-Origin: *');
$objeto = new Conexion();
$conexion = $objeto->Conectar();
if($_SERVER['REQUEST_METHOD']=='GET'){
    if(isset($_GET['id_deporte'])){
        $id=$_GET['id_deporte'];
        $id_nivel=$_GET['id_nivel'];
        $id_rama=$_GET['id_rama'];
        
        //Traemos todas las ramas
        $consultaRamas = "SELECT p.id, p.nombre FROM deportes_pruebas AS dp 
        INNER JOIN pruebas AS p ON (dp.id_prueba = p.id) 
        WHERE dp.id_deporte = $id AND dp.id_nivel = $id_nivel AND id_rama = $id_rama";
        if($id_nivel == 1){
            $consultaRamas.=" ORDER BY p.id;";
        }else{
            $consultaRamas.=" ORDER BY p.nombre;";
        }
        
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