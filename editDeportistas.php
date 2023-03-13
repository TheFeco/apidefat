<?php
include_once 'db/conexion.php';
$objeto = new Conexion();
$conexion = $objeto->Conectar();
header('Access-Control-Allow-Origin: *');

if($_POST['METHOD']=='POST'){
    unset($_POST['METHOD']);
    $id_deportista = $_POST["id"];
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


    if(isset($_FILES["actNacimiento"]) && $_FILES["actNacimiento"]["error"] == 0) {
        $actNacimiento = guardarArchivo($_FILES["actNacimiento"]);
        $updateQuery = "UPDATE deportistas SET acta_nacimiento = '$actNacimiento' WHERE id = '$id_deportista'";
        $resultado = $conexion->prepare($updateQuery);
        $resultado->execute();
    }

    if(isset($_FILES["curpPdf"]) && $_FILES["curpPdf"]["error"] == 0) {
        $curpPdf = guardarArchivo($_FILES["curpPdf"]);
        $updateQuery = "UPDATE deportistas SET curp_pdf = '$curpPdf' WHERE id = '$id_deportista'";
        $resultado = $conexion->prepare($updateQuery);
        $resultado->execute();
    }

    if(isset($_FILES["cerMedico"]) && $_FILES["cerMedico"]["error"] == 0) {
        $cerMedico = guardarArchivo($_FILES["cerMedico"]);
    }

    if(isset($_FILES["cartaResponsiva"]) && $_FILES["cartaResponsiva"]["error"] == 0) {
        $cartaResponsiva = guardarArchivo($_FILES["cartaResponsiva"]);
    }

    if(isset($_FILES["ine"]) && $_FILES["ine"]["error"] == 0) {
        $ine = guardarArchivo($_FILES["ine"]);
        $updateQuery = "UPDATE deportistas SET ine = '$ine' WHERE id = '$id'";
    }

    if(isset($_FILES["constanciaAutorizacion"]) && $_FILES["constanciaAutorizacion"]["error"] == 0) {
        $constanciaAutorizacion = guardarArchivo($_FILES["constanciaAutorizacion"]);
    }

    if(isset($_FILES["constanciaServicio"]) && $_FILES["constanciaServicio"]["error"] == 0) {
        $constanciaServicio = guardarArchivo($_FILES["constanciaServicio"]);
    }

    

    $query_folio = "SELECT folio FROM deportistas WHERE id = $id;";
    $resultado_folio = $conexion->prepare($query_folio);
    $resultado_folio->execute();
    $folio = $resultado_folio->fetchColumn();

    if(isset($_FILES["foto"]) && $_FILES["foto"]["error"] == 0) {
        $foto = guardarFoto($id_deportista,$_FILES["foto"],$folio);
    }

    $query = "UPDATE deportistas SET 
                    nombre='$nombre', 
                    apellidos='$apellidos', 
                    fh_nacimiento='$fh_nacimiento', 
                    cct='$cct', 
                    escuela='$escuela', 
                    turno='$turno', 
                    id_municipio='$municipio',
                    zona='$zona',
                    id_nivel='$nivel', 
                    id_ciclo='$ciclo', 
                    id_funcion='$funcion',
                    id_deporte='$deporte', 
                    id_rama='$rama', 
                    id_prueba='$prueba', 
                    id_categoria='$categoria', 
                    id_peso='$peso'
                    WHERE id = $id_deportista";

    try {
        $conexion->beginTransaction();
            $resultado = $conexion->prepare($query);
            $resultado->execute();
            
            
        $conexion->commit();
    } catch (\Throwable $th) {
        echo "Mensaje de Error: " . $th->getMessage();
    }

    //print_r($resultado->errorInfo());
    header("HTTP/1.1 200 Ok");
    $d = array('status' => "success", "message" => "¡Se guardo Exitosamente!");
    return print json_encode($d);
    $conexion = NULL;

}

function guardarArchivo($archivo) {
    $nombreArchivo = uniqid()."_".$archivo["name"];
    $rutaArchivo = "files/".$nombreArchivo;
    move_uploaded_file($archivo["tmp_name"], $rutaArchivo);
    return $rutaArchivo;
}

function guardarFoto($id_usuario, $archivo, $folio){

    if (!file_exists('img/'.$id_usuario)) {

        mkdir('./img/'.$id_usuario, 0777, true);
    }

    $ext = explode('.', $archivo['name']);
    $nombre_base = basename($archivo['name']);
    $ext = substr($nombre_base, strrpos($nombre_base, '.') + 1);
    $nombre_final = date("d-m-y") . "-" . $folio . '.' . $ext;
    $path = "img/" . $id_usuario . "/";
    $rutaFoto = $path . $nombre_final;
    move_uploaded_file($archivo["tmp_name"], $rutaFoto);
    return $rutaFoto;
}
