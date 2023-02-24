<?php

require_once(dirname(__FILE__) . '/../db/conexion.php');
require_once "respuestas.class.php";

class usuarios extends conexion {

    private $table = "usuarios";
    private $token = "";
    private $id = "";
    private $nombre  = "";
    private $estado = "";

    public function obtener($token){
        $_respuestas = new respuestas;

        if(!$token){
            return $_respuestas->error_401();
        }

        $this->token = $token;
        $arrayToken =   $this->buscarToken();
        if($arrayToken){

            $query = "SELECT id, usuario, Estado FROM " . $this->table . " WHERE id != 1 ORDER BY id_rol";
            
            return parent::obtenerDatos($query);

        }else{
            return $_respuestas->error_401("El Token que envio es invalido o ha caducado");
        }

    }

    public function putEstado($json){
        $_respuestas = new respuestas;
        $datos = $json;

        if(!isset($datos['token'])){
            return $_respuestas->error_401();
        }else{
            $this->token = $datos['token'];
            $arrayToken =   $this->buscarToken();
            if($arrayToken){
                if(!isset($datos['usuarioId'])){
                    return $_respuestas->error_400();
                }else{
                    $this->id = $datos['usuarioId'];
                    $this->estado = $datos['estado'];
        
                    $resp = $this->modificarEstado();
                    if($resp){
                        $respuesta = $_respuestas->response;
                        $respuesta["result"] = array(
                            "id" => $this->id
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

    private function modificarEstado(){
        $query = "UPDATE " . $this->table . " SET Estado ='" . $this->estado .
         "' WHERE id = '" . $this->id . "'"; 
         
        $resp = parent::nonQuery($query);
        if($resp >= 1){
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