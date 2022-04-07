<?php

use Mpdf\Tag\Legend;

require_once __DIR__ . '/vendor/autoload.php';
include_once 'db/conexion.php';
$objeto = new Conexion();
$conexion = $objeto->Conectar();
header('Access-Control-Allow-Origin: *');
$_POST['METHOD']='POST';
if($_POST['METHOD']=='POST'){
$id_usuario = isset($_GET['usuarios']) ? $_GET['usuarios'] : 0;
$id_ciclo = isset($_GET['ciclo']) ? $_GET['ciclo'] : 1;
$id_funcion = isset($_GET['funcion']) ? $_GET['funcion'] : 0;
$id_deporte = isset($_GET['deporte']) ? $_GET['deporte'] : 0;
$id_rama = isset($_GET['rama']) ? $_GET['rama'] : 0;
$id_categoria = isset($_GET['categoria']) ? $_GET['categoria'] : 0;
$id_peso = isset($_GET['peso']) ? $_GET['peso'] : 0;
$id_prueba = isset($_GET['peso']) ? $_GET['peso'] : 0;

//obtenemos el nombre del ciclo
$consultaCiclo = "SELECT id, nombre FROM ciclos WHERE id =".$id_ciclo;
$resultado = $conexion->prepare($consultaCiclo);
$resultado->execute();
$rows=$resultado->fetchAll(PDO::FETCH_ASSOC);
foreach( $rows as $row ) {
        $ciclo = $row["nombre"];
}

$consulta = "SELECT d.folio, d.nombre, d.apellidos, d.curp, d.foto,DATE_FORMAT(d.fh_nacimiento,'%d/%m/%Y') AS fh_nacimeinto, d.cct, d.escuela, d.zona, CASE WHEN turno = 1 THEN 'Matutino' WHEN turno = 2 THEN 'vespertino' END AS turno,c.nombre AS ciclo, m.nombre AS municipio, f.nombre AS funcion, dp.nombre AS deporte, r.nombre AS rama, cat.nombre AS categoria, peso.nombre AS peso, pruebas.nombre AS prueba, CONCAT_WS(':, ', cat.nombre,peso.nombre,pruebas.nombre) AS array_pruebas
FROM deportistas AS d 
INNER JOIN ciclos AS c ON (d.id_ciclo = c.id) 
INNER JOIN funciones AS f  ON (d.id_funcion = f.id) 
INNER JOIN municipios AS m ON( d.id_municipio = m.id) 
LEFT JOIN deportes AS dp ON (d.id_deporte = dp.id) 
LEFT JOIN ramas AS r ON( d.id_rama = r.id )
LEFT JOIN  categorias AS cat ON ( d.id_categoria = cat.id)
LEFT JOIN peso ON (d.id_peso = peso.id)
LEFT JOIN pruebas ON (d.id_prueba = pruebas.id)
";

$resultado = $conexion->prepare($consulta);
$resultado->execute();
if($resultado->rowCount() >= 1){
    $data = $resultado->fetchAll(PDO::FETCH_ASSOC);
}else{
    $d = array('menssage' => 'No se encontro ningun dato.');
    header("HTTP/1.1 500 OK");
    return print json_encode($d);
    $conexion = NULL;
}
$html = '';
$html .= '<table>';

foreach ($data as $row) { 
$html .= '
        <tr>

            <td> 
                <img src="'.$row["foto"].'" class="imagen" height="100" width="100">  

                <h3>'.$row["funcion"].'</h3>

                <h3>'.$row["rama"].'</h3>
                
            </td>

            <td>
                <div class="job">
                    <span style="display: inline-flex"><span><h3>Apellido(s): </h3></span><span><p>'.$row["apellidos"].'</p></span> </span>
                    <span style="display: inline-flex"><span><h3>Nombre(s): </h3></span><span><p>'.$row["nombre"].'</p></span> </span>
                    <span style="display: inline-flex"><span><h3>Fecha de nacimiento: </h3></span><span><p>'.$row["fh_nacimeinto"].'</p></span> </span>
                    <span style="display: inline-flex"><span><h3>CURP: </h3></span><span><p>'.$row["curp"].'</p></span> </span>
                    <span style="display: inline-flex"><span><h3>Escuela: </h3></span><span><p>'.$row["escuela"].'</p></span> </span>
                    <span style="display: inline-flex"><span><h3>Turno: </h3></span><span><p>'.$row["turno"].'</p></span> </span>
                    <span style="display: inline-flex"><span><h3>Entidad: </h3></span><span><p>Sinaloa</p></span> </span>
                    <span style="display: inline-flex"><span><h3>Municipio: </h3></span><span><p>'.$row["municipio"].'</p></span> </span>
                    <span style="display: inline-flex"><span><h3>Zona Escolar: </h3></span><span><p>'.str_pad($row["zona"],2,"0", STR_PAD_LEFT).'</p></span> </span>
                </div>	

            </td>
        </tr>

        <tr class="espacio" style="">
            <td>
                <h3>PRUEBA(S):</h3>
            </td>

            <td class="tdptruebas" colspan="2">
                <p>'.$row["array_pruebas"].'</p>
            </td>
        </tr>
    ';
}
$html .= '</table>';
$mpdf = new \Mpdf\Mpdf([
    'mode' => 'utf-8',
    'marginLeft' => 0,
    'format' => 'LEGAL',
    'orientation' => 'P'
]);
$stylesheet = file_get_contents('bootstrap.min.css');
$stylesheet = file_get_contents('resume.css');
$mpdf->WriteHTML($stylesheet,1);
$mpdf->setAutoTopMargin="stretch";
$mpdf->setAutoBottomMargin="stretch";
$mpdf->defaultheaderline = 0;
$mpdf->defaultfooterline = 0;
$mpdf->SetDisplayMode('fullpage');
$mpdf->SetHeader('
<div id="inner">
    <img src="img/banner.png" width="100%">
    <h2>Juegos Deportivos Escolares de la Educación Básica '.$ciclo.'</h2>					
</div>');

$mpdf->SetFooter('
<div id="inner2">
    <h3 class="centerText">______________________________________________</h3>		
    <h3 class="centerText">Titular de Educación Física en el Estado</h3>	
    <h3 class="centerText">Nombre, Firma y Sello</h3>
</div>');
$mpdf->SetColumns(2);
$mpdf->WriteHTML($html);

$mpdf->Output();
}else{
    header("HTTP/1.1 500 Ok");
    $d = array('menssage' => "Error no es usuario administrador");
    return print json_encode($d);
    $conexion = NULL;
}