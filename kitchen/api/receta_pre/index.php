<?php 

require 'receta_preelaborado.php';

header('Content-Type: application/json; charset=utf-8'); // JSON
header("Access-Control-Allow-Origin: *");

$method = $_SERVER['REQUEST_METHOD'];
$receta_preelaborado = listarReceta_preelaborado();

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
        echo json_encode($receta_preelaborado, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
}

else{
    echo json_encode(['error' => 'Método no permitido']);
}
?>