<?php

require '../../models/ingredientesDao.php';
require 'crearEtiqueta.php';

function listarPedidos(){
    $query = "SELECT * FROM `pedido_producto`";
    $link = mysqli_connect('localhost', 'root', '', 'kitchentag');
    $resultPedidos = $link->query($query);

    $pedidos = array();

    // Agrega cada pedido al array $pedidos
    while ($row = $resultPedidos->fetch_assoc()){
        $producto = IngredientesDao::select($row['producto_id']);
        
        $pedidos[] = array(
            'id' => $row['id'],
            'producto_id' => $row['producto_id'],
            // 'producto' => $producto->getFName(),
            'proveedor_id' => $row['proveedor_id'],
            'cantidad' => $row['cantidad'],
            'tipo_cantidad' => $row['tipo_cantidad'],
            'tipo_pago' => $row['tipo_pago'],
            'tiempo_pago' => $row['tiempo_pago'],
        );
    }

    return $pedidos;
}

function crearPedido($data) {
    crearEtiqueta($data['producto_id'], $data['cantidad'], $data['tipo_cantidad']);

    $query = "INSERT INTO `pedido_producto` (producto_id, proveedor_id, cantidad, tipo_cantidad, tipo_pago, tiempo_pago)
    VALUES (?, ?, ?, ?, ?, ?)";

    $link = mysqli_connect('localhost', 'root', '', 'kitchentag');
    $stmt = $link->prepare($query);
    $stmt->bind_param(
        'iiiiss', 
        $data['producto_id'], 
        $data['proveedor_id'], 
        $data['cantidad'],
        $data['tipo_cantidad'],
        $data['tipo_pago'],
        $data['tiempo_pago']
    );

    $resultado = $stmt->execute();
    $stmt->close();
    $link->close();

    return $resultado;
}

function obtenerPedidoPorId($id) {
    $link = mysqli_connect('localhost', 'root', '', 'kitchentag');
    $stmt = $link->prepare("SELECT * FROM `pedido_producto` WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    $pedido = null;
    if ($row = $result->fetch_assoc()) {
        $producto = IngredientesDao::select($row['producto_id']);
        
        $pedido = array(
            'id' => $row['id'],
            'producto_id' => $row['producto_id'],
            'producto' => $producto->getFName(),
            'proveedor_id' => $row['proveedor_id'],
            'cantidad' => $row['cantidad'],
            'tipo_cantidad' => $row['tipo_cantidad'],
        );
    }

    $stmt->close();
    $link->close();

    return $pedido;
}

?>