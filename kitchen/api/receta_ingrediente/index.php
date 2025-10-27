<?php 

require 'receta_ingrediente.php';

header('Content-Type: application/json; charset=utf-8'); // JSON
header("Access-Control-Allow-Origin: *");

$method = $_SERVER['REQUEST_METHOD'];
$receta_ingrediente = listarReceta_ingrediente();

if($method === 'GET'){
        if (isset($_GET['id'])) {
        $id = intval($_GET['id']);
        $recetas = obtenerRecetaPorId($id);

        if ($recetas) {
            echo json_encode($recetas, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        } else {
            echo json_encode(['error' => 'Receta no encontrado']);
        }
    } else {
        echo json_encode($receta_ingrediente, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
}

// else if($method === 'POST'){
//     $input = file_get_contents('php://input');
//     $data = json_decode($input, true); // Pasa a Array asociativo

//     if (isset($data['nombre'], $data['instrucciones'], $data['peso'], $data['numero de raciones'], $data['caducidad dias'], $data['localizacion'], $data['empaquetado'], $data['descripcion'])) {

//         $resultado = crearreceta_ingrediente($data);

//         if ($resultado) {
//             echo json_encode(['success' => 'Receta insertada correctamente']);
//         } else {
//             echo json_encode(['error' => 'No se pudo insertar la receta']);
//         }
//     } else {
//         echo json_encode(['error' => 'Datos incompletos']);
//     }
// }
else{
    echo json_encode(['error' => 'Método no permitido']);
}
?>