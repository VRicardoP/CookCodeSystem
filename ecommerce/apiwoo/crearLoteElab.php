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
    $parent_sku = $data['parent_sku']; // SKU del producto principal

    // Extraer valores de meta_data
    $meta_data = $data['meta_data'] ?? [];
    $meta_values = array_reduce($meta_data, function ($carry, $meta) {
        $carry[$meta['key']] = $meta['value'];
        return $carry;
    }, []);

    $cost_price = $meta_values['cost_price'] ?? '';
    $fecha_elaboracion = $meta_values['fecha_elaboracion'] ?? '';
    $fecha_caducidad = $meta_values['fecha_caducidad'] ?? '';
    $type_unit = $meta_values['type_unit'] ?? '';

    try {
        // Buscar el producto principal por SKU
        $products = $woocommerce->get('products', ['sku' => $parent_sku]);

        if (empty($products)) {
            echo json_encode([
                'success' => false,
                'message' => 'El producto principal con SKU ' . $parent_sku . ' no se encuentra.',
            ]);
            exit;
        }

        // Obtener el ID del producto principal
        $principal_product = reset($products);
        $parent_id = $principal_product->id;
        $parent_stock = $principal_product->stock_quantity ?? 0;

        // Buscar si el lote ya existe por SKU
        $existing_lote = $woocommerce->get('products', ['sku' => $sku]);

        if (!empty($existing_lote)) {
            // Lote ya existe, actualizar stock y metadatos
            $lote = reset($existing_lote);
            $lote_id = $lote->id;
            $current_stock = $lote->stock_quantity;

            $new_stock = $current_stock + $stock_quantity;

            $update_data = [
                'stock_quantity' => $new_stock,
                'meta_data' => [
                    ['key' => 'cost_price', 'value' => $cost_price],
                    ['key' => 'fecha_elaboracion', 'value' => $fecha_elaboracion],
                    ['key' => 'fecha_caducidad', 'value' => $fecha_caducidad],
                    ['key' => 'type_unit', 'value' => $type_unit],
                ],
            ];

            $woocommerce->put("products/$lote_id", $update_data);
        } else {

          

            // Crear nuevo lote
            $lote_data = [
                'name' => $data['name'],
                'sku' => $sku,
                'regular_price' => $data['regular_price'],
                'sale_price' => $data['sale_price'] ?? null,
                'stock_quantity' => $stock_quantity,
                'manage_stock' => true,
                'description' => $data['description'],
                'short_description' => $data['short_description'],
                'images' => [$data['image']],
                'parent_id' => $parent_id, // Usar el ID numérico del producto principal
                'meta_data' => [
                    ['key' => 'cost_price', 'value' => $cost_price],
                    ['key' => 'fecha_elaboracion', 'value' => $fecha_elaboracion],
                    ['key' => 'fecha_caducidad', 'value' => $fecha_caducidad],
                    ['key' => 'type_unit', 'value' => $type_unit],
                    ['key' => 'lote_id', 'value' => $sku],
                ],
                'categories' => [
                    ['id' => $data['categories'][0]['id']], // Asignar categoría por ID
                ],
                // Ocultar el lote de la tienda
                'catalog_visibility' => 'hidden',
            ];

            $woocommerce->post('products', $lote_data);
        }

        // Actualizar el stock del producto principal
        $new_parent_stock = $parent_stock + $stock_quantity;


        $update_parent_data = [
            'stock_quantity' => $new_parent_stock
        ];

        // Si el lote tiene un precio, actualizar el precio del producto principal
        if (!empty($data['regular_price']) && $data['regular_price'] > 0) {
            $update_parent_data['regular_price'] = $data['regular_price'];
            if (!empty($data['sale_price'])) {
                $update_parent_data['sale_price'] = $data['sale_price'];
            }
        }

        // Enviar la actualización al producto principal
        $woocommerce->put("products/$parent_id", $update_parent_data);



        echo json_encode([
            'success' => true,
            'message' => 'El lote se procesó correctamente y se actualizó el stock del producto principal.',
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error al procesar el lote.',
            'error' => $e->getMessage(),
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'No se recibieron datos válidos.',
    ]);
}
