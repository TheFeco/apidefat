<?php
include_once 'db/conexion.php';

$objeto = new Conexion();
$conexion = $objeto->Conectar();

header('Access-Control-Allow-Origin: *');

if ($_POST['METHOD'] == 'POST') {
    unset($_POST['METHOD']);

    $usuario = $_POST['usuario'] ?? null;
    $id_ciclo = $_POST['id_ciclo'] ?? null;
    $id_funcion = $_POST['id_funcion'] ?? null;
    $id_deporte = $_POST['id_deporte'] ?? null;
    $id_rama = $_POST['id_rama'] ?? null;

    $query = "SELECT d.id_usuairo AS id_zona, d.escuela, d.cct, 
    CASE 
        WHEN turno = 1 THEN 'Matutino' 
        WHEN turno = 2 THEN 'Vespertino' 
        WHEN turno = 3 THEN 'Nocturno'
        WHEN turno = 4 THEN 'Discontinuo'
        WHEN turno = 5 THEN 'Continuo'
    END AS turno,
    d.id_ciclo, c.nombre AS ciclo, d.id_funcion, f.nombre AS funcion, dp.nombre AS deporte, d.id_deporte, d.id_rama, ramas.nombre AS rama  
    FROM deportistas AS d 
    INNER JOIN ciclos AS c ON (d.id_ciclo = c.id) 
    INNER JOIN funciones AS f ON (d.id_funcion = f.id) 
    LEFT JOIN deportes AS dp ON (d.id_deporte = dp.id) 
    LEFT JOIN ramas ON (d.id_rama = ramas.id) 
    WHERE d.id_usuairo = $usuario AND c.id = $id_ciclo AND id_funcion = $id_funcion";

    if (!empty($id_deporte)) {
        $query .= " AND id_deporte = $id_deporte";
    }

    if (!empty($id_rama)) {
        $query .= " AND id_rama = $id_rama";
    }

    $query .= " GROUP BY d.id_usuairo, d.escuela, d.cct, turno, d.id_ciclo, c.nombre, d.id_funcion, f.nombre, dp.nombre, d.id_deporte, d.id_rama, ramas.nombre";

    // Imprime la consulta para depuración
    //echo "Consulta SQL: " . $query . "\n";
    // echo "Consulta SQL: " . $query . "\n";
    // echo "Parámetros: ";
    // echo "\n";

    $stmt = $conexion->prepare($query);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // print_r($data);
    $conexion = null;
    if (empty($data)) {
        header("HTTP/1.1 404 Not Found");
        print json_encode(['message' => 'No se encontraron datos']);
    } else {
        header("HTTP/1.1 200 OK");
        print json_encode(['data' => $data]);
    }
}
?>
