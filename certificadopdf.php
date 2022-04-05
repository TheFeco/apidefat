<?php

require_once __DIR__ . '/vendor/autoload.php';
include_once 'db/conexion.php';
$objeto = new Conexion();
$conexion = $objeto->Conectar();
header('Access-Control-Allow-Origin: *');
$id_ciclo = isset($_GET['id_ciclo']) ? $_GET['id_ciclo'] : 1;
$consultaCiclo = "SELECT id, nombre FROM ciclos WHERE id =".$id_ciclo;
$resultado = $conexion->prepare($consultaCiclo);
$resultado->execute();
$rows=$resultado->fetchAll(PDO::FETCH_ASSOC);
foreach( $rows as $row ) {
        $ciclo = $row["nombre"];
}

$consulta = "SELECT d.folio, d.curp,d.nombre, d.apellidos, d.fh_nacimiento, d.cct, d.escuela, CASE WHEN turno = 1 THEN 'Matutino' WHEN turno = 2 THEN 'vespertino' END AS turno, f.nombre AS funcion, d.foto  FROM deportistas AS d INNER JOIN funciones AS f ON (d.id_funcion = f.id)  WHERE d.id_funcion = 3 AND d.id_ciclo = 1 AND d.id_usuairo = 1";
$resultado = $conexion->prepare($consulta);
$resultado->execute();
$data=$resultado->fetchAll(PDO::FETCH_ASSOC);

$html = '<table>';
// foreach ($variable as $key => $value) {
//     # code...
// }
for ($i=0; $i < 50; $i++) { 
$html .= '
    <tr>

        <td> 
            <img src="img/foto.jpg" class="imagen" height="100" width="100">  

            <h3>Deportista</h3>

            <h3>Mixto</h3>
            
        </td>

        <td>
            <div class="job">

                <h3>Apellido(s):</h3>
                                                        
                <h3>Nombre(s):</h3>

                <h3>Fecha de nacimiento:</h3>                 

                <h3>CURP:</h3>

                <h3>Escuela:</h3>

                <h3>Turno:</h3>

                <h3>Entidad:</h3>

                <h3>Municipio:</h3>

                <h3>Zona Escolar:</h3>

            </div>	

        </td>

        <td>

            <div class="job">
                <p>Rodríguez Betrán </p>
                                
                <p>Carlos Francisco </p>

                <p>25/12/1993</p>                            

                <p>ROBC961104HSLDLR04</p>    

                <p>General Alvaro Obregón</p>

                <p>Matutino</p>

                <p>Sinaloa</p>

                <p>Culiacán</p>

                <p>025</p>

            </div>	

        </td>
    </tr>

    <tr class="espacio" style="">
        <td>
            <h3>PRUEBA(S):</h3>
        </td>

        <td class="tdptruebas" colspan="2">
            <p>Clásico (60 min):, Rápido (30 min.):</p>
        </td>
    </tr>
';
}
$html .= '</table>';
$mpdf = new \Mpdf\Mpdf();
$stylesheet = file_get_contents('resume.css');
$mpdf->WriteHTML($stylesheet,\Mpdf\HTMLParserMode::HEADER_CSS);
$mpdf->setAutoTopMargin="stretch";
$mpdf->setAutoBottomMargin="stretch";
$mpdf->defaultheaderline = 0;
$mpdf->defaultfooterline = 0;
$mpdf->SetHeader('
<div id="inner">
    <img src="img/banner.png" width="100%">
    <h2>Juegos Deportivos Nacionales Escolares de la Educación Básica '.$ciclo.'</h2>					
</div>');

$mpdf->SetFooter('
<div id="inner">
    <h3 class="centerText">______________________________________________</h3>		
    <h3 class="centerText">Titular de Educación Física en el Estado</h3>	
    <h3 class="centerText">Nombre, Firma y Sello</h3>
</div>');

$mpdf->WriteHTML($html);

$mpdf->Output();