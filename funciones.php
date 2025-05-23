<?php
include_once 'db/conexion.php';

header('Access-Control-Allow-Origin: *');
$objeto = new Conexion();
$conexion = $objeto->Conectar();

header('Access-Control-Allow-Origin: *');
if($_SERVER['REQUEST_METHOD']=='GET'){
    if(isset($_GET['id'])){
        $id = $_GET['id'];
        $consulta = "
        SELECT dp.escuela, dp.cct, dp.id_ciclo, c.nombre AS ciclo, dp.id_funcion, f.nombre AS funcion, dp.id_deporte, d.nombre AS deporte, dp.id_rama, r.nombre AS rama
        FROM deportistas AS dp 
        INNER JOIN ciclos AS c ON (dp.id_ciclo = c.id) 
        INNER JOIN funciones AS f ON ( dp.id_funcion = f.id)
        LEFT JOIN deportes AS d ON (dp.id_deporte = d.id)
        LEFT JOIN ramas  AS r ON (dp.id_rama = r.id)
        WHERE dp.id_usuairo = '$id' 
        GROUP BY dp.escuela, dp.cct, dp.id_ciclo, c.nombre, dp.id_funcion, f.nombre, dp.id_deporte, d.nombre, dp.id_rama, r.nombre
        ORDER BY dp.id_ciclo, c.nombre, f.nombre DESC
        ";
        
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        if($resultado->rowCount() >= 1){
            $registros = $resultado->fetchAll(PDO::FETCH_ASSOC);
        }else{
            $errors = $resultado->errorInfo();
            // echo $errors[2] . ", " . $errors[1] . " ," . $errors[0];
            $registros=[];
        }
       
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
    $consultaCiclos = "SELECT id, nombre FROM ciclos ORDER BY id DESC LIMIT 5";
    $resultado = $conexion->prepare($consultaCiclos);
    $resultado->execute();
    $ciclos=$resultado->fetchAll(PDO::FETCH_ASSOC);
    
    //Traemos todos los Deportes
    $consulta = "SELECT id, nombre FROM deportes WHERE is_active = 1 ORDER BY id";
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
    $consultaRamas = "SELECT id, nombre FROM ramas WHERE is_active = 1 ORDER BY id";
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
    $d = array('registros' => $registros,'funciones' => $funciones, 'ciclos' => $ciclos, 'deportes' => $deportes, 'ramas' => $ramas, 'municipios' => $municipios);
    header("HTTP/1.1 200 OK");
    return print json_encode($d);
    $conexion = NULL;
}

?>