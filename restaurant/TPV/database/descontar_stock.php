<?php
session_start();
header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (empty($_SESSION['isLogged']) || !$_SESSION['isLogged']) {
    http_response_code(401);
    echo json_encode(['error' => 'Usuario no autenticado']);
    exit;
}

$conn = new mysqli('localhost', 'root', '', 'restaurant');
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Error de base de datos']);
    exit;
}

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!isset($data['producto'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Producto no recibido']);
    exit;
}

$producto = $data['producto'];
$idElaborado = (int)$producto['id'];
$cantidadNecesaria = 1; // 👈 solo restar 1

$conn->begin_transaction();

try {
    $stmtLotes = $conn->prepare("SELECT id, cantidad_stock FROM stock WHERE elaborado_id = ? AND cantidad_stock > 0 ORDER BY id ASC");
    $stmtLotes->bind_param('i', $idElaborado);
    $stmtLotes->execute();
    $resultadoLotes = $stmtLotes->get_result();

    $lotes = [];
    while ($lote = $resultadoLotes->fetch_assoc()) {
        $lotes[] = $lote;
    }
    $stmtLotes->close();

    foreach ($lotes as $lote) {
        if ($cantidadNecesaria <= 0) break;

        $stockLote = (int)$lote['cantidad_stock'];
        $idLote = (int)$lote['id'];

        if ($stockLote >= $cantidadNecesaria) {
            $stmtUpdate = $conn->prepare("UPDATE stock SET cantidad_stock = cantidad_stock - ? WHERE id = ?");
            $stmtUpdate->bind_param('ii', $cantidadNecesaria, $idLote);
            if (!$stmtUpdate->execute()) {
                throw new Exception("Error al actualizar stock en lote ID: $idLote");
            }
            $stmtUpdate->close();
            $cantidadNecesaria = 0;
        }
    }

    if ($cantidadNecesaria > 0) {
        throw new Exception("Stock insuficiente para el producto ID: $idElaborado");
    }

    $conn->commit();
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    $conn->rollback();
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}

$conn->close();
