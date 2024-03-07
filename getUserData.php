<?php
include_once 'db/conexion.php';

$objeto = new Conexion();
$conexion = $objeto->Conectar();

header('Access-Control-Allow-Origin: *');

if ($_POST['METHOD'] == 'POST') {
    unset($_POST['METHOD']);
    extract($_POST);

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
    WHERE d.id_usuairo = :id_usuario AND c.id = :ciclo AND id_funcion = :funcion";

    $params = ['id_usuario' => $usuario, 'ciclo' => $id_ciclos, 'funcion' => $id_funciones];

    $filters = [
        'deporte' => 'AND id_deporte = :id_deportes',
        'rama' => 'AND id_rama = :id_ramas',
        'categoria' => 'AND id_categoria = :categoria',
        'peso' => 'AND id_peso = :peso',
        'prueba' => 'AND id_prueba = :prueba'
    ];

    foreach ($filters as $key => $filter) {
        if (!empty($$key)) {
            $query .= " $filter";
            $params[$key] = $$key;
        }
    }

    $query .= " GROUP BY d.id_usuairo, d.escuela, d.cct, turno, d.id_ciclo, c.nombre, d.id_funcion, f.nombre, dp.nombre, d.id_deporte, d.id_rama, ramas.nombre";

    // Imprime la consulta para depuración
    /*echo "Consulta SQL: " . $query . "\n";
    echo "Parámetros: ";
    print_r($params);
    echo "\n";*/

    $stmt = $conexion->prepare($query);
    $stmt->execute($params);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header("HTTP/1.1 200 OK");
    return print json_encode(['data' => $data]);
    $conexion = null;
}
?>
