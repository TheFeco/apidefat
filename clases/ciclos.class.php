<?php

require_once(dirname(__FILE__) . '/../db/conexion.php');
require_once "respuestas.class.php";

class ciclos extends conexion {
    private $table = "ciclos";
    private $token = "";
    private $nombre  = "";
    public function obtener($token){
        $_respuestas = new respuestas;
        
        if(!$token){
            return $_respuestas->error_401();
        }

        $this->token = $token;
        var_dump($this->token);
        var_dump('putos');
        $arrayToken =   $this->buscarToken();
        var_dump($arrayToken);
        if($arrayToken){

            $query = "SELECT id, nombre FROM ciclos ORDER BY id DESC";
            return parent::obtenerDatos($query);

        }else{
            return $_respuestas->error_401("El Token que envio es invalido o ha caducado");
        }

    }

    public function post($json){
        $_respuestas = new respuestas;
        $datos = $json;

        if(!isset($datos['token'])){
                return $_respuestas->error_401();
        }else{
            $this->token = $datos['token'];
            $arrayToken =   $this->buscarToken();
            if($arrayToken){

                if(!isset($datos['nombre'])){
                    return $_respuestas->error_400();
                }else{
                    $this->nombre = $datos['nombre'];
                    $resp = $this->insertarCiclo();
                    if($resp){
                        $respuesta = $_respuestas->response;
                        $respuesta["result"] = array(
                            "cicloId" => $resp
                        );
                        return $respuesta;
                    }else{
                        return $_respuestas->error_500();
                    }
                }

            }else{
                return $_respuestas->error_401("El Token que envio es invalido o ha caducado");
            }
        }

    }

    private function insertarCiclo(){
        $query = "INSERT INTO " . $this->table . " (Nombre)
        values
        ('" . $this->nombre . "')"; 
        $resp = parent::nonQueryId($query);
        if($resp){
             return $resp;
        }else{
            return 0;
        }
    }    

    private function buscarToken(){
        $query = "SELECT  TokenId,UsuarioId,Estado from usuarios_token WHERE Token = '" . $this->token . "' AND Estado = 'Activo'";
        $resp = parent::obtenerDatos($query);
        if($resp){
            return $resp;
        }else{
            return 0;
        }
    }
}
?>