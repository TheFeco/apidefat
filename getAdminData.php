<?php
include_once 'db/conexion.php';

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
        $consultaCiclos = "SELECT id, nombre FROM ciclos ORDER BY id DESC LIMIT 5";
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
if($_POST['METHOD']=='POST'){

    unset($_POST['METHOD']);
    $id_usuario    = $_POST["usuario"];
    $ciclo         = $_POST["ciclo"];
    $funcion       = $_POST["funcion"];
    $deporte       = isset($_POST["deporte"]) ? $_POST["deporte"] : 0;
    $rama          = isset($_POST["rama"]) ? $_POST["rama"] : 0;
    $categoria     = isset($_POST["categoria"]) ? $_POST["categoria"] : 0;
    $peso          = isset($_POST["peso"]) ? $_POST["peso"] : 0;
    $prueba        = isset($_POST["prueba"]) ? $_POST["prueba"] : 0;

    $query = "SELECT d.id_usuairo AS id_zona, d.escuela, d.cct, CASE WHEN turno = 1 THEN 'Matutino' WHEN turno = 2 THEN 'vespertino' END AS turno, d.id_ciclo, c.nombre AS ciclo, d.id_funcion, f.nombre AS funcion, dp.nombre AS deporte, d.id_deporte, d.id_rama, ramas.nombre AS rama, d.id_categoria, d.id_peso, d.id_prueba  FROM deportistas AS d INNER JOIN ciclos AS c ON (d.id_ciclo = c.id) INNER JOIN funciones AS f  ON (d.id_funcion = f.id) LEFT JOIN deportes AS dp ON (d.id_deporte = dp.id) LEFT JOIN ramas ON (d.id_rama = ramas.id) WHERE d.id_usuairo = $id_usuario AND c.id = $ciclo AND id_funcion = $funcion ";
    if($deporte > 0){
        $query.= "AND id_deporte = $deporte ";
    }
    if($rama > 0){
        $query.= "AND id_rama = $rama ";
    }
    if($categoria > 0){
        $query.= "AND id_categoria = $categoria ";
    }
    if($peso > 0){
        $query.= "AND id_peso = $peso ";
    }
    if($prueba > 0){
        $query.= "AND id_prueba = $prueba ";
    }
    $query.= "GROUP BY d.id_usuairo, d.escuela, d.cct, turno, d.id_ciclo, c.nombre, d.id_funcion, f.nombre, dp.nombre, d.id_deporte, d.id_rama, ramas.nombre, d.id_categoria, d.id_peso, d.id_prueba";
    // print_r($query);
    // die();
    $resultado = $conexion->prepare($query);
    $resultado->execute();
    $data=$resultado->fetchAll(PDO::FETCH_ASSOC);

    $d = array('data' => $data);
    header("HTTP/1.1 200 OK");
    return print json_encode($d);
    $conexion = NULL;
}
?>