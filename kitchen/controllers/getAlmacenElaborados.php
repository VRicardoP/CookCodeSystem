<?php
require_once __DIR__ . '/../models/almacenElaboracionesDao.php';
require_once __DIR__ . '/../models/almacenElaboraciones.php';

// Verificar que la solicitud sea GET
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405); 
    exit("Solo se permiten solicitudes GET.");
}

try {
    $almacenElaboraciones = AlmacenElaboracionesDao::getAll();
    echo(json_encode($almacenElaboraciones));
    http_response_code(200);
    exit();
} catch (\Throwable $th) {
    http_response_code(500);
    echo(json_encode($th));
    exit();
}
