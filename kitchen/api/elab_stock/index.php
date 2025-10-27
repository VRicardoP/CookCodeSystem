<?php 

require 'elab_stock.php';

header('Content-Type: application/json; charset=utf-8'); // JSON
header('Access-Control-Allow-Origin: *');

$method = $_SERVER['REQUEST_METHOD'];
$stock = listarStock();

if($method === 'GET'){
    echo json_encode($stock, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
}
else{
    echo json_encode(['error' => 'Método no permitido']);
}
?>