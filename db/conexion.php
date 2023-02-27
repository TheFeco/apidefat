<?php 
class Conexion{
    private $server;
    private $user;
    private $password;
    private $database;
    private $port;

    public function Conectar() {        
        
        $listadatos = $this->datosConexion();
        foreach ($listadatos as $key => $value) {
            $this->server = $value['server'];
            $this->user = $value['user'];
            $this->password = $value['password'];
            $this->database = $value['database'];
            $this->port = $value['port'];
        }				        
        $opciones = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');			
        try{
            $conexion = new PDO("mysql:host=". $this->server."; dbname=".$this->database, $this->user, $this->password, $opciones);			
            return $conexion;
        }catch (Exception $e){
            die("El error de Conexión es: ". $e->getMessage());
        }
    }

    private function datosConexion(){
        $direccion = dirname(__FILE__);
        $jsondata = file_get_contents($direccion . "/". "config");
        return json_decode($jsondata, true);
    }

    public function obtenerDatos($sqlstr){
        $objeto = new Conexion();
        $conn = $objeto->Conectar();
        $stmt = $conn->prepare($sqlstr);
        $stmt->execute();
        print_r($stmt->errorInfo());
        $resultArray = $stmt->fetchAll(PDO::FETCH_ASSOC);
        print_r($resultArray);
        return $this->convertirUTF8($resultArray);

    }

    public function nonQueryId($sqlstr) {
        $objeto = new Conexion();
        $conn = $objeto->Conectar();
        $stmt = $conn->prepare($sqlstr);
        $stmt->execute();
        // print_r($stmt->errorInfo());
        $filas = $stmt->rowCount();
        if ($filas >= 1) {
            return $conn->lastInsertId();
        } else {
            return 0;
        }
    }

    public function nonQuery($sqlstr) {
        $objeto = new Conexion();
        $conn = $objeto->Conectar();
        $stmt = $conn->prepare($sqlstr);
        $stmt->execute();
        // print_r($stmt->errorInfo());
        return $stmt->rowCount();
    }
    
    

    private function convertirUTF8($array){
        array_walk_recursive($array,function(&$item,$key){
            if(!mb_detect_encoding($item,'utf-8',true)){
                $item = utf8_encode($item);
            }
        });
        return $array;
    }

    //encriptar

    protected function encriptar($string){
        return md5($string);
    }
}


function metodoGet($query){
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();
    try{
        $resultado = $conexion->prepare($query);
        $resultado->execute();
        $resultado->fetchAll(PDO::FETCH_ASSOC);
        return $resultado;
    }catch(Exception $e){
        die("Error: ".$e);
    }
}

function baseUrl(){
    $base_url = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != "off") ? "https" : "http");
    $base_url .= "://".$_SERVER['HTTP_HOST'];
    $base_url .= str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);
    return $base_url;
}