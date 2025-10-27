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
    ]
);

// Verificar si la solicitud es POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos de productos
    $result = obtenerDatosDeProductos();

    // Devolver los datos en formato JSON
    header('Content-Type: application/json');
    echo json_encode($result);
} else {
    // Si el método no es POST
    header('Content-Type: application/json');
    echo json_encode(array('status' => 'error', 'message' => 'Método no permitido.'));
}

// Función para obtener productos y variaciones desde WooCommerce
function obtenerDatosDeProductos()
{
    global $woocommerce;

    try {
        // Obtener productos desde la API de WooCommerce
        $productos = $woocommerce->get('products', ['per_page' => 100]);

        $productos_data = array();
        
        // Recorrer los productos
        foreach ($productos as $producto) {

            // Obtener la imagen destacada del producto (si existe)
            $image_url = isset($producto->images[0]) ? $producto->images[0]->src : ''; // URL de la imagen

            // Datos del producto principal
            $cost_price = null;
            $fechaCreado = null;
            $fechaCad = null;
            if (isset($producto->meta_data)) {
                foreach ($producto->meta_data as $meta) {
                    if ($meta->key === 'cost_price') {
                        $cost_price = $meta->value;
                    }
                    if ($meta->key === 'fecha_elaboracion') {
                        $fechaCreado = $meta->value;
                    }
                    if ($meta->key === 'fecha_caducidad') {
                        $fechaCad = $meta->value;
                    }
                     if ($meta->key === 'type_unit') {
                        $typeUnit = $meta->value;
                    }
                }
            }

            // Preparar la información del producto principal
            $producto_data = array(
                'id' => $producto->id,
                'sku' => $producto->sku,
                'name' => $producto->name,
                'price' => $producto->price,
                'cost_price' => $cost_price,
                'stock_quantity' => isset($producto->stock_quantity) ? $producto->stock_quantity : null,
                'category' => isset($producto->categories) ? array_column($producto->categories, 'name') : array(),
                'date_created' => $producto->date_created,
                'date_modified' => $producto->date_modified,
                'fecha_elaboracion' => $fechaCreado,
                'fecha_caducidad' => $fechaCad,
                'image_url' => $image_url,
                'type_unit' => $typeUnit
            );

            // Si el producto tiene variaciones, obtenerlas
            if ($producto->type === 'variable') {
                $variaciones = $woocommerce->get('products/' . $producto->id . '/variations');
                $producto_data['variations'] = [];

                foreach ($variaciones as $variacion) {
                    $producto_data['variations'][] = array(
                        'id' => $variacion->id,
                        'name' => $variacion->attributes[0]->option, // Aquí se toma el atributo de la variación 
                        'price' => $variacion->price,
                        'stock_quantity' => isset($variacion->stock_quantity) ? $variacion->stock_quantity : null,
                    );
                }
            }

            // Agregar el producto (principal o con variaciones)
            $productos_data[] = $producto_data;
        }

        return array('status' => 'success', 'data' => $productos_data);
    } catch (Exception $e) {
        return array('status' => 'error', 'message' => 'Error al obtener datos de productos: ' . $e->getMessage());
    }
}
