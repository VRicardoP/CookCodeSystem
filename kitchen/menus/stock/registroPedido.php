<?php

require __DIR__ . '/../../models/almacenElaboraciones.php';
require_once __DIR__ . '/../../models/almacenElaboracionesDao.php';
require_once __DIR__ . '/../../DBConnection.php';

header('Content-Type: application/json');

$response = [
    'error' => false,
    'message' => '',
    'success' => ''
];

try {
    $conn = DBConnection::connectDB();
    if (!$conn) {
        throw new Exception("No se pudo establecer la conexión a la base de datos.");
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $id = $_POST['idPedido'] ?? '';
        $estado = $_POST['estado'] ?? '';

        if (empty($id) || empty($estado)) {
            throw new Exception("ID del pedido o estado no proporcionado.");
        }

        $estadoEnvio = '';
        if ($estado === "Registered") {
            $estadoEnvio = "Sent";
        } elseif ($estado === "Sent") {
            $estadoEnvio = "Received";
        } else {
            throw new Exception("Estado inválido proporcionado.");
        }

        // Verificar si el pedido existe
        $stmtCheck = $conn->prepare("SELECT COUNT(*) FROM pedidos_ecommerce WHERE id = ?");
        $stmtCheck->execute([$id]);
        $pedidoExiste = $stmtCheck->fetchColumn();

        if ($pedidoExiste) {
            // Actualizar el estado del pedido
            $stmtUpdate = $conn->prepare("UPDATE pedidos_ecommerce SET estado_envio = ? WHERE id = ?");
            if ($stmtUpdate->execute([$estadoEnvio, $id])) {
                $response['success'] = "El estado del pedido ha sido actualizado exitosamente.";
            } else {
                throw new Exception("Error al actualizar el estado del pedido.");
            }
        } else {
            http_response_code(404);
            throw new Exception("No se encontró el pedido con el ID proporcionado.");
        }

    } else {
        http_response_code(405);
        throw new Exception("Método no permitido. Usa POST.");
    }

} catch (Exception $e) {
    $response['error'] = true;
    $response['message'] = $e->getMessage();
    if (!http_response_code()) {
        http_response_code(500);
    }
}

echo json_encode($response);
exit;
