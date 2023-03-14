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
    $fh_nacimiento =  $_POST["fh_nacimiento"];
    $cct           = $_POST["cct"];
    $escuela       = $_POST["escuela"];
    $nivel         = $_POST["nivel"];
    $turno         = $_POST["turno"];
    $zona          = $_POST["zona"];
    $municipio     = $_POST["municipio"];
    $ciclo         = $_POST["ciclo"];
    $funcion       = $_POST["funcion"];
    $deporte       = isset($_POST["deporte"]) ? $_POST["deporte"] : 0;
    $rama          = isset($_POST["rama"]) ? $_POST["rama"] : 0;
    $categoria     = isset($_POST["categoria"]) ? $_POST["categoria"] : 0;
    $peso          = isset($_POST["peso"]) ? $_POST["peso"] : 0;
    $prueba        = isset($_POST["prueba"]) ? $_POST["prueba"] : 0;

    $id_usuario    = $_POST["usuario"];

    $actNacimiento = "";
    $curpPdf = "";
    $cerMedico = "";
    $cartaResponsiva = "";
    $ine = "";
    $constanciaAutorizacion = "";
    $constanciaServicio = "";
    $constanciaEstudio = "";

    if(isset($_FILES["actNacimiento"]) && $_FILES["actNacimiento"]["error"] == 0) {
        $actNacimiento = guardarArchivo($_FILES["actNacimiento"]);
    }

    if(isset($_FILES["curpPdf"]) && $_FILES["curpPdf"]["error"] == 0) {
        $curpPdf = guardarArchivo($_FILES["curpPdf"]);
    }

    if(isset($_FILES["cerMedico"]) && $_FILES["cerMedico"]["error"] == 0) {
        $cerMedico = guardarArchivo($_FILES["cerMedico"]);
    }

    if(isset($_FILES["cartaResponsiva"]) && $_FILES["cartaResponsiva"]["error"] == 0) {
        $cartaResponsiva = guardarArchivo($_FILES["cartaResponsiva"]);
    }

    if(isset($_FILES["ine"]) && $_FILES["ine"]["error"] == 0) {
        $ine = guardarArchivo($_FILES["ine"]);
    }

    if(isset($_FILES["constanciaAutorizacion"]) && $_FILES["constanciaAutorizacion"]["error"] == 0) {
        $constanciaAutorizacion = guardarArchivo($_FILES["constanciaAutorizacion"]);
    }

    if(isset($_FILES["constanciaServicio"]) && $_FILES["constanciaServicio"]["error"] == 0) {
        $constanciaServicio = guardarArchivo($_FILES["constanciaServicio"]);
    }

    if(isset($_FILES["constanciaEstudio"]) && $_FILES["constanciaEstudio"]["error"] == 0) {
        $constanciaEstudio = guardarArchivo($_FILES["constanciaEstudio"]);
    }

    switch ($funcion) {
        case '1':
            $query = "INSERT into deportistas (nombre, apellidos, fh_nacimiento, curp, cct, escuela, turno, id_municipio, zona, id_nivel, id_ciclo, id_funcion, id_deporte, id_rama, id_prueba, id_categoria, id_peso, id_usuairo, acta_nacimiento, curp_pdf, cert_medico, carta_responsiva, ine, constancia_autorizacion, constancia_servicio, constanciaEstudio) VALUES ('$nombre','$apellidos','$fh_nacimiento','$curp','$cct','$escuela', '$turno', '$municipio','$zona','$nivel', '$ciclo', '$funcion', '$deporte', '$rama', '$prueba', '$categoria', '$peso', '$id_usuario', '$actNacimiento', '$curpPdf', '$cerMedico', '$cartaResponsiva', '$ine', '$constanciaAutorizacion', '$constanciaServicio', '$constanciaEstudio')"; 
            break;
        case '2':
            $query = "INSERT into deportistas (nombre, apellidos, fh_nacimiento, curp, cct, escuela, turno, id_municipio, zona, id_nivel, id_ciclo, id_funcion, id_deporte, id_rama, id_usuairo, acta_nacimiento, curp_pdf, cert_medico, carta_responsiva, ine, constancia_autorizacion, constancia_servicio, constanciaEstudio) VALUES ('$nombre','$apellidos','$fh_nacimiento','$curp','$cct','$escuela', '$turno', '$municipio','$zona','$nivel', '$ciclo', '$funcion', '$deporte', '$rama', '$id_usuario', '$actNacimiento', '$curpPdf', '$cerMedico', '$cartaResponsiva', '$ine', '$constanciaAutorizacion', '$constanciaServicio', '$constanciaEstudio')";
            break;
        case '3':
            $query = "INSERT into deportistas (nombre, apellidos, fh_nacimiento, curp, cct, escuela, turno, id_municipio, zona, id_nivel, id_ciclo, id_funcion, id_usuairo, acta_nacimiento, curp_pdf, cert_medico, carta_responsiva, ine, constancia_autorizacion, constancia_servicio, constanciaEstudio) VALUES ('$nombre','$apellidos','$fh_nacimiento','$curp','$cct','$escuela', '$turno', '$municipio','$zona','$nivel', '$ciclo', '$funcion', '$id_usuario', '$actNacimiento', '$curpPdf', '$cerMedico', '$cartaResponsiva', '$ine', '$constanciaAutorizacion', '$constanciaServicio', '$constanciaEstudio')";
            break;
        case '4':
            $query = "INSERT into deportistas (nombre, apellidos, fh_nacimiento, curp, cct, escuela, turno, id_municipio, zona, id_nivel, id_ciclo, id_funcion, id_deporte, id_usuairo, acta_nacimiento, curp_pdf, cert_medico, carta_responsiva, ine, constancia_autorizacion, constancia_servicio, constanciaEstudio) VALUES ('$nombre','$apellidos','$fh_nacimiento','$curp','$cct','$escuela', '$turno', '$municipio','$zona','$nivel', '$ciclo', '$funcion', '$deporte', '$id_usuario', '$actNacimiento', '$curpPdf', '$cerMedico', '$cartaResponsiva', '$ine', '$constanciaAutorizacion', '$constanciaServicio', '$constanciaEstudio')";
            break;
        case '5':
            $query = "INSERT into deportistas (nombre, apellidos, fh_nacimiento, curp, cct, escuela, turno, id_municipio, zona, id_nivel, id_ciclo, id_funcion, id_usuairo, acta_nacimiento, curp_pdf, cert_medico, carta_responsiva, ine, constancia_autorizacion, constancia_servicio, constanciaEstudio) VALUES ('$nombre','$apellidos','$fh_nacimiento','$curp','$cct','$escuela', '$turno', '$municipio','$zona','$nivel', '$ciclo', '$funcion', '$id_usuario', '$actNacimiento', '$curpPdf', '$cerMedico', '$cartaResponsiva', '$ine', '$constanciaAutorizacion', '$constanciaServicio', '$constanciaEstudio')";
            break;
        case '6':
            $query = "INSERT into deportistas (nombre, apellidos, fh_nacimiento, curp, cct, escuela, turno, id_municipio, zona, id_nivel, id_ciclo, id_funcion, id_deporte, id_usuairo, acta_nacimiento, curp_pdf, cert_medico, carta_responsiva, ine, constancia_autorizacion, constancia_servicio, constanciaEstudio) VALUES ('$nombre','$apellidos','$fh_nacimiento','$curp','$cct','$escuela', '$turno', '$municipio','$zona','$nivel', '$ciclo', '$funcion', '$deporte', '$id_usuario', '$actNacimiento', '$curpPdf', '$cerMedico', '$cartaResponsiva', '$ine', '$constanciaAutorizacion', '$constanciaServicio', '$constanciaEstudio')";
            break;
        case '7':
            $query = "INSERT into deportistas (nombre, apellidos, fh_nacimiento, curp, cct, escuela, turno, id_municipio, zona, id_nivel, id_ciclo, id_funcion, id_deporte, id_usuairo, acta_nacimiento, curp_pdf, cert_medico, carta_responsiva, ine, constancia_autorizacion, constancia_servicio, constanciaEstudio) VALUES ('$nombre','$apellidos','$fh_nacimiento','$curp','$cct','$escuela', '$turno', '$municipio','$zona','$nivel', '$ciclo', '$funcion', '$deporte', '$id_usuario', '$actNacimiento', '$curpPdf', '$cerMedico', '$cartaResponsiva', '$ine', '$constanciaAutorizacion', '$constanciaServicio', '$constanciaEstudio')";
            break;
        case '8':
            $query = "INSERT into deportistas (nombre, apellidos, fh_nacimiento, curp, cct, escuela, turno, id_municipio, zona, id_nivel, id_ciclo, id_funcion, id_deporte, id_rama, id_usuairo, acta_nacimiento, curp_pdf, cert_medico, carta_responsiva, ine, constancia_autorizacion, constancia_servicio, constanciaEstudio) VALUES ('$nombre','$apellidos','$fh_nacimiento','$curp','$cct','$escuela', '$turno', '$municipio','$zona','$nivel', '$ciclo', '$funcion', '$deporte', '$rama', '$id_usuario', '$actNacimiento', '$curpPdf', '$cerMedico', '$cartaResponsiva', '$ine', '$constanciaAutorizacion', '$constanciaServicio', '$constanciaEstudio')";
            break;
        
    }

    if(isset($_FILES["foto"])){
        
        if (!file_exists('img/'.$id_usuario)) {

            mkdir('./img/'.$id_usuario, 0777, true);
        }

        try {
            $conexion->beginTransaction();
                $resultado = $conexion->prepare($query);
                $resultado->execute();
                //print_r($resultado->errorInfo());
                $LAST_ID = $conexion->lastInsertId();
                
            $conexion->commit();
        } catch (\Throwable $th) {
            echo "Mensaje de Error: " . $th->getMessage();
        }
       
        $query = ("SELECT nombre FROM `ciclos` WHERE id='$ciclo'");
        $resultado = $conexion->prepare($query);
        $resultado->execute();
        $ciclos = $resultado->fetch();
        $cicloNombre = $ciclos['nombre'];
        $folio = "025".$cicloNombre.$LAST_ID;
        $ext = explode('.', $_FILES['foto']['name']);
        $nombre_base=basename($_FILES["foto"]["name"]);
        $ext = substr($nombre_base, strrpos($nombre_base, '.')+1);
        $nombre_final = date("d-m-y")."-".$folio.'.'.$ext;
        $path = "img/".$id_usuario."/";
        $ruta = $path. $nombre_final;
        $subirFoto = move_uploaded_file($_FILES["foto"]["tmp_name"], $ruta);
        if($subirFoto){
            $query2 = "UPDATE deportistas SET folio='$folio', foto='$ruta' WHERE id = $LAST_ID";
            $resultado = $conexion->prepare($query2);
            $resultado->execute();
            // print_r($resultado->errorInfo());
            header("HTTP/1.1 200 Ok");
            $d = array('status' => "success", "message" => "¡Se guardo Exitosamente!");
            return print json_encode($d);
            $conexion = NULL; 
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

function guardarArchivo($archivo) {
    $nombreArchivo = uniqid()."_".$archivo["name"];
    $rutaArchivo = "files/".$nombreArchivo;
    move_uploaded_file($archivo["tmp_name"], $rutaArchivo);
    return $rutaArchivo;
}
