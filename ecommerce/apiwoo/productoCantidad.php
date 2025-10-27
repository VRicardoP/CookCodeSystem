<?php
require __DIR__ . '/vendor/autoload.php';

use Automattic\WooCommerce\Client;

// Claves de la API
$consumer_key = 'ck_e116116b637f445f1d001e151c8df6f626897364';
$consumer_secret = 'cs_154cea8e9fc1568dd1b0d4fd5d86cb8ff98dae5a';
$url_base = 'http://localhost:8080/ecommerce/';

// Función para devolver cantidad sobre un producto
function cantidadProducto($id, $periodo = null) {
    $woocommerce = new Client(
        $GLOBALS['url_base'],
        $GLOBALS['consumer_key'],
        $GLOBALS['consumer_secret'],
        [
            'version' => 'wc/v3',
            'timeout' => 160, 
        ]
    );

    try {
        //Comprueba si id es un array
        if(is_array($id)){
            foreach ($id as $ids) {
                // Si no se especifica ningún período de tiempo, obtener todos los pedidos completados
                if ($periodo) {
                    // Obtener la fecha límite según el período seleccionado
                    switch ($periodo) {
                        case 'semana':
                            $fecha_limite = date('Y-m-d\TH:i:s', strtotime('-1 week'));
                            break;
                        case 'mes':
                            $fecha_limite = date('Y-m-d\TH:i:s', strtotime('-1 month'));
                            break;
                        case 'ano':
                            $fecha_limite = date('Y-m-d\TH:i:s', strtotime('-1 year'));
                            break;
                        default:
                            // Si el período no es válido, mantener los pedidos completados sin restricción de fecha
                            break;
                    }
                }
            
                //Si hay alguna fecha se filtra por la fecha sino, se busca en todos los pedidos
                if (isset($fecha_limite)) {
                    $orders = $woocommerce->get('orders', ['status' => 'completed', 'before' => $fecha_limite]);
                }else{
                    $orders = $woocommerce->get('orders', ['status' => 'completed']);
                }
                
                $total_products = 0;
                
                foreach ($orders as $order) {
                    $items = $order->line_items;
                    // Por cada producto, obtener el ID y la cantidad y sumar si coincide con el ID buscado
                    foreach ($items as $item) {
                        $product_id = $item->product_id;
                        $quantity = $item->quantity;
             
                        if ($product_id == $ids) {
                            $total_products += $quantity;
                        }
                    }
                }
               
                echo json_encode(['total' => $total_products]);
            }
        }else{
        // Si no se especifica ningún período de tiempo, obtener todos los pedidos completados
        if ($periodo) {
            // Obtener la fecha límite según el período seleccionado
            switch ($periodo) {
                case 'semana':
                    $fecha_limite = date('Y-m-d\TH:i:s', strtotime('-1 week'));
                    break;
                case 'mes':
                    $fecha_limite = date('Y-m-d\TH:i:s', strtotime('-1 month'));
                    break;
                case 'ano':
                    $fecha_limite = date('Y-m-d\TH:i:s', strtotime('-1 year'));
                    break;
                default:
                    // Si el período no es válido, mantener los pedidos completados sin restricción de fecha
                    break;
            }
        }
    
        //Si hay alguna fecha se filtra por la fecha sino, se busca en todos los pedidos
        if (isset($fecha_limite)) {
            $orders = $woocommerce->get('orders', ['status' => 'completed', 'before' => $fecha_limite]);
        }else{
            $orders = $woocommerce->get('orders', ['status' => 'completed']);
        }
        
        $total_products = 0;
        
        foreach ($orders as $order) {
            $items = $order->line_items;
            // Por cada producto, obtener el ID y la cantidad y sumar si coincide con el ID buscado
            foreach ($items as $item) {
                $product_id = $item->product_id;
                $quantity = $item->quantity;
     
                if ($product_id == $id) {
                    $total_products += $quantity;
                }
            }
        }
       
        echo json_encode(['total' => $total_products]);
    }
    } catch (\Exception $e) {
        echo "Error: " . $e->getMessage(); // Mostrar mensaje de error más informativo
    }
}


//Solo recibe peticiones POST y necesita una variable llamda id
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    
    if(isset($_POST['fecha'])){
        cantidadProducto($id, $fecha);
    }else{
        cantidadProducto($id);
    }
    
} else {
    cantidadProducto([199, 24], 'semana');
    //echo json_encode("No existe ningun producto:");
}
?>