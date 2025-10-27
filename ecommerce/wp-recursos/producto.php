<?php
require __DIR__ . '/vendor/autoload.php';

use Automattic\WooCommerce\Client;

// Claves de la API
$consumer_key = 'ck_e116116b637f445f1d001e151c8df6f626897364';
$consumer_secret = 'cs_154cea8e9fc1568dd1b0d4fd5d86cb8ff98dae5a';
$url_base = 'http://localhost:8080/ecommerce/';

$woocommerce = new Client(
    $url_base,
    $consumer_key,
    $consumer_secret,
    ['version' => 'wc/v3']
);

// ID del producto a buscar
$id_producto = 227; // Reemplaza esto con el ID del producto que deseas buscar

// Obtener el producto por su ID
$producto = $woocommerce->get("products/$id_producto");

// Verificar si se encontró el producto
if (!empty($producto)) {
    // Imprimir toda la información del producto
    echo '<pre>';
    print_r($producto);
    echo '</pre>';
} else {
    echo 'No se encontró el producto';
}
?>
