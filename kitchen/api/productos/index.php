<?php 

require 'productos.php';

header('Content-Type: application/json; charset=utf-8'); // JSON
header("Access-Control-Allow-Origin: *");

$method = $_SERVER['REQUEST_METHOD'];
$productos = listarProductos();

if($method === 'GET'){
    if (isset($_GET['id'])) {
        $id = intval($_GET['id']);
        $producto = obtenerProductoPorId($id);

        if ($producto) {
            echo json_encode($producto, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        } else {
            echo json_encode(['error' => 'Producto no encontrado']);
        }
    } else {
        $productos = listarProductos();
        echo json_encode($productos, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
}
else if($method === 'POST'){
    $input = file_get_contents('php://input');
    $data = json_decode($input, true); // Pasa a Array asociativo

    if (isset($data['nombre'], $data['merma'], $data['empaquetado'], $data['unidad'], $data['localizacion'], $data['precio'], $data['moneda'], $data['caducidad_dias'], $data['cantidad_empaquetados'])) {
        $resultado = crearPedido($data);

        if ($resultado) {
            echo json_encode(['success' => 'Producto creado correctamente']);
        } else {
            echo json_encode(['error' => 'No se pudo crear el producto']);
        }
    } else {
        echo json_encode(['error' => 'Datos incompletos']);
    }
}
else{
    echo json_encode(['error' => 'Método no permitido']);
}
?>