<?php
include_once 'db/conexion.php';
$objeto = new Conexion();
$conexion = $objeto->Conectar();
header('Access-Control-Allow-Origin: *');
if($_POST['METHOD']=='POST'){
    unset($_POST['METHOD']);
    $curp          = $_POST["curp"];
    $nombre        = $_POST["nombre"];
    $apellidos     = $_POST["apellidos"];
    $fh_nacimineto =  $_POST["fh_nacimiento"];
    $cct           = $_POST["cct"];
    $escuela       = $_POST["escuela"];
    $nivel         = $_POST["nivel"];
    $turno         = $_POST["turno"];
    $zona          = $_POST["zona"];
    $municipio     = $_POST["municipio"];
    $ciclo         = $_POST["ciclo"];
    $funcion       = $_POST["funcion"];
    $deporte       = isset($_POST["deporte"]) ? $_POST["deporte"] : "";
    $rama          = isset($_POST["rama"]) ? $_POST["rama"] : "";
    $categoria     = isset($_POST["categoria"]) ? $_POST["categoria"] : "";
    $peso          = isset($_POST["peso"]) ? $_POST["peso"] : "";
    $prueba        = isset($_POST["prueba"]) ? $_POST["prueba"] : "";
   
    $id_usuario    = $_POST["usuario"];

    switch ($funcion) {
        case '1':
            $query = "INSERT into deportistas (nombre, apellidos, fh_nacimiento, curp, cct, escuela, turno, id_municipio, zona, id_nivel, id_ciclo, id_funcion,id_deporte,id_rama,id_prueba,id_categoria,id_peso,id_usuairo) VALUES ('$nombre','$apellidos','$fh_nacimiento','$curp','$cct','$escuela', '$turno', '$municipio','$zona','$nivel', '$ciclo', '$funcion', '$deporte', '$rama', '$prueba', '$categoria', '$peso', '$id_usuario')"; 
            break;
        case '2':
            $query = "INSERT into deportistas (nombre, apellidos, fh_nacimiento, curp, cct, escuela, turno, id_municipio, zona, id_nivel, id_ciclo, id_funcion, id_deporte, id_rama, id_usuairo) VALUES ('$nombre','$apellidos','$fh_nacimiento','$curp','$cct','$escuela', '$turno', '$municipio','$zona','$nivel', '$ciclo', '$funcion', '$deporte', '$rama', '$id_usuario')";
            break;
        case '3':
            $query = "INSERT into deportistas (nombre, apellidos, fh_nacimiento, curp, cct, escuela, turno, id_municipio, zona, id_nivel, id_ciclo, id_funcion, id_usuairo) VALUES ('$nombre','$apellidos','$fh_nacimiento','$curp','$cct','$escuela', '$turno', '$municipio','$zona','$nivel', '$ciclo', '$funcion', '$id_usuario')";
            break;
        case '4':
            $query = "INSERT into deportistas (nombre, apellidos, fh_nacimiento, curp, cct, escuela, turno, id_municipio, zona, id_nivel, id_ciclo, id_funcion, id_deporte, id_rama, id_usuairo) VALUES ('$nombre','$apellidos','$fh_nacimiento','$curp','$cct','$escuela', '$turno', '$municipio','$zona','$nivel', '$ciclo', '$funcion', '$deporte', '$id_usuario')";
            break;
        case '5':
            $query = "INSERT into deportistas (nombre, apellidos, fh_nacimiento, curp, cct, escuela, turno, id_municipio, zona, id_nivel, id_ciclo, id_funcion, id_usuairo) VALUES ('$nombre','$apellidos','$fh_nacimiento','$curp','$cct','$escuela', '$turno', '$municipio','$zona','$nivel', '$ciclo', '$funcion', '$id_usuario')";
            break;
        case '6':
            $query = "INSERT into deportistas (nombre, apellidos, fh_nacimiento, curp, cct, escuela, turno, id_municipio, zona, id_nivel, id_ciclo, id_funcion, id_deporte, id_rama, id_usuairo) VALUES ('$nombre','$apellidos','$fh_nacimiento','$curp','$cct','$escuela', '$turno', '$municipio','$zona','$nivel', '$ciclo', '$funcion', '$deporte', '$id_usuario')";
            break;
    }

    if($_FILES["foto"]){
        if (!file_exists('img/'.$id_usuario)) {
            mkdir('img/'.$id_usuario, 0777, true);
        }
        $resultado = $conexion->prepare($query);
        $resultado->execute();
        $LAST_ID = $conexion->lastInsertId();
        $folio = "025".$ciclo.$LAST_ID;
        $nombre_base=basename($_FILES["foto"]["name"]);
        $nombre_final = date("d-m-y")."-".$folio."-".$nombre_base;
        $ruta = "img/".$id_usuario."/". $nombre_final;
        $subirFoto = move_uploaded_file($_FILES["foto"]["tmp_name"], $ruta);
        if($subirFoto){
            $query2 = "UPDATE deportistas SET folio = $folio, foto = $ruta WHERE id = $LAST_ID";
            $resultado = $conexion->prepare($query2);
            $resultado->execute();
            $d = array('status' => "success", "message" => "¡Se guardo Exitosamente!");
        }else{
            header("HTTP/1.1 500 OK");
            $d = array('menssage' => "Error al subir la foto");
            return print json_encode($d);
            $conexion = NULL; 
        }
    }else{
        header("HTTP/1.1 500 Ok");
        $d = array('menssage' => "No se cargó la foto");
        return print json_encode($d);
        $conexion = NULL;
    }
}
?>