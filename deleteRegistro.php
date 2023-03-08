<?php
include_once 'db/conexion.php';
$objeto = new Conexion();
$conexion = $objeto->Conectar();
$url = $objeto->baseUrl();
header('Access-Control-Allow-Origin: *');
if($_POST['METHOD']=='DELETE'){
    unset($_POST['METHOD']);
    if(isset($_GET['id'])){
        $id = $_GET['id'];
        try {
            //code...
            $query = "SELECT * FROM deportistas WHERE id = $id";
            $resultado = $conexion->prepare($query);
            $resultado->execute();
            if($resultado->rowCount() >= 1){
                $registro = $resultado->fetch(PDO::FETCH_ASSOC);
            }else{
                $errors = $resultado->errorInfo();
                echo $errors[2] . ", " . $errors[1] . " ," . $errors[0];
                $registro=null;
            }
            
            // die($url.$registro['foto']);
            if ( file_exists( $url.$registro['foto']) ) {
                unlink($url.$registro['foto']);
            }

            $queryDelete = "DELETE FROM deportistas WHERE id = $id";
            $resultado = $conexion->prepare($queryDelete);
            $resultado->execute();
            if($resultado->rowCount() >= 1){
                $registros = $resultado->fetchAll(PDO::FETCH_ASSOC);
            }else{
                $errors = $resultado->errorInfo();
                echo $errors[2] . ", " . $errors[1] . " ," . $errors[0];
                $registros=null;
            }

            header("HTTP/1.1 200 Ok");
            $d = array('status' => "success", "message" => "¡Se Elimino Exitosamente!");
            return print json_encode($d);
            $conexion = NULL;
        } catch (\Throwable $th) {
            // echo "Mensaje de Error: " . $th->getMessage();
            header("HTTP/1.1 418 error");
            $d = array('status' => "error", "message" => $th->getMessage());
            return print json_encode($d);
            $conexion = NULL;
        }
        
    }
}
?>