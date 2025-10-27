<?php
require __DIR__ . '/vendor/autoload.php';

use Automattic\WooCommerce\Client;
set_time_limit(0);

// Claves de la API
$consumer_key = 'ck_e116116b637f445f1d001e151c8df6f626897364';
$consumer_secret = 'cs_154cea8e9fc1568dd1b0d4fd5d86cb8ff98dae5a';
$url_base = 'http://localhost:8080/ecommerce/';

function getProducto($filtro, $cantidad = false) {
    $woocommerce = new Client(
        'http://localhost:8080/ecommerce/',
        'ck_e116116b637f445f1d001e151c8df6f626897364',
        'cs_154cea8e9fc1568dd1b0d4fd5d86cb8ff98dae5a',
        [
            'version' => 'wc/v3',
            'timeout' => 60,
        ]
    );

    try {
        // Intenta obtener el producto por ID
        $producto = $woocommerce->get("products/$filtro");

        // Verifica si el producto tiene variaciones
        if ($producto->type === 'variable') {
            // Obtiene las variaciones
            $variaciones = $woocommerce->get("products/$filtro/variations");
            $producto->variaciones = $variaciones; // Añade las variaciones al objeto producto
        }

        // Si $cantidad es true, devuelve la cantidad en stock
        if ($cantidad === true) {
            return isset($producto->stock_quantity) ? $producto->stock_quantity : 'Stock no disponible';
        } 
        // Devuelve el producto completo
        return $producto;
    } catch (Exception $e) {
        // Maneja cualquier excepción
        return ['error' => $e->getMessage()];
    }
}

header('Content-Type: application/json');
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['filtro'])) {
    $filtro = $_POST['filtro'];
    $cantidad = filter_var($_POST['cantidad'] ?? false, FILTER_VALIDATE_BOOLEAN);
    $resultado = getProducto($filtro, $cantidad);

    if (!isset($resultado['error'])) {
        echo json_encode(['success' => true, 'data' => $resultado]);
    } else {
        echo json_encode(['success' => false, 'data' => $resultado['error']]);
    }
} else {
    // Si no es una solicitud POST o falta el parámetro 'filtro'
    echo json_encode(['success' => false, 'data' => 'Solicitud no válida']);
}
