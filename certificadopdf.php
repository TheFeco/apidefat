<?php

use Mpdf\Tag\Legend;

require_once __DIR__ . '/vendor/autoload.php';
include_once 'db/conexion.php';
$objeto = new Conexion();
$conexion = $objeto->Conectar();
header('Access-Control-Allow-Origin: *');

if($_POST['METHOD']=='POST'){
print_r("entre me la chuoas cros");
die();
$id_usuario = isset($_POST['usuario']) ? $_POST['usuario'] : 0;
$id_ciclo = isset($_POST['ciclo']) ? $_POST['ciclo'] : 1;
$id_funcion = isset($_POST['funcion']) ? $_POST['funcion'] : 0;
$id_deporte = isset($_POST['deporte']) ? $_POST['deporte'] : 0;
$id_rama = isset($_POST['rama']) ? $_POST['rama'] : 0;
$id_categoria = isset($_POST['categoria']) ? $_POST['categoria'] : 0;
$id_peso = isset($_POST['peso']) ? $_POST['peso'] : 0;
$id_prueba = isset($_POST['prueba']) ? $_POST['prueba'] : 0;
$cct = isset($_POST['cct']) ? $_POST['cct'] : 0;

// Variables para el pdf
$colums = 2;

//obtenemos el nombre del ciclo
$ciclo = "";
$consultaCiclo = "SELECT id, nombre FROM ciclos WHERE id =".$id_ciclo;
$resultado = $conexion->prepare($consultaCiclo);
$resultado->execute();
$rows=$resultado->fetchAll(PDO::FETCH_ASSOC);
foreach( $rows as $row ) {
        $ciclo = $row["nombre"];
}

//obtenemos el nombre del deporte
$deporte ="";
$consultaDeporte = "SELECT id, nombre FROM deportes WHERE id =".$id_deporte;
$resultado = $conexion->prepare($consultaDeporte);
$resultado->execute();
$rows=$resultado->fetchAll(PDO::FETCH_ASSOC);
foreach( $rows as $row ) {
        $deporte = $row["nombre"];
}


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
if($resultado->rowCount() >= 1){
    $data = $resultado->fetchAll(PDO::FETCH_ASSOC);
    if($resultado->rowCount() === 1){
        $colums = 1;
    }
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
        <tr class="espacio">
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
    'marginTop' => 0,
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
    <img src="imagenes/Cintillo.jpg" width="100%">
    <h2>Juegos Deportivos Escolares de la Educación Básica '.$ciclo.'</h2>
    <h3>'.$deporte.'</h3>				
</div>');

$mpdf->SetFooter('
<div id="inner2">
    <h3 class="centerText">______________________________________________</h3>		
    <h3 class="centerText">Titular de Educación Física en el Estado</h3>	
    <h3 class="centerText">Nombre, Firma y Sello</h3>
</div>');
$mpdf->SetColumns($colums);
$mpdf->WriteHTML($html);
$mpdf->AddColumn();
$name = 'cedulas-' . md5(uniqid(mt_rand(), true)) . '.pdf';
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