<?php
require __DIR__ . '/vendor/autoload.php';

use Automattic\WooCommerce\Client;

// Claves de la API
$consumer_key = 'ck_e116116b637f445f1d001e151c8df6f626897364';
$consumer_secret = 'cs_154cea8e9fc1568dd1b0d4fd5d86cb8ff98dae5a';
$url_base = 'http://localhost:8080/ecommerce/';

// Función para devolver información sobre un pedido
function devolverProducto($id, $status = false) {
    $woocommerce = new Client(
        $GLOBALS['url_base'],
        $GLOBALS['consumer_key'],
        $GLOBALS['consumer_secret'],
        [
            'version' => 'wc/v3',
            'timeout' => 60, 
        ]
    );

    try {
        $order = $woocommerce->get("orders/$id");

        if (isset($order)) {
            if ($status === true) {
                echo json_encode($order->status);
            } else {
                echo json_encode($order);
            }
        }
    } catch (\Exception $e) {
        echo json_encode("No existe ningun pedido: $id");
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    $status = isset($_POST['boolean']) ? $_POST['boolean'] : false;
    devolverProducto($id, $status);
} else {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['todo']) &&  $_POST['todo'] == true) {
        $woocommerce = new Client(
            $GLOBALS['url_base'],
            $GLOBALS['consumer_key'],
            $GLOBALS['consumer_secret'],
            [
                'version' => 'wc/v3',
                'timeout' => 60, 
            ]

  


        );






        
        // Enviar los pedidos en formato JSON
        echo json_encode($woocommerce->get("orders"));
    }else{
        $woocommerce = new Client(
            $GLOBALS['url_base'],
            $GLOBALS['consumer_key'],
            $GLOBALS['consumer_secret'],
            [
                'version' => 'wc/v3',
                'timeout' => 60, 
            ]
        );
        // Enviar los pedidos en formato JSON
        echo json_encode($woocommerce->get("orders"));
        //echo json_encode("No existe ningun pedido:");
    } 
}
?>
