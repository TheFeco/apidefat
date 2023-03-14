<?php

require_once(dirname(__FILE__) . '/../db/conexion.php');
require_once "respuestas.class.php";

class deportistas extends conexion {
    
    private $token = "";

    public function obtener($id, $token){
        $_respuestas = new respuestas;

        if(!$token){
            return $_respuestas->error_401();
        }

        $this->token = $token;
        $arrayToken =   $this->buscarToken();
        if($arrayToken){

            $query = "SELECT d.id_usuairo AS id_zona, d.escuela, d.cct, 
            CASE WHEN turno = 1 THEN 'Matutino' 
                WHEN turno = 2 THEN 'Vespertino' 
                WHEN turno = 3 THEN 'Nocturno'
                WHEN turno = 4 THEN 'Discontinuo'
                WHEN turno = 5 THEN 'Continuo'
            END AS turno,
            d.id_ciclo, c.nombre AS ciclo, d.id_funcion, f.nombre AS funcion, dp.nombre AS deporte, d.id_deporte, d.id_rama, ramas.nombre AS rama, d.id_categoria, d.id_peso, d.id_prueba 
            FROM deportistas AS d 
            INNER JOIN ciclos AS c ON (d.id_ciclo = c.id) 
            INNER JOIN funciones AS f  ON (d.id_funcion = f.id) 
            LEFT JOIN deportes AS dp ON (d.id_deporte = dp.id) 
            LEFT JOIN ramas ON (d.id_rama = ramas.id) 
            WHERE d.id_usuairo = $id ";

            $query.= "GROUP BY d.escuela, ciclo, funcion, deporte ORDER BY funcion, deporte, d.created_at DESC";

            return parent::obtenerDatos($query);

        }else{
            return $_respuestas->error_401("El Token que envio es invalido o ha caducado");
        }

    }
    public function obtenerById($id, $token){
        $_respuestas = new respuestas;

        if(!$token){
            return $_respuestas->error_401();
        }

        $this->token = $token;
        $arrayToken =   $this->buscarToken();
        if($arrayToken){

            $query = "SELECT d.* 
            FROM deportistas AS d
            WHERE d.id = $id ";


            return parent::obtenerDatos($query);

        }else{
            return $_respuestas->error_401("El Token que envio es invalido o ha caducado");
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