<?php

require_once(dirname(__FILE__) . '/../db/conexion.php');
require_once "respuestas.class.php";

class escuelas extends conexion {
    public function getNombre($escuela){

        $query = "SELECT nombre FROM escuelas WHERE cct =$escuela";
        $datos = parent::obtenerDatos($query);
        return ($datos);
    }
}
?>