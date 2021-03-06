<?php 
class Conexion{	  
    public static function Conectar() {        
        define('servidor', 'localhost');
        define('nombre_bd', 'defat');
        define('usuario', 'root');
        define('password', 't3cnolog14');					        
        // define('password', '');					        
        $opciones = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');			
        try{
            $conexion = new PDO("mysql:host=".servidor."; dbname=".nombre_bd, usuario, password, $opciones);			
            return $conexion;
        }catch (Exception $e){
            die("El error de Conexión es: ". $e->getMessage());
        }
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