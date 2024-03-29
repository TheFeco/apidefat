<?php
include_once 'db/conexion.php';

$objeto = new Conexion();
$conexion = $objeto->Conectar();

header('Access-Control-Allow-Origin: *');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if ($_GET['rol'] == 1) {
        $consultas = [
            'usuarios' => "SELECT id, usuario AS nombre FROM usuarios WHERE id_rol = 2 ORDER BY id",
            'funciones' => "SELECT id, nombre FROM funciones ORDER BY id",
            'ciclos' => "SELECT id, nombre FROM ciclos ORDER BY id DESC LIMIT 5",
            'deportes' => "SELECT id, nombre FROM deportes ORDER BY id",
            'ramas' => "SELECT id, nombre FROM ramas ORDER BY id",
            'municipios' => "SELECT id, nombre FROM municipios ORDER BY id"
        ];
        $resultado = [];
        foreach ($consultas as $clave => $consulta) {
            $stmt = $conexion->prepare($consulta);
            $stmt->execute();
            $resultado[$clave] = $stmt->rowCount() >= 1 ? $stmt->fetchAll(PDO::FETCH_ASSOC) : null;
        }
        header("HTTP/1.1 200 OK");
        return print json_encode($resultado);
    } else {
        $d = array('message' => 'Error: No es usuario administrador');
        header("HTTP/1.1 403 Forbidden");
        return print json_encode($d);
    }
    $conexion = null;
}

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
    d.id_ciclo, c.nombre AS ciclo, d.id_funcion, f.nombre AS funcion, dp.nombre AS deporte, d.id_deporte, d.id_rama, ramas.nombre AS rama, d.id_categoria, d.id_peso, d.id_prueba  
    FROM deportistas AS d 
    INNER JOIN ciclos AS c ON (d.id_ciclo = c.id) 
    INNER JOIN funciones AS f ON (d.id_funcion = f.id) 
    LEFT JOIN deportes AS dp ON (d.id_deporte = dp.id) 
    LEFT JOIN ramas ON (d.id_rama = ramas.id) 
    WHERE d.id_usuairo = :id_usuario AND c.id = :ciclo AND id_funcion = :funcion";

    $params = ['id_usuario' => $usuario, 'ciclo' => $ciclo, 'funcion' => $funcion];

    $filters = [
        'deporte' => 'AND id_deporte = :deporte',
        'rama' => 'AND id_rama = :rama',
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

    $query .= " GROUP BY d.id_usuairo, d.escuela, d.cct, turno, d.id_ciclo, c.nombre, d.id_funcion, f.nombre, dp.nombre, d.id_deporte, d.id_rama, ramas.nombre, d.id_categoria, d.id_peso, d.id_prueba";

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
