<?php
include_once 'db/conexion.php';

header('Access-Control-Allow-Origin: *');
$objeto = new Conexion();
$conexion = $objeto->Conectar();

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['id_deporte'])) {
        $id = $_GET['id_deporte'];
        $id_nivel = isset($_GET['id_nivel']) ? $_GET['id_nivel'] : null;
        $id_rama = $_GET['id_rama'];
        $id_usuario = isset($_GET['id_usuario']) ? $_GET['id_usuario'] : null;

        if ($id_usuario !== null) {
            $consultaNivel = "SELECT id_nivel FROM usuarios WHERE id = :id_usuario";
            $resultadoNivel = $conexion->prepare($consultaNivel);
            $resultadoNivel->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $resultadoNivel->execute();
            if ($resultadoNivel->rowCount() > 0) {
                $row = $resultadoNivel->fetch(PDO::FETCH_ASSOC);
                $id_nivel = $row['id_nivel'];
            } else {
                $d = array('message' => 'Error: No se encontró el nivel del usuario');
                header("HTTP/1.1 404 Not Found");
                return print json_encode($d);
            }
        }

        $consultaRamas = "SELECT p.id, p.nombre FROM deportes_pruebas AS dp 
                          INNER JOIN pruebas AS p ON (dp.id_prueba = p.id) 
                          WHERE dp.id_deporte = $id AND dp.id_nivel = $id_nivel AND id_rama = $id_rama
                          ORDER BY " . ($id_nivel == 1 ? 'p.id' : 'p.nombre') . ";";
        $resultadoRamas = $conexion->prepare($consultaRamas);
        $resultadoRamas->execute();
        if ($resultadoRamas->rowCount() >= 1) {
            $categorias = $resultadoRamas->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $errors = $resultadoRamas->errorInfo();
            echo $errors[2] . ", " . $errors[1] . " ," . $errors[0];
            $categorias = null;
        }

        $d = array('datos' => $categorias);
        header("HTTP/1.1 200 OK");
        return print json_encode($d);
    } else {
        $d = array('message' => 'Error al obtener las categorias');
        header("HTTP/1.1 500 Internal Server Error");
        return print json_encode($d);
    }
    $conexion = null;
}
?>
