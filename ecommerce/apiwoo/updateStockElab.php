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

// Función para obtener el valor de un metadato específico
function obtener_valor_metadato($meta_data, $key)
{
    foreach ($meta_data as $meta) {
        if (isset($meta->key) && $meta->key === $key) {
            return $meta->value;
        }
    }
    return null; // Devuelve null si no encuentra el metadato
}

// Verificar si la solicitud es POST y contiene los datos necesarios
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Leer el cuerpo de la solicitud como JSON
    $input = json_decode(file_get_contents('php://input'), true);

    // Validar que los datos requeridos estén presentes
    if (isset($input['product_id'], $input['quantity'], $input['sku'], $input['name'])) {
        $product_id = intval($input['product_id']);
        $quantity = intval($input['quantity']);
        $sku = $input['sku']; // SKU del producto principal
        $name = $input['name'];

        try {
            if ($quantity > 0) {
                // Obtener el producto principal
                $product = $woocommerce->get("products/{$product_id}");

                if (!$product) {
                    echo json_encode(['status' => 'error', 'message' => 'Producto principal no encontrado.']);
                    exit;
                }

                // Actualizar el stock del producto principal
                $current_stock = isset($product->stock_quantity) ? $product->stock_quantity : 0;
                $new_stock = max(0, $current_stock - $quantity);

                $woocommerce->put("products/{$product_id}", [
                    'stock_quantity' => $new_stock,
                    'manage_stock' => true,
                ]);

                // Buscar productos relacionados con el SKU principal
                $related_products = $woocommerce->get("products", ['per_page' => 100]);

                // Filtrar y ordenar los lotes basados en SKU y fecha de elaboración
                $lote_products = [];
                foreach ($related_products as $related) {
                    if (strpos($related->sku, $sku . '-') === 0) {
                        // Asegúrate de que el lote tenga una fecha de elaboración
                        $date_created = $related->date_created ?? null;
                        if ($date_created) {
                            $lote_products[] = [
                                'id' => $related->id,
                                'sku' => $related->sku,
                                'stock_quantity' => $related->stock_quantity,
                                'name' => $related->name,
                                'date_created' => $date_created,
                                'meta_data' => $related->meta_data,
                            ];
                        }
                    }
                }

                // Ordenar lotes por fecha de elaboración más antigua
                usort($lote_products, function ($a, $b) {
                    return strtotime($a['date_created']) - strtotime($b['date_created']);
                });

                // Descontar del lote con la fecha más antigua primero
                $remaining_quantity = $quantity;
                $updated_lotes = [];
                foreach ($lote_products as $lote) {
                    if ($remaining_quantity <= 0) break;

                    $lote_stock = $lote['stock_quantity'];
                    $deduction = min($remaining_quantity, $lote_stock);

                    // Calcular el nuevo stock
                    $new_lote_stock = max(0, $lote_stock - $deduction);
                    $remaining_quantity -= $deduction;

                    // Actualizar el stock del lote
                    $woocommerce->put("products/{$lote['id']}", [
                        'stock_quantity' => $new_lote_stock,
                        'manage_stock' => true,
                    ]);

                    // Accediendo a los metadatos del lote
                    $fecha_elaboracion = obtener_valor_metadato($lote['meta_data'], 'fecha_elaboracion');
                    $fecha_caducidad = obtener_valor_metadato($lote['meta_data'], 'fecha_caducidad');
                    $cost_price = obtener_valor_metadato($lote['meta_data'], 'cost_price');
                    $type_unit = obtener_valor_metadato($lote['meta_data'], 'type_unit');

                    // Agregar todos los detalles del lote actualizado
                    $updated_lotes[] = [
                        'id' => $lote['id'],
                        'sku' => $lote['sku'],
                        'name' => $lote['name'],
                        'new_stock' => $new_lote_stock,
                        'deducted_quantity' => $deduction, // Aquí se almacena la cantidad restada
                        'date_created' => $lote['date_created'],
                        'fecha_elaboracion' => $fecha_elaboracion,
                        'fecha_caducidad' => $fecha_caducidad,
                        'cost_price' => $cost_price,
                        'type_unit' => $type_unit,
                    ];
                }

                // Responder con los resultados
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Stock actualizado correctamente.',
                    'data' => [
                        'product' => [
                            'id' => $product_id,
                            'new_stock' => $new_stock,
                        ],
                        'lotes' => $updated_lotes, // Enviar los lotes actualizados con cantidad descontada
                    ],
                ]);
                exit;
            } else {
                echo json_encode(['status' => 'error', 'message' => 'La cantidad debe ser mayor que 0.']);
                exit;
            }
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Error al actualizar el stock: ' . $e->getMessage()]);
            exit;
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Datos inválidos o incompletos.']);
        exit;
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método no permitido.']);
    exit;
}
