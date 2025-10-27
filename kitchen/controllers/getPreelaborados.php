<?php
require_once __DIR__ . '/../models/recetasDao.php';
require_once __DIR__ . '/../models/recetas.php';

// Verificar que la solicitud sea GET
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405); 
    exit("Solo se permiten solicitudes GET.");
}

try {
    $recetas = RecetasDao::getPreelaborados();
    echo(json_encode($recetas));
    http_response_code(200);
    exit();
} catch (\Throwable $th) {
    http_response_code(500);
    echo json_encode([
        "error" => $th->getMessage(),
        "file" => $th->getFile(),
        "line" => $th->getLine()
    ]);    
    exit();
}