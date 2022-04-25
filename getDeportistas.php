<?php
include_once 'db/conexion.php';

header('Access-Control-Allow-Origin: *');
$objeto = new Conexion();
$conexion = $objeto->Conectar();

header('Access-Control-Allow-Origin: *');
if($_SERVER['REQUEST_METHOD']=='GET'){
    if(isset($_GET['id_usuario'])){
        $id_usuario=$_GET['id_usuario'];
        $id_ciclo=$_GET['id_ciclo'];
        $id_funcion=$_GET['id_funcion'];
        $id_deporte = isset($_GET['id_deporte']) ? $_GET['id_deporte'] : 0;
        $id_rama = isset($_GET['id_rama']) ? $_GET['id_rama'] : 0;
        
        $consulta = "SELECT d.folio, d.nombre, d.apellidos, d.curp, d.foto,DATE_FORMAT(d.fh_nacimiento,'%d/%m/%Y') AS fh_nacimeinto, d.cct, d.escuela, d.zona, CASE WHEN turno = 1 THEN 'Matutino' WHEN turno = 2 THEN 'vespertino' END AS turno,c.nombre AS ciclo, m.nombre AS municipio, f.nombre AS funcion, dp.nombre AS deporte, r.nombre AS rama, cat.nombre AS categoria, peso.nombre AS peso, pruebas.nombre AS prueba, CONCAT_WS(':, ', cat.nombre,peso.nombre,pruebas.nombre) AS array_pruebas
        FROM deportistas AS d 
        INNER JOIN ciclos AS c ON (d.id_ciclo = c.id) 
        INNER JOIN funciones AS f  ON (d.id_funcion = f.id) 
        INNER JOIN municipios AS m ON( d.id_municipio = m.id) 
        LEFT JOIN deportes AS dp ON (d.id_deporte = dp.id) 
        LEFT JOIN ramas AS r ON( d.id_rama = r.id )
        LEFT JOIN categorias AS cat ON ( d.id_categoria = cat.id)
        LEFT JOIN peso ON (d.id_peso = peso.id)
        LEFT JOIN pruebas ON (d.id_prueba = pruebas.id)
        WHERE d.id_usuairo = '$id_usuario'
        AND d.id_ciclo = '$id_ciclo'
        AND d.id_funcion = '$id_funcion' 
        ";
        if($id_deporte != 0){
            $consulta .= "AND d.id_deporte = '$id_deporte' "; 
        }
        if($id_rama != 0){
            $consulta .= "AND d.id_rama = '$id_rama' "; 
        }

        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        if($resultado->rowCount() >= 1){
            $data = $resultado->fetchAll(PDO::FETCH_ASSOC);
        }else{
            $d = array('menssage' => 'No se encontro ningun dato.');
            header("HTTP/1.1 500 OK");
            return print json_encode($d);
            $conexion = NULL;
        }
        $d = array('registros' => $data);
        header("HTTP/1.1 200 OK");
        return print json_encode($d);
        $conexion = NULL;
    }else{
        $d = array('menssage' => 'Error al obtener las información');
        header("HTTP/1.1 500 OK");
        return print json_encode($d);
        $conexion = NULL;
        
    }
}
?>