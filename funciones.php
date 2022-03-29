<?php
include 'db/db.php';

header('Access-Control-Allow-Origin: *');
if($_SERVER['REQUEST_METHOD']=='GET'){
    if(isset($_GET['id'])){
        $id=$_GET['id'];
        //$consulta = "SELECT informes.*, ciclos.nombre AS ciclo , periodos.nombre AS periodo FROM informes JOIN ciclos ON( informes.id_ciclo = ciclos.id ) JOIN periodos ON( informes.id_periodo = periodos.id ) WHERE id_usuario='$id' ";
       
    }else{
       // $consulta = "SELECT informes.*, e.cct , ciclos.nombre AS ciclo , periodos.nombre AS periodo FROM informes JOIN ciclos ON( informes.id_ciclo = ciclos.id ) JOIN periodos ON( informes.id_periodo = periodos.id ) JOIN escuelas AS e ON( informes.id_escuela = e.id ) ORDER BY e.Nombre,informes.id_ciclo,informes.id_periodo DESC";
        
    }
    //Traemon el ulltimo ciclo escolar
    $consultaCiclos = "SELECT id, nombre FROM ciclos ORDER BY id DESC LIMIT 1";
    $resultado=metodoGet($consultaCiclos);
    $ciclos=$resultado->fetchAll(PDO::FETCH_ASSOC);
    
    //Traemos todos los Deportes
    $consultaDeportes = "SELECT id, nombre FROM deportes ORDER BY id";
    $resultadoDeportes=metodoGet($consultaDeportes);
    if($resultadoDeportes->rowCount() >= 1){
        $deportes = $resultadoDeportes->fetchAll(PDO::FETCH_ASSOC);
    }else{
        $deportes=null;
    }
    // 
    $d = array('ciclos' => $ciclos, 'periodos' => $deportes);
    //var_dump($d);
    header("HTTP/1.1 200 OK");
    return print json_encode($d);
    $conexion = NULL;
}
?>