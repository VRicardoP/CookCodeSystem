<?php 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'elaborados.php';

header('Content-Type: application/json; charset=utf-8'); // JSON
header("Access-Control-Allow-Origin: *");

$method = $_SERVER['REQUEST_METHOD'];
$elaborados = listarElaborados();

if($method === 'GET'){
    if (isset($_GET['id'])) {
        $id = intval($_GET['id']);
        $elaborado = obtenerElaboradoPorId($id);
        $ingredientes = obtenerIngredientesReceta($id);
        if ($elaborado) {
            echo json_encode([
                'elaborado' => $elaborado,
                'ingredientes' => $ingredientes
            ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        }
    }else {
        echo json_encode($elaborados, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
}
else if($method === 'POST'){
    $input = file_get_contents('php://input');
    $data = json_decode($input, true); // Pasa a Array asociativo

    if (isset($data['nombre'], $data['instrucciones'], $data['peso'], $data['numero de raciones'], $data['caducidad dias'], $data['localizacion'], $data['empaquetado'], $data['descripcion'])) {

        $resultado = crearElaborados($data);

        if ($resultado) {
            echo json_encode(['success' => 'Receta insertada correctamente']);
        } else {
            echo json_encode(['error' => 'No se pudo insertar la receta']);
        }
    } else {
        echo json_encode(['error' => 'Datos incompletos']);
    }
}
else{
    echo json_encode(['error' => 'Método no permitido']);
}
?>