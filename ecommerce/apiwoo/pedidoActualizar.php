<?php
require __DIR__ . '/vendor/autoload.php';

use Automattic\WooCommerce\Client;
define( 'WP_DEBUG', true );
// Claves de la API
$consumer_key = 'ck_e116116b637f445f1d001e151c8df6f626897364';
$consumer_secret = 'cs_154cea8e9fc1568dd1b0d4fd5d86cb8ff98dae5a';
$url_base = 'http://localhost:8080/ecommerce/';

// Función para modificar el estado de un pedido
function modificarProducto($id, $estado, $usuario) {
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
            $woocommerce->put("orders/$id", ['status' => $estado],['userMod' => $usuario]);
            echo json_encode("Estado cambiado a: ".$estado);
        }
    } catch (\Exception $e) {
        echo json_encode($e->getMessage()."No existe ningun pedido: $id");
    }
}

//Comprobacion peticion POST con envío de atributos id y estado, estado tiene que validarse
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && isset($_POST['estado']) && isset($_POST['usuario'])) {
    $id = $_POST['id'];
    $estado = isset($_POST['estado']) ? $_POST['estado'] : false;
    $usuario = $_POST['usuario'];
    $validar_estado = ['pending', 'processing', 'on-hold', 'completed', 'cancelled', 'refunded', 'failed'];
    if (in_array($estado, $validar_estado)){
        modificarProducto($id, $estado, $usuario);
    }else{
        echo json_encode("No existe ningun estado: $estado");
    }
} else {
    echo json_encode("No existe ningun pedido:");
}
?>