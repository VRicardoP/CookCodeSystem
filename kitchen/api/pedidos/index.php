<?php 

require 'pedidos.php';

header('Content-Type: application/json; charset=utf-8'); // JSON
header("Access-Control-Allow-Origin: *");

$method = $_SERVER['REQUEST_METHOD'];
$pedidos = listarPedidos();

if($method === 'GET'){
    if (isset($_GET['id'])) {
        $id = intval($_GET['id']);
        $pedido = obtenerPedidoPorId($id);

        if ($pedido) {
            echo json_encode($pedido, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        } else {
            echo json_encode(['error' => 'Pedido no encontrado']);
        }
    } else {
        $pedidos = listarPedidos();
        echo json_encode($pedidos, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
}
else if($method === 'POST'){
    $input = file_get_contents('php://input');
    $data = json_decode($input, true); // Pasa a Array asociativo

    // Si viene un array de productos
    if (isset($data['proveedor_id'], $data['productos']) && is_array($data['productos'])) {
        $errores = [];
        foreach ($data['productos'] as $producto) {
            if (isset($producto['producto_id'], $producto['cantidad'], $producto['tipo_cantidad'])) {
                $pedidoData = [
                    'producto_id' => $producto['producto_id'],
                    'proveedor_id' => $data['proveedor_id'],
                    'cantidad' => $producto['cantidad'],
                    'tipo_cantidad' => $producto['tipo_cantidad'],
                    'tipo_pago' => $data['tipo_pago'] ?? '',
                    'tiempo_pago' => $data['tiempo_pago'] ?? '',
                ];
                $resultado = crearPedido($pedidoData);
                if (!$resultado) {
                    $errores[] = $producto['producto_id'];
                }
            } else {
                $errores[] = $producto['producto_id'] ?? null;
            }
        }
        if (empty($errores)) {
            echo json_encode(['success' => 'Todos los pedidos creados correctamente']);
        } else {
            echo json_encode(['error' => 'No se pudieron crear los pedidos para los productos: ' . implode(', ', $errores)]);
        }
    }
    // Si viene un solo producto (compatibilidad con el formato anterior)
    else if (isset($data['producto_id'], $data['proveedor_id'], $data['cantidad'], $data['tipo_cantidad'])) {
        $data['tipo_pago'] = $data['tipo_pago'] ?? '';
        $data['tiempo_pago'] = $data['tiempo_pago'] ?? '';
        $resultado = crearPedido($data);

        if ($resultado) {
            echo json_encode(['success' => 'Pedido creado correctamente']);
        } else {
            echo json_encode(['error' => 'No se pudo crear el pedido']);
        }
    } else {
        echo json_encode(['error' => 'Datos incompletos']);
    }
}
else{
    echo json_encode(['error' => 'Método no permitido']);
}
?>