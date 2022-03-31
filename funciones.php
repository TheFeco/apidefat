<?php
include_once 'db/conexion.php';
//include 'db/db.php';

header('Access-Control-Allow-Origin: *');
$objeto = new Conexion();
$conexion = $objeto->Conectar();

header('Access-Control-Allow-Origin: *');
if($_SERVER['REQUEST_METHOD']=='GET'){
    if(isset($_GET['id'])){
        $id=$_GET['id'];
        //$consulta = "SELECT informes.*, ciclos.nombre AS ciclo , periodos.nombre AS periodo FROM informes JOIN ciclos ON( informes.id_ciclo = ciclos.id ) JOIN periodos ON( informes.id_periodo = periodos.id ) WHERE id_usuario='$id' ";
       
    }else{
       // $consulta = "SELECT informes.*, e.cct , ciclos.nombre AS ciclo , periodos.nombre AS periodo FROM informes JOIN ciclos ON( informes.id_ciclo = ciclos.id ) JOIN periodos ON( informes.id_periodo = periodos.id ) JOIN escuelas AS e ON( informes.id_escuela = e.id ) ORDER BY e.Nombre,informes.id_ciclo,informes.id_periodo DESC";
        
    }
    //Traemos todas las funciones
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
    $d = array('funciones' => $funciones, 'ciclos' => $ciclos, 'deportes' => $deportes, 'ramas' => $ramas, 'municipios' => $municipios);
    header("HTTP/1.1 200 OK");
    return print json_encode($d);
    $conexion = NULL;
}
?>