<?php 

require 'ing_etiquetas.php';

header('Content-Type: application/json; charset=utf-8'); // JSON
header('Access-Control-Allow-Origin: *');

$method = $_SERVER['REQUEST_METHOD'];
$etiquetas = listarEtiquetas();

if($method === 'GET'){
    echo json_encode($etiquetas, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
}
else{
    echo json_encode(['error' => 'Método no permitido']);
}
?>