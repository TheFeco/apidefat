<?php
include_once 'db/conexion.php';

header('Access-Control-Allow-Origin: *');
$objeto = new Conexion();
$conexion = $objeto->Conectar();

header('Access-Control-Allow-Origin: *');
if($_SERVER['REQUEST_METHOD']=='GET'){

    if(isset($_GET['id_deporte'])){
        $id_deporte = $_GET['id_deporte'];
        $id_usuario = $_GET['id_usuario'];
        $id_rama = $_GET['id_rama'];
        $id_nivel;

        $consultaCiclo = "SELECT id_nivel FROM usuarios WHERE id =".$id_usuario;
        $resultado = $conexion->prepare($consultaCiclo);
        $resultado->execute();
        $rows=$resultado->fetchAll(PDO::FETCH_ASSOC);
        foreach( $rows as $row ) {
                $id_nivel = $row["id_nivel"];
        }
        
        //Traemos todas las ramas
        $consulta = "SELECT p.id, p.nombre FROM deportes_peso AS pp INNER JOIN peso AS p ON (pp.id_peso = p.id) WHERE pp.id_deporte = $id_deporte AND pp.id_nivel = $id_nivel AND pp.id_rama = $id_rama ORDER BY id";
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