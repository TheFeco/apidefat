<?php
include_once 'db/conexion.php';
$objeto = new Conexion();
$conexion = $objeto->Conectar();
header('Access-Control-Allow-Origin: *');

// Función para subir el archivo y devolver el nombre del archivo
function subirArchivo($inputName, $id_deportista, $isImage = false) {
    if (isset($_FILES[$inputName])) {
        $file = $_FILES[$inputName];
        $nombreArchivo = uniqid() . "_" . $id_deportista . "_" . $file['name'];

        if ($isImage) {
            if (!file_exists('img/' . $id_deportista)) {
                mkdir('./img/' . $id_deportista, 0777, true);
            }
            $filepath = "img/" . $id_deportista . "/" . $nombreArchivo;
        } else {
            $filepath = "files/" . $nombreArchivo;
        }

        move_uploaded_file($file['tmp_name'], $filepath);
        return $nombreArchivo;
    } else {
        return null;
    }
}

if ($_POST['METHOD'] == 'POST') {

    $id_deportista = $_POST['idDeportista']; // Obtener el ID del deportista

    // Subir los archivos y almacenar sus nombres en las variables correspondientes
    $foto = subirArchivo("foto", $id_deportista, true);
    $curp_pdf = subirArchivo("acta_curp", $id_deportista);
    $cert_medico = subirArchivo("certificado_medico", $id_deportista);
    $carta_responsiva = subirArchivo("carta_responsiva", $id_deportista);
    $ine = subirArchivo("ine", $id_deportista);
    $constancia_autorizacion = subirArchivo("constancia_acreditacion", $id_deportista);
    $constancia_servicio = subirArchivo("cocnstancia_servicio", $id_deportista);
    $constanciaEstudio = subirArchivo("constancia_estudio", $id_deportista);

    $query = "UPDATE deportistas SET ";

    if ($foto) {
        $query .= "foto='$foto', ";
    }
    if ($curp_pdf) {
        $query .= "curp_pdf='$curp_pdf', ";
    }
    if ($cert_medico) {
        $query .= "cert_medico='$cert_medico', ";
    }
    if ($carta_responsiva) {
        $query .= "carta_responsiva='$carta_responsiva', ";
    }
    if ($ine) {
        $query .= "ine='$ine', ";
    }
    if ($constancia_autorizacion) {
        $query .= "constancia_autorizacion='$constancia_autorizacion', ";
    }
    if ($constancia_servicio) {
        $query .= "constancia_servicio='$constancia_servicio', ";
    }
    if ($constanciaEstudio) {
        $query .= "constanciaEstudio='$constanciaEstudio', ";
    }

    // Remover la última coma y agregar la cláusula WHERE
    $query = rtrim($query, ", ") . " WHERE id=$id_deportista";

    $statement = $conexion->prepare($query);
    $statement->execute();

    header("HTTP/1.1 200 Ok");

    $data = array("status" => "success", "message" => "Archivos actualizados correctamente.");
    echo json_encode($data);
}

$conexion = NULL;
