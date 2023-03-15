<?php
include_once 'db/conexion.php';
$objeto = new Conexion();
$conexion = $objeto->Conectar();
header('Access-Control-Allow-Origin: *');

if($_POST['METHOD']=='POST'){
    $id_deportista = $_POST['id_deportista'];

    $setValues = array();

    if (isset($_FILES["foto"]) && $_FILES["foto"]["error"] == 0) {
        $foto = $_FILES["foto"];
        $nombreFoto = uniqid() . "_" . $id_deportista . "_" . $foto["name"];
        $rutaFoto = "img/" . $nombreFoto;
        move_uploaded_file($foto["tmp_name"], $rutaFoto);
        $setValues[] = "foto = '$rutaFoto'";
    }

    if (isset($_FILES["acta_curp"]) && $_FILES["acta_curp"]["error"] == 0) {
        $acta_curp = $_FILES["acta_curp"];
        $nombreActaCurp = uniqid() . "_" . $id_deportista . "_" . $acta_curp["name"];
        $rutaActaCurp = "files/" . $nombreActaCurp;
        move_uploaded_file($acta_curp["tmp_name"], $rutaActaCurp);
        $setValues[] = "curp_pdf = '$rutaActaCurp'";
    }

    if (isset($_FILES["certificado_medico"]) && $_FILES["certificado_medico"]["error"] == 0) {
        $certificado_medico = $_FILES["certificado_medico"];
        $nombreCm = uniqid() . "_" . $id_deportista . "_" . $certificado_medico["name"];
        $rutaCM = "files/" . $nombreCm;
        move_uploaded_file($certificado_medico["tmp_name"], $rutaCM);
        $setValues[] = "cert_medico = '$rutaCM'";
    }

    if (isset($_FILES["carta_responsiva"]) && $_FILES["carta_responsiva"]["error"] == 0) {
        $carta_responsiva = $_FILES["carta_responsiva"];
        $nombreCR = uniqid() . "_" . $id_deportista . "_" . $carta_responsiva["name"];
        $rutaCR = "files/" . $nombreCR;
        move_uploaded_file($carta_responsiva["tmp_name"], $rutaCR);
        $setValues[] = "carta_responsiva = '$rutaCR'";
    }

    if (isset($_FILES["ine"]) && $_FILES["ine"]["error"] == 0) {
        $ine = $_FILES["ine"];
        $nombreIne = uniqid() . "_" . $id_deportista . "_" . $ine["name"];
        $rutaActaCurp = "files/" . $nombreIne;
        move_uploaded_file($ine["tmp_name"], $rutaActaCurp);
        $setValues[] = "ine = '$rutaActaCurp'";
    }

    if (isset($_FILES["constancia_acreditacion"]) && $_FILES["constancia_acreditacion"]["error"] == 0) {
        $constancia_acreditacion = $_FILES["constancia_acreditacion"];
        $nombreCA = uniqid() . "_" . $id_deportista . "_" . $constancia_acreditacion["name"];
        $rutaCA = "files/" . $nombreCA;
        move_uploaded_file($constancia_acreditacion["tmp_name"], $rutaCA);
        $setValues[] = "constancia_autorizacion = '$rutaCA'";
    }

    if (isset($_FILES["cocnstancia_servicio"]) && $_FILES["cocnstancia_servicio"]["error"] == 0) {
        $cocnstancia_servicio = $_FILES["cocnstancia_servicio"];
        $nombreACS = uniqid() . "_" . $id_deportista . "_" . $cocnstancia_servicio["name"];
        $rutaCS = "files/" . $nombreACS;
        move_uploaded_file($cocnstancia_servicio["tmp_name"], $rutaCS);
        $setValues[] = "constancia_servicio = '$rutaCS'";
    }

    if (isset($_FILES["constancia_estudio"]) && $_FILES["constancia_estudio"]["error"] == 0) {
        $constancia_estudio = $_FILES["constancia_estudio"];
        $nombreCE = uniqid() . "_" . $id_deportista . "_" . $constancia_estudio["name"];
        $rutaCE = "files/" . $nombreCE;
        move_uploaded_file($constancia_estudio["tmp_name"], $rutaCE);
        $setValues[] = "constanciaEstudio = '$rutaCE'";
    }

    // Continuar con los otros archivos...

    $setValuesStr = implode(", ", $setValues);

    if (!empty($setValues)) {
        $consulta = "UPDATE deportistas SET $setValuesStr WHERE id = $id_deportista";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
    }
}
?>
