<?php
include_once 'db/conexion.php';
$objeto = new Conexion();
$conexion = $objeto->Conectar();
header('Access-Control-Allow-Origin: *');
$_POST['METHOD']='POST';
if($_POST['METHOD']=='POST'){
    //Variables del post
    $id_usuario = isset($_POST['usuario']) ? $_POST['usuario'] : 0;
    $id_ciclo = isset($_POST['ciclo']) ? $_POST['ciclo'] : 0;
    $id_funcion = isset($_POST['funcion']) ? $_POST['funcion'] : 0;
    $id_deporte = isset($_POST['deporte']) ? $_POST['deporte'] : 0;
    $id_rama = isset($_POST['rama']) ? $_POST['rama'] : 0;
    $id_categoria = isset($_POST['categoria']) ? $_POST['categoria'] : 0;
    $id_peso = isset($_POST['peso']) ? $_POST['peso'] : 0;
    $id_prueba = isset($_POST['prueba']) ? $_POST['prueba'] : 0;
    $cct = isset($_POST['cct']) ? $_POST['cct'] : 0;

    //Consultas de Mysql que trae
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
    if($id_categoria != 0){
        $consulta .= "AND d.id_categoria = '$id_categoria' "; 
    }
    if($id_peso != 0){
        $consulta .= "AND d.id_peso = '$id_peso' "; 
    }
    if($id_prueba != 0){
        $consulta .= "AND d.id_prueba = '$id_prueba' "; 
    }
    if($cct != 0){
        $consulta .= "AND d.cct = '$cct' "; 
    }

    $resultado = $conexion->prepare($consulta);
    $resultado->execute();
    $rows = $resultado->rowCount();
    if($resultado->rowCount() >= 1){
        $data = $resultado->fetchAll(PDO::FETCH_ASSOC);

    }else{
        $d = array();
        header("HTTP/1.1 200 OK");
        return print json_encode($d);
        $conexion = NULL;
    }

    $html = '';
    $i = 0;
    foreach ($data as $row){
    // for ($i=0; $i < $rows ; $i++) { 
        # code...
        if( $i % 2 != 0 ){
        $html.='
        
        <div class="gafete">
            <img src="imagenes/gafete.jpg" />
        </div>
    
    
        <div style="
        position: absolute; 
        bottom: 290PX; 
        left: 23px; 
        font-size: large;
        text-align: center; 
        width: 300px; 
        text-transform: uppercase; 
        font-weight: bold;" 
        class="texto">
    
            '.$row["funcion"].' / '.$row["deporte"].'
    
        </div>
    
        <div style="
        position: absolute; 
        bottom: 260PX; 
        left: 23px; 
        font-size: large;
        text-align: center; 
        width: 300px; 
        text-transform: uppercase;" 
        class="texto">
    
        '.$row["rama"].'
    
        </div>
    
        <div style="
        position: absolute; 
        bottom: 80PX; 
        left: 25px; 
        font-size: large;
        text-align: center; 
        width: 300px; 
        text-transform: uppercase; 
        font-weight: bold;" 
        class="texto">
    
            '.$row["nombre"].'
    
        </div>
    
        <div style="
        position: absolute; 
        bottom: 55PX; 
        left: 26px; 
        font-size: large;
        text-align: center; 
        width: 300px; 
        text-transform: uppercase; 
        font-weight: bold;" 
        class="texto">
    
            '.$row["apellidos"].'
    
        </div>
    
        <div style="
        position: absolute; 
        bottom: 110PX; 
        left: 128px;" 
        class="foto">
    
            <img class="fotoimg" src="'.$row["foto"].'" />
    
        </div>
    
    
        ';
        }
        if( $i % 2 == 0 ){
            $html.='
            <div class="gafete">
                <img src="imagenes/gafete.jpg" />
            </div>
            <div style="
            position: absolute; 
            top: 200PX; 
            left: 23px; 
            font-size: large;
            text-align: center; 
            width: 300px; 
            text-transform: uppercase; 
            font-weight: bold;" 
            class="texto">
    
            '.$row["funcion"].' / '.$row["deporte"].'
            </div>

            <div style="
            position: absolute; 
            top: 230PX; 
            left: 23px; 
            font-size: large;
            text-align: center; 
            width: 300px; 
            text-transform: uppercase;"
            class="texto">

            '.$row["rama"].'

            </div>

            <div style="
            position: absolute; 
            top: 410PX; 
            left: 25px; 
            font-size: large;
            text-align: center; 
            width: 300px; 
            text-transform: uppercase; 
            font-weight: bold;" 
            class="texto">

                '.$row["nombre"].'

            </div>

            <div style="
            position: absolute; 
            top: 435PX; 
            left: 25px; 
            font-size: large;
            text-align: center; 
            width: 300px; 
            text-transform: uppercase; 
            font-weight: bold;" 
            class="texto">

                '.$row["apellidos"].'

            </div>
            
            <div style="
            position: absolute; 
            top: 295PX; 
            left: 128px;" 
            class="foto">
        
                <img class="fotoimg" src="'.$row["foto"].'" />
        
            </div>    
            ';
            if($i == $rows){
                $html.='<div class="page_break"></div>';
            }
            
        }
        $i++;
    }



    require_once __DIR__ . '/vendor/autoload.php';
    $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8',
                            'format' => [180, 270],
                        ]);
    $stylesheet = file_get_contents('gafete.css');
    $mpdf->WriteHTML($stylesheet,1);
    // $mpdf->SetColumns(2, 'J', 2);


    $mpdf->WriteHTML($html);

    $name = 'gafete-' . md5(uniqid(mt_rand(), true)) . '.pdf';
    $filename = "files/tmp/";
    if (!file_exists($filename)) {
        mkdir($filename, 0777, true);
    }
    $filename.=$name;
    $mpdf->Output($filename, 'F');
    echo json_encode(["file"=>$filename, "name"=>$name]);
}else{
    header("HTTP/1.1 500 Ok");
    $d = array('menssage' => "Error no es usuario administrador");
    return print json_encode($d);
    $conexion = NULL;
}