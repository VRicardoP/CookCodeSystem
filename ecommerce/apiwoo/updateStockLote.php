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
// Función para obtener la variación de un producto
function obtener_variacion_producto($woocommerce, $product_id, $variation_id)
{
    try {
        $response = $woocommerce->get("products/{$product_id}/variations/{$variation_id}");
        if (!$response) {
            error_log("Error al obtener la variación: Producto ID {$product_id}, Variación ID {$variation_id}");
            return null;
        }
        return (array) $response; // Convertir a array
    } catch (Exception $e) {
        error_log("Error al obtener la variación: " . $e->getMessage());
        return null;
    }
}

// Función para actualizar el stock de la variación
function actualizar_stock_variacion($woocommerce, $product_id, $variation_id, $new_stock)
{
    $data = [
        'stock_quantity' => $new_stock,
        'manage_stock' => true, // Asegurarse de que el stock se gestione
    ];
    try {
        $response = $woocommerce->put("products/{$product_id}/variations/{$variation_id}", $data);
        return $response;
    } catch (Exception $e) {
        error_log("Error al actualizar el stock: " . $e->getMessage());
        return null;
    }
}

// Función para sanitizar el SKU y eliminar caracteres no deseados
function sanitizar_sku($sku_prefix)
{
    return preg_replace('/[^a-zA-Z0-9\-_]/', '', $sku_prefix);
}

// Función para buscar lotes por nombre del producto
function obtener_lotes_por_nombre($woocommerce, $numeroSku, $categoria_id, $variationName)
{
    try {
        // Sanitizar el nombre del producto para eliminar caracteres no deseados


        $matching_lots = [];
        $page = 1;
        $per_page = 100; // Número de productos por página

        // Filtrar productos por categoría y nombre de manera más eficiente
        while (true) {
            // Consultar productos de la categoría con paginación
            $products = $woocommerce->get("products", [
                'category' => $categoria_id, // Filtrar por categoría
                'page' => $page,
                'per_page' => $per_page,
            ]);

            // Si no hay más productos, detener el bucle
            if (empty($products)) {
                break;
            }

            // Filtrar los productos cuyo nombre contenga el nombre del producto principal
            foreach ($products as $product) {
               

                // Verificar si el número extraído está presente en el nombre del producto
                if ($numeroSku !== null && isset($product->name) && strpos(strtolower($product->name), strtolower($numeroSku)) !== false) {
                    $matching_lots[] = $product;
                }
            }

            $page++; // Incrementa la página para la siguiente iteración
        }

        return $matching_lots;
    } catch (Exception $e) {
        error_log("Error al buscar lotes con nombre: " . $e->getMessage());
        return [];
    }
}

// Verificar si la solicitud es POST y si se recibieron datos
// Verificar si la solicitud es POST y si se recibieron datos
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['products'])) {
    $products = json_decode($_POST['products'], true); // Obtener los productos del cuerpo de la solicitud
    $response_data = [];

    try {
        foreach ($products as $product) {
            $product_id = $product['product_id'];
            $variation_id = $product['variation_id'];
            $quantity = $product['quantity'];
            $skuProductoPrincipal = $product['sku'];
            $variationName = $product['variationName'] *10;
            $name =  $product['name'];
            $formattedVariationName = str_pad($variationName, 3, '0', STR_PAD_LEFT);



            $partes = explode('-', $skuProductoPrincipal);

            // Obtén la segunda parte
            if (isset($partes[1])) {
                $numeroSku = $partes[1];
            }
            if ($quantity > 0) {
                // Obtener los datos actuales de la variación (incluido el stock actual)
                $current_variation = obtener_variacion_producto($woocommerce, $product_id, $variation_id);

                if (!$current_variation) {
                    $response_data[] = [
                        'status' => 'error',
                        'message' => 'No se pudo obtener la variación del producto.',
                        'product_id' => $product_id,
                        'variation_id' => $variation_id,
                    ];
                    continue;
                }

                // Calcular el nuevo stock
                $current_stock = isset($current_variation['stock_quantity']) ? $current_variation['stock_quantity'] : 0;
                $new_stock = $current_stock - $quantity;
                if ($new_stock < 0) {
                    $new_stock = 0;
                }

                // Actualizar el stock de la variación
                $update_response = actualizar_stock_variacion($woocommerce, $product_id, $variation_id, $new_stock);
                if (!$update_response) {
                    $response_data[] = [
                        'status' => 'error',
                        'message' => 'Error al actualizar el stock de la variación.',
                        'product_id' => $product_id,
                        'variation_id' => $variation_id,
                    ];
                    continue;
                }

                $response_data[] = [
                    'status' => 'success',
                    'message' => 'Stock de la variación actualizado correctamente',
                    'product_id' => $product_id,
                    'variation_id' => $variation_id,
                    'new_stock' => $new_stock,
                ];

                // Obtener lotes
                $sku_lote = $skuProductoPrincipal . "-" . $formattedVariationName . "-L";
                $all_lots = obtener_lotes_por_nombre($woocommerce, $numeroSku, 25, $variationName);

                // Arreglo para almacenar los lotes descontados
                $discounter_lots = [];

                if (empty($all_lots)) {
                    $response_data[] = [
                        'status' => 'error',
                        'message' => 'No se encontraron lotes con el nombre: ' . $sku_lote,
                        'product_id' => $product_id,
                        'variation_id' => $variation_id,
                    ];
                } else {
                    // Ordenar los lotes por fecha de creación (metadatos)
                    usort($all_lots, function ($a, $b) {
                        return strtotime($a->meta_data[0]->value) - strtotime($b->meta_data[0]->value);
                    });

                    $remaining_quantity = $quantity;

                    foreach ($all_lots as $lot) {
                        // Comprobar si el SKU del lote contiene el variationName
                        if (strpos($lot->sku, $formattedVariationName) !== false) {
                            if ($remaining_quantity <= 0) break;

                            // Acceder a las propiedades usando la notación de flecha -> en lugar de corchetes []
                            $lot_stock = $lot->stock_quantity ?? 0;
                            $deduction = min($remaining_quantity, $lot_stock);
                            $new_lot_stock = $lot_stock - $deduction;
                            $remaining_quantity -= $deduction;

                            // Accediendo a los metadatos del lote
                            $fecha_elaboracion = obtener_valor_metadato($lot->meta_data, 'fecha_elaboracion');
                            $fecha_caducidad = obtener_valor_metadato($lot->meta_data, 'fecha_caducidad');
                            $cost_price = obtener_valor_metadato($lot->meta_data, 'cost_price');
                            $type_unit = obtener_valor_metadato($lot->meta_data, 'type_unit');
                            // Actualizar el stock del lote
                            $woocommerce->put("products/{$lot->id}", ['stock_quantity' => $new_lot_stock]);

                            // Almacenar los detalles del lote descontado
                            $discounter_lots[] = [
                                'lot_id' => $lot->id,
                                'lot_sku' => $lot->sku,
                                'lot_stock_before' => $lot_stock,
                                'deducted_quantity' => $deduction,
                                'lot_stock_after' => $new_lot_stock,
                                'fecha_elaboracion' => $fecha_elaboracion,
                                'fecha_caducidad' => $fecha_caducidad,
                                'cost_price' => $cost_price,
                                'type_unit' => $type_unit
                            ];
                        }
                    }

                    // Si aún queda cantidad por deducir
                    if ($remaining_quantity > 0) {
                        $response_data[] = [
                            'status' => 'error',
                            'message' => 'No hay suficiente stock en los lotes con el variationName para completar la cantidad solicitada',
                            'product_id' => $product_id,
                            'variation_id' => $variation_id,
                        ];
                    }
                }

                // Agregar los lotes descontados al mensaje final
                $response_data[] = [
                    'status' => 'success',
                    'message' => 'Stock descontado de los lotes',
                    'discounter_lots' => $discounter_lots
                ];
            } else {
                $response_data[] = [
                    'status' => 'error',
                    'message' => 'La cantidad debe ser mayor que 0',
                    'product_id' => $product_id,
                    'variation_id' => $variation_id,
                ];
            }
        }

        echo json_encode(['status' => 'success', 'message' => 'Stock actualizado correctamente', 'data' => $response_data]);
    } catch (Exception $e) {
        error_log("Error al procesar la solicitud: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Error al actualizar el stock: ' . $e->getMessage()]);
    }
} else {
    error_log("Datos inválidos o método incorrecto");
    echo json_encode(['status' => 'error', 'message' => 'Método o datos inválidos']);
}
