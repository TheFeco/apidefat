<?php

require_once(dirname(__FILE__) . '/../db/conexion.php');
require_once "respuestas.class.php";

class usuarios extends conexion {

    private $table = "usuarios";
    private $token = "";
    private $id = 0;
    private $usuario  = "";
    private $password = "";
    private $rol = "";
    private $nivel ="";
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

    public function obtenerConId($id, $token){
        $_respuestas = new respuestas;

        if(!$token){
            return $_respuestas->error_401();
        }

        $this->token = $token;
        $arrayToken =   $this->buscarToken();
        if($arrayToken){

            $query = "SELECT id, usuario, id_rol, id_nivel FROM " . $this->table . " WHERE id = '$id' ";
            
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

                if(!isset($datos['usuario']) || !isset($datos['password'])){
                    return $_respuestas->error_400();
                }else{

                    $this->usuario = $datos['usuario'];
                    $arrayUsuario =   $this->existeUsuario();
                    
                    if(!$arrayUsuario){ 
                        $this->password = parent::encriptar($datos['password']);
                        $this->rol = $datos['rol'];
                        $this->nivel = $datos['nivel'];
                        $resp = $this->insertarUsuario();
                        if($resp){
                            $respuesta = $_respuestas->response;
                            $respuesta["result"] = array(
                                "id" => $resp
                            );
                            return $respuesta;
                        }else{
                            return $_respuestas->error_500();
                        }
                    }else{
                        return $_respuestas->error_401("El usuario ya existe");
                    }
                }

            }else{
                return $_respuestas->error_401("El Token que envio es invalido o ha caducado");
            }
        }
    }

    public function put($json){
        $_respuestas = new respuestas;
        $datos = $json;
        if(!isset($datos['token'])){
                return $_respuestas->error_401();
        }else{
            $this->token = $datos['token'];
            $arrayToken =   $this->buscarToken();
            if($arrayToken){

                if(!isset($datos['usuario'])){
                    return $_respuestas->error_400();
                }else{
                    $this->id = $datos['id'];
                    $this->usuario = $datos['usuario'];
                    $arrayUsuario =   $this->existeUsuario();
                    
                    if(!$arrayUsuario){ 
                        $this->rol = $datos['rol'];
                        $this->nivel = $datos['nivel'];
                        $resp = $this->modificarUsuario();
                        if($resp){
                            $respuesta = $_respuestas->response;
                            $respuesta["result"] = array(
                                "id" => $resp
                            );
                            return $respuesta;
                        }else{
                            return $_respuestas->error_500();
                        }
                    }else{
                        return $_respuestas->error_401("El usuario ya existe");
                    }
                }

            }else{
                return $_respuestas->error_401("El Token que envio es invalido o ha caducado");
            }
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

    public function putPassword($json){
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
                    $this->password = parent::encriptar($datos['password']);
        
                    $resp = $this->modificarPassword();
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

    public function delete($json){
        $_respuestas = new respuestas;
        $datos = $json;

        if(!isset($datos['token'])){
            return $_respuestas->error_401();
        }else{
            $this->token = $datos['token'];
            $arrayToken =   $this->buscarToken();
            if($arrayToken){

                if(!isset($datos['id'])){
                    return $_respuestas->error_400();
                }else{
                    $this->id = $datos['id'];
                    $resp = $this->eliminarUsuario();
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

    private function insertarUsuario(){
        $query = "INSERT INTO " . $this->table . " (usuario,password,id_rol,id_nivel,Estado)
        values
        ('" . $this->usuario . "','" . $this->password . "'," . $this->rol ."," . $this->nivel . ",'Activo')"; 

        $resp = parent::nonQueryId($query);
        if($resp){
             return $resp;
        }else{
            return 0;
        }
    }

    private function modificarUsuario(){
        $query = "UPDATE " . $this->table . " SET usuario = '" . $this->usuario . "', id_rol = '" . $this->rol . "', id_nivel = '" . $this->nivel . "' " .
                 "WHERE id = '" . $this->id . "'";

        $resp = parent::nonQuery($query);
       
    
        if ($resp >= 1) {
            return $resp;
        } else {
            return 0;
        }
    }

    private function existeUsuario() {
        $query = "SELECT id FROM usuarios WHERE usuario = '" . $this->usuario . "'";
        if ($this->id > 0) {
            $query .= " AND id <> " . $this->id;
        }
    
        $query .= " LIMIT 1";
        $resp = parent::nonQueryId($query);

        if($resp){
            return $resp;
        }else{
           return 0;
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

    private function modificarPassword(){
        $query = "UPDATE " . $this->table . " SET password ='" . $this->password .
         "' WHERE id = '" . $this->id . "'"; 
         
        $resp = parent::nonQuery($query);
        if($resp >= 1){
             return $resp;
        }else{
            return 0;
        }
    }

    private function eliminarUsuario(){
        $query = "DELETE FROM " . $this->table . " WHERE id= '" . $this->id . "'";
        $resp = parent::nonQuery($query);
        if($resp >= 1 ){
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