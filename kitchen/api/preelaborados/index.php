<?php 

require 'preelaborados.php';

header('Content-Type: application/json; charset=utf-8'); // JSON
header("Access-Control-Allow-Origin: *");

$method = $_SERVER['REQUEST_METHOD'];
$preelaborados = listarPreelaborados();

if($method === 'GET'){
    echo json_encode($preelaborados, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
}
else if($method === 'POST'){
    $input = file_get_contents('php://input');
    $data = json_decode($input, true); // Pasa a Array asociativo

    if (isset($data['nombre'], $data['instrucciones'], $data['peso'], $data['numero de raciones'], $data['caducidad dias'], $data['localizacion'], $data['empaquetado'], $data['descripcion'])) {

        $resultado = crearPreelaborados($data);

        if ($resultado) {
            echo json_encode(['success' => 'Preelaborado insertado correctamente']);
        } else {
            echo json_encode(['error' => 'No se pudo insertar el preelaborado']);
        }
    } else {
        echo json_encode(['error' => 'Datos incompletos']);
    }
}
else{
    echo json_encode(['error' => 'Método no permitido']);
}
?>