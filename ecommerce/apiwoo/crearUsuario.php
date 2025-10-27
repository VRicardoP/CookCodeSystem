<?php
require __DIR__ . '/vendor/autoload.php'; 

use Automattic\WooCommerce\Client;

$consumer_key = 'ck_e116116b637f445f1d001e151c8df6f626897364';
$consumer_secret = 'cs_154cea8e9fc1568dd1b0d4fd5d86cb8ff98dae5a';
$url_base = 'http://localhost:8080/ecommerce/';

// Configuración de WooCommerce
$woocommerce = new Client(
    $url_base,
    $consumer_key,
    $consumer_secret,
    [
        'version' => 'wc/v3',
        'timeout' => 160, 
    ]
);



// Verificar si se recibieron datos y son válidos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Crear un array con los datos del usuario a registrar
    $user_data = array(
        'email' => $_POST['email'],
        'first_name' => $_POST['first_name'],
        'last_name' => $_POST['last_name'],
        'username' => $_POST['username'],
        'password' => $_POST['billing']['city'],
        'billing' => array(
            'first_name' => $_POST['billing']['first_name'],
            'last_name' => $_POST['billing']['last_name'],
            'address_1' => $_POST['billing']['address_1'],
            'city' => $_POST['billing']['city'],
            'postcode' => $_POST['billing']['postcode'],
            'country' => $_POST['billing']['country'],
            'state' => $_POST['billing']['state']
        ),
        'shipping' => array(
            'first_name' => $_POST['shipping']['first_name'],
            'last_name' => $_POST['shipping']['last_name'],
            'address_1' => $_POST['shipping']['address_1'],
            'city' => $_POST['shipping']['city'],
            'postcode' => $_POST['shipping']['postcode'],
            'country' => $_POST['shipping']['country'],
            'state' => $_POST['shipping']['state']
        )
    );

    // Registrar el usuario a través de la API de WooCommerce
    $response = $woocommerce->post('customers', $user_data);

    // Devolver la respuesta al cliente
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    // Devolver un mensaje de error si no se recibieron datos válidos
    echo json_encode(array('message' => 'No se recibieron datos validos.'));
}
?>
