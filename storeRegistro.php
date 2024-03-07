<?php
include_once 'db/conexion.php';
$objeto = new Conexion();
$conexion = $objeto->Conectar();
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

if ($_POST['METHOD'] == 'POST') {
    //echo json_encode($_POST);
    //exit;
    //die();
    unset($_POST['METHOD']);
    $campos = ['curp', 'nombre', 'apellidos', 'fh_nacimiento', 'cct', 'escuela', 'nivel', 'turno', 'zona', 'municipio', 'ciclo', 'funcion', 'usuario'];
    $valores = [];
    foreach ($campos as $campo) {
        $valores[$campo] = $_POST[$campo] ?? '';
    }
    $valores['deporte'] = $_POST['deporte'] ?? 0;
    $valores['rama'] = $_POST['rama'] ?? 0;
    $valores['categoria'] = $_POST['categoria'] ?? 0;
    $valores['peso'] = $_POST['peso'] ?? 0;
    $valores['prueba'] = $_POST['prueba'] ?? 0;
    $valores['prueba2'] = $_POST['prueba2'] ?? 0;

    $archivos = ['actNacimiento', 'curpPdf', 'cerMedico', 'cartaResponsiva', 'ine', 'constanciaAutorizacion', 'constanciaServicio', 'constanciaEstudio'];
    foreach ($archivos as $archivo) {
        if (isset($_FILES[$archivo]) && $_FILES[$archivo]['error'] == 0) {
            $valores[$archivo] = guardarArchivo($_FILES[$archivo]);
        } else {
            $valores[$archivo] = '';
        }
    }

    $query = "INSERT INTO deportistas (curp, nombre, apellidos, fh_nacimiento, cct, escuela, id_nivel, turno, zona, id_municipio, id_ciclo, id_funcion, id_deporte, id_rama, id_categoria, id_peso, id_prueba, id_prueba2, id_usuairo, acta_nacimiento, curp_pdf, cert_medico, carta_responsiva, ine, constancia_autorizacion, constancia_servicio, constanciaEstudio) VALUES (:curp, :nombre, :apellidos, :fh_nacimiento, :cct, :escuela, :nivel, :turno, :zona, :municipio, :ciclo, :funcion, :deporte, :rama, :categoria, :peso, :prueba, :prueba2, :usuario, :actNacimiento, :curpPdf, :cerMedico, :cartaResponsiva, :ine, :constanciaAutorizacion, :constanciaServicio, :constanciaEstudio)";

    $stmt = $conexion->prepare($query);
    $ejecucionExitosa = $stmt->execute($valores);

    if (!$ejecucionExitosa)  {
        // Si hubo un problema con la inserción
        $errorInfo = $stmt->errorInfo();
        $d = ['status' => 'error', 'message' => 'Error al guardar los datos: ' . $errorInfo[2]];
        header("HTTP/1.1 500 Internal Server Error");
        return print json_encode($d);
        $conexion = null;
    }

    // Manejo de la foto y actualización del folio
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $id_usuario = $valores['usuario'];
        $ciclo = $valores['ciclo'];
        $LAST_ID = $conexion->lastInsertId();
        $cicloNombre = obtenerNombreCiclo($conexion, $ciclo);
        $folio = "025" . $cicloNombre . $LAST_ID;
        $ruta = guardarFoto($_FILES['foto'], $id_usuario, $folio);

        if ($ruta) {
            $query2 = "UPDATE deportistas SET folio = :folio, foto = :foto WHERE id = :id";
            $stmt2 = $conexion->prepare($query2);
            $ejecucionExitosa2 = $stmt2->execute(['folio' => $folio, 'foto' => $ruta, 'id' => $LAST_ID]);

            if ($ejecucionExitosa2) {
                // La actualización fue exitosa
                $d = ['status' => 'success', 'message' => '¡Se guardó exitosamente!'];
                header("HTTP/1.1 200 OK");
            } else {
                // Hubo un problema con la actualización
                $errorInfo2 = $stmt2->errorInfo();
                $d = ['status' => 'error', 'message' => 'Error al actualizar el folio: ' . $errorInfo2[2]];
                header("HTTP/1.1 500 Internal Server Error");
                return print json_encode($d);
            }
        } else {
            $d = ['message' => "Error al subir la foto"];
            header("HTTP/1.1 500 Internal Server Error");
        }
    } else {
        $d = ['message' => "No se cargó la foto"];
        header("HTTP/1.1 400 Bad Request");
    }
    return print json_encode($d);
    $conexion = null;
}

function guardarArchivo($archivo)
{
    $nombreArchivo = uniqid() . "_" . $archivo['name'];
    $rutaArchivo = "files/" . $nombreArchivo;
    move_uploaded_file($archivo['tmp_name'], $rutaArchivo);
    return $rutaArchivo;
}

function obtenerNombreCiclo($conexion, $ciclo)
{
    $stmt = $conexion->prepare("SELECT nombre FROM ciclos WHERE id = :id");
    $stmt->execute(['id' => $ciclo]);
    $resultado = $stmt->fetch();
    return $resultado['nombre'] ?? '';
}

function guardarFoto($archivo, $id_usuario, $folio)
{
    $directorio = "img/" . $id_usuario;
    if (!file_exists($directorio)) {
        mkdir($directorio, 0777, true);
    }
    $extension = pathinfo($archivo['name'], PATHINFO_EXTENSION);
    $nombre_final = date("d-m-y") . "-" . $folio . '.' . $extension;
    $ruta = $directorio . '/' . $nombre_final;
    $subirFoto = move_uploaded_file($archivo['tmp_name'], $ruta);
    return $subirFoto ? $ruta : false;
}
