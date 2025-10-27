<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

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

// Verificar si se recibieron datos JSON en la solicitud POST
$data = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($data)) {
    $sku = $data['sku'];
    $stock_quantity = floatval($data['stock_quantity']);
   

  //  $sku_identificador = substr($sku, -3);

    $categoria = 0;
    $sku_parts = explode('-', $sku);  // Divide el SKU en partes usando el guion como delimitador
    $sku_categoria = isset($sku_parts[0]) ? $sku_parts[0] : '';  // Toma la primera parte del SKU
    $sku_identificador= isset($sku_parts[1]) ? $sku_parts[1] : ''; 
    switch ($sku_categoria) {
        case 'ING':
            $categoria = 22;
            break;
        case 'ELAB':
            $categoria = 24;
            break;
        default:
            $categoria = 0;
            break;
    }

    $principal_sku = $sku_categoria . '-' . $sku_identificador;

    // Buscar producto principal por SKU
    $principal_product = $woocommerce->get('products', ['sku' => $principal_sku]);

    if (empty($principal_product)) {
        $meta_data = $data['meta_data'];

        // Inicializar las variables
        $cost_price = '';
        $fecha_elaboracion = '';
        $fecha_caducidad = '';

        // Buscar los valores en meta_data
        foreach ($meta_data as $meta) {
            if ($meta['key'] === 'cost_price') {
                $cost_price = $meta['value'];
            }
            if ($meta['key'] === 'fecha_elaboracion') {
                $fecha_elaboracion = $meta['value'];
            }
            if ($meta['key'] === 'fecha_caducidad') {
                $fecha_caducidad = $meta['value'];
            }
            if ($meta['key'] === 'type_unit') {
                $type_unit = $meta['value'];
            }
        }

        // Crear el producto principal si no existe
        $principal_data = [
            'name' => $data['name'],
            'type' => 'simple',
            'sku' => $principal_sku,
            'regular_price' => $data['regular_price'],
            'stock_quantity' => 0,
            'description' => 'Producto principal para el lote con SKU ' . $sku,
            'short_description' => $data['short_description'],
            'images' => $data['images'],
            'manage_stock' => true,
            'stock_status' => 'instock',
            'catalog_visibility' => 'visible',
            'categories' => [
                ['id' => $categoria]
            ],
            'meta_data' => [
                [
                    'key' => 'cost_price',
                    'value' => $cost_price
                ],
                [
                    'key' => 'type_unit',
                    'value' => $type_unit
                ]
            ]
        ];

        // Crear el producto principal
        $principal_product = $woocommerce->post('products', $principal_data);
        $principal_product_id = $principal_product->id;
    } else {
        $principal_product_id = $principal_product[0]->id;
    }

    // Buscar producto de lote por SKU
    $lote_product = $woocommerce->get('products', ['sku' => $sku]);

    if (!empty($lote_product)) {
        $product_id = $lote_product[0]->id;
        $current_stock = $lote_product[0]->stock_quantity;

        $new_stock = $current_stock + $stock_quantity;

        // Actualizar el stock y el cost_price del producto de lote
        $update_data = [
            'stock_quantity' => $new_stock,
            'meta_data' => [
                [
                    'key' => 'cost_price',
                    'value' => $cost_price
                ],
                [
                    'key' => 'type_unit',
                    'value' => $type_unit
                ]
            ]
        ];

        $response = $woocommerce->put("products/$product_id", $update_data);
    } else {
        $meta_data = $data['meta_data'];

        // Inicializar las variables
        $cost_price = '';
        $fecha_elaboracion = '';
        $fecha_caducidad = '';

        foreach ($meta_data as $meta) {
            if ($meta['key'] === 'cost_price') {
                $cost_price = $meta['value'];
            }
            if ($meta['key'] === 'fecha_elaboracion') {
                $fecha_elaboracion = $meta['value'];
            }
            if ($meta['key'] === 'fecha_caducidad') {
                $fecha_caducidad = $meta['value'];
            }
        }
        // Si el lote no existe, crearlo con el stock y el cost_price
        $lote_data = array_merge($data, [
            'parent_id' => $principal_product_id,
            'catalog_visibility' => 'hidden',
            'meta_data' => [
                [
                    'key' => 'cost_price',
                    'value' => $cost_price
                ],
                [
                    'key' => 'fecha_elaboracion',
                    'value' => $fecha_elaboracion
                ],
                [
                    'key' => 'fecha_caducidad',
                    'value' => $fecha_caducidad
                ],
                [
                    'key' => 'type_unit',
                    'value' => $type_unit
                ]
            ]
        ]);

        $response = $woocommerce->post('products', $lote_data);
    }

    // Actualizar el stock del producto principal
    $lotes = $woocommerce->get('products', ['parent' => $principal_product_id]);

    $total_stock = 0;
    foreach ($lotes as $lote) {
        $total_stock += $lote->stock_quantity;
    }

    $update_principal_stock = [
        'stock_quantity' => $total_stock,
        'manage_stock' => true,
        'stock_status' => $total_stock > 0 ? 'instock' : 'outofstock'
    ];

    $woocommerce->put("products/$principal_product_id", $update_principal_stock);

    print_r($response);
}

?>
