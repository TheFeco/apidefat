<?php
include_once 'db/conexion.php';

header('Access-Control-Allow-Origin: *');
$objeto = new Conexion();
$conexion = $objeto->Conectar();

header('Access-Control-Allow-Origin: *');


if($_SERVER['REQUEST_METHOD']=='GET'){
    if($_GET['rol'] == 1){
        $consultaUsuarios = "SELECT id, usuario as nombre FROM usuarios where id_rol = 2 ORDER BY id";
        $resultadoUsuarios = $conexion->prepare($consultaUsuarios);
        $resultadoUsuarios->execute();
        if($resultadoUsuarios->rowCount() >= 1){
            $usuarios = $resultadoUsuarios->fetchAll(PDO::FETCH_ASSOC);
        }else{
            $errors = $resultadoUsuarios->errorInfo();
            echo $errors[2] . ", " . $errors[1] . " ," . $errors[0];
            $usuarios=null;
        }
        
        $consultaFunciones = "SELECT id, nombre FROM funciones ORDER BY id";
        $resultadoFunciones = $conexion->prepare($consultaFunciones);
        $resultadoFunciones->execute();
        if($resultadoFunciones->rowCount() >= 1){
            $funciones = $resultadoFunciones->fetchAll(PDO::FETCH_ASSOC);
        }else{
            $errors = $resultadoFunciones->errorInfo();
            echo $errors[2] . ", " . $errors[1] . " ," . $errors[0];
            $funciones=null;
        }
        //Traemon el ulltimo ciclo escolar
        $consultaCiclos = "SELECT id, nombre FROM ciclos ORDER BY id DESC LIMIT 1";
        $resultado = $conexion->prepare($consultaCiclos);
        $resultado->execute();
        $ciclos=$resultado->fetchAll(PDO::FETCH_ASSOC);
        
        //Traemos todos los Deportes
        $consulta = "SELECT id, nombre FROM deportes ORDER BY id";
        $resultadoDeportes = $conexion->prepare($consulta);
        $resultadoDeportes->execute();
        if($resultadoDeportes->rowCount() >= 1){
            $deportes = $resultadoDeportes->fetchAll(PDO::FETCH_ASSOC);
        }else{
            $errors = $resultadoDeportes->errorInfo();
            echo $errors[2] . ", " . $errors[1] . " ," . $errors[0];
            $deportes=null;
        }
        //Traemos todas las ramas
        $consultaRamas = "SELECT id, nombre FROM ramas ORDER BY id";
        $resultadoRamas = $conexion->prepare($consultaRamas);
        $resultadoRamas->execute();
        if($resultadoRamas->rowCount() >= 1){
            $ramas = $resultadoRamas->fetchAll(PDO::FETCH_ASSOC);
        }else{
            $errors = $resultadoRamas->errorInfo();
            echo $errors[2] . ", " . $errors[1] . " ," . $errors[0];
            $ramas=null;
        }
        //Traemos todos los Municipios
        $consultaMunicipios = "SELECT id, nombre FROM municipios ORDER BY id";
        $resultadoMunicipios = $conexion->prepare($consultaMunicipios);
        $resultadoMunicipios->execute();
        if($resultadoMunicipios->rowCount() >= 1){
            $municipios = $resultadoMunicipios->fetchAll(PDO::FETCH_ASSOC);
        }else{
            $errors = $resultadoMunicipios->errorInfo();
            echo $errors[2] . ", " . $errors[1] . " ," . $errors[0];
            $municipios=null;
        }
        // 
        $d = array('usuarios' => $usuarios, 'funciones' => $funciones, 'ciclos' => $ciclos, 'deportes' => $deportes, 'ramas' => $ramas, 'municipios' => $municipios);
        header("HTTP/1.1 200 OK");
        return print json_encode($d);
        $conexion = NULL;
    }else{
        $d = array('menssage' => 'Error no es usuario administrador');
        header("HTTP/1.1 500 OK");
        return print json_encode($d);
        $conexion = NULL;
        
    }
}
?>