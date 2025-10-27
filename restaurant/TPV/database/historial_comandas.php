<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//session_start();
header('Content-Type: application/json');
/** 
if (empty($_SESSION['isLogged']) || !$_SESSION['isLogged']) {
    http_response_code(401);
    echo json_encode(['error' => 'Usuario no autenticado']);
    exit;
}
*/
$conn = new mysqli('localhost', 'root', '', 'restaurant');
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Error de base de datos']);
    exit;
}

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (
    !isset($data['comanda']['mesa_id'], $data['comanda']['total'], $data['comanda']['productos']) ||
    !is_array($data['comanda']['productos']) ||
    empty($data['comanda']['productos'])
) {
    http_response_code(400);
    echo json_encode(['error' => 'Datos inválidos']);
    exit;
}

$mesa_id = (int)$data['comanda']['mesa_id'];
$total = (float)$data['comanda']['total'];
$productos = $data['comanda']['productos'];

$conn->begin_transaction();

try {
    $stmtTicket = $conn->prepare("INSERT INTO comandas_historial (mesa_id, total, fecha_creacion) VALUES (?, ?, NOW())");
    if (!$stmtTicket) {
        throw new Exception("Error en la preparación de la consulta del ticket");
    }
    $stmtTicket->bind_param('id', $mesa_id, $total);
    if (!$stmtTicket->execute()) {
        throw new Exception("Error al guardar el ticket");
    }
    $ticket_id = $stmtTicket->insert_id;
    $stmtTicket->close();

    $stmtProducto = $conn->prepare("INSERT INTO comandas_detalle (comanda_id, producto_id, cantidad) VALUES (?, ?, ?)");
    if (!$stmtProducto) {
        throw new Exception("Error en la preparación de la consulta de productos");
    }

    foreach ($productos as $producto) {
        $producto_id = (int)$producto['id'];
        $cantidad = (int)$producto['cantidad'];

        if ($producto_id <= 0 || $cantidad <= 0) {
            throw new Exception("Datos inválidos en productos");
        }

        $stmtProducto->bind_param('iii', $ticket_id, $producto_id, $cantidad);
        if (!$stmtProducto->execute()) {
            throw new Exception("Error al guardar producto en el ticket");
        }
    }

    $stmtProducto->close();
    $conn->commit();

    echo json_encode(['success' => true, 'ticket_id' => $ticket_id]);
} catch (Exception $e) {
    $conn->rollback();
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}

$conn->close();
