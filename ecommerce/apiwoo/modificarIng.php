<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

require __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/../wp-load.php';

use Automattic\WooCommerce\Client;

$consumer_key = 'ck_e116116b637f445f1d001e151c8df6f626897364';
$consumer_secret = 'cs_154cea8e9fc1568dd1b0d4fd5d86cb8ff98dae5a';
$url_base = 'http://localhost:8080/ecommerce/';

$woocommerce = new Client(
    $url_base,
    $consumer_key,
    $consumer_secret,
    [
        'version' => 'wc/v3',
    ]
);

// Función de log para depuración que guarda en un archivo 'error_log.txt'
function log_message($message)
{
    $log_file = __DIR__ . '/error_log.txt';  // Definir la ruta del archivo de log
    $message = date('Y-m-d H:i:s') . ' - ' . $message . PHP_EOL;  // Agregar la fecha y el mensaje
    file_put_contents($log_file, $message, FILE_APPEND);  // Guardar el mensaje en el archivo log
}

// Leer los datos JSON de la solicitud
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Verificar si los datos son válidos
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($data['sku'])) {
    log_message("Datos recibidos: " . print_r($data, true));
    modificarProductoPorSKU($data);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Datos inválidos o faltantes.'
    ]);
}

function modificarProductoPorSKU($data)
{
    try {
        global $wpdb;

        $sku = sanitize_text_field($data['sku']);
        log_message("Iniciando modificación de producto con SKU: $sku");

        // Obtener el ID del producto basado en el SKU
        $product_id = $wpdb->get_var($wpdb->prepare(
            "SELECT ID FROM {$wpdb->posts} p 
             JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id 
             WHERE pm.meta_key = '_sku' AND pm.meta_value = %s 
             LIMIT 1",
            $sku
        ));

        if (!$product_id) {
            throw new Exception("Producto con SKU $sku no encontrado.");
        }

        log_message("Producto encontrado con ID: $product_id");

        $product = wc_get_product($product_id);
        if (!$product) {
            throw new Exception("Producto no encontrado.");
        }

        // Actualizar datos básicos del producto
        if (!empty($data['name'])) {
            log_message("Actualizando nombre a: " . $data['name']);
            $product->set_name($data['name']);
        }

        if (!empty($data['short_description'])) {
            log_message("Actualizando descripción a: " . $data['short_description']);
            $product->set_short_description($data['short_description']);
        }

        if (!empty($data['regular_price'])) {
            log_message("Actualizando precio regular a: " . $data['regular_price']);
            $product->set_regular_price($data['regular_price']);
            $product->update_meta_data('cost_price', $data['regular_price']);
        }

        if (!empty($data['sale_price'])) {
            log_message("Actualizando precio de oferta a: " . $data['sale_price']);
            $product->set_sale_price($data['sale_price']);
        }

        $product->save();

        // Manejo de atributos y variaciones
        if (!empty($data['attributes'])) {
            log_message("Procesando atributos y variaciones...");
            $variaciones = $data['attributes'][0]['options'];
            $attributes = [];

            $attribute = new WC_Product_Attribute();
            $attribute->set_name($data['attributes'][0]['name']);
            $attribute->set_options($variaciones);
            $attribute->set_variation(true);
            $attribute->set_visible(true);
            $attributes[] = $attribute;

            $product->set_attributes($attributes);
            $product->save();
            log_message("Atributos guardados correctamente.");

            // Obtener las variaciones actuales
            $existing_variations = $wpdb->get_col($wpdb->prepare(
                "SELECT post_id FROM {$wpdb->posts} 
                 WHERE post_parent = %d AND post_type = 'product_variation'",
                $product_id
            ));

            log_message("Variaciones existentes: " . print_r($existing_variations, true));

            $variaciones_skus = [];

            // Procesar las nuevas variaciones
            foreach ($data['variations'] as $variation_data) {
                log_message("Procesando variación: " . print_r($variation_data, true));

                $variationSku = $sku . '-' . sprintf("%03d", $variation_data['attributes'][0]['option']);
                $variaciones_skus[] = $variationSku;

                log_message("Generado SKU de variación: $variationSku");

                // Buscar variación existente o crear nueva
                $variation_id = $wpdb->get_var($wpdb->prepare(
                    "SELECT post_id FROM {$wpdb->postmeta} 
                     WHERE meta_key = '_sku' AND meta_value = %s 
                     LIMIT 1",
                    $variationSku
                ));

                if ($variation_id) {
                    $variation = wc_get_product($variation_id);
                    log_message("Variación encontrada con ID: $variation_id");
                } else {
                    $variation = new WC_Product_Variation();
                    $variation->set_parent_id($product_id);
                    $variation->set_sku($variationSku);
                    $variation->set_manage_stock(true);
                    log_message("Creada nueva variación con SKU: $variationSku");
                }

                $variation->set_attributes([
                    sanitize_title($data['attributes'][0]['name']) => $variation_data['attributes'][0]['option']
                ]);

                if (!empty($variation_data['regular_price'])) {
                    $price = number_format((float)$variation_data['regular_price'], 2, '.', '');
                    log_message("Actualizando precio regular de la variación a: $price");
                    $variation->set_regular_price($price);
                }

                if (!empty($variation_data['sale_price'])) {
                    log_message("Actualizando precio de oferta de la variación a: " . $variation_data['sale_price']);
                    $variation->set_sale_price($variation_data['sale_price']);
                }

                if (isset($variation_data['stock_quantity'])) {
                    log_message("Actualizando cantidad de stock de la variación a: " . $variation_data['stock_quantity']);
                    $variation->set_stock_quantity($variation_data['stock_quantity']);
                }

                $variation->save();
                log_message("Variación guardada con SKU: $variationSku");
            }

            $variations = $product->get_children(); // Obtener los IDs de las variaciones
            log_message("Variaciones encontradas: " . print_r($variations, true));


            // Eliminar variaciones antiguas que ya no están
            foreach ($variations as $variation_id) {
                $sku_variation = get_post_meta($variation_id, '_sku', true);
                log_message("Verificando variación: SKU $sku_variation, ID: $variation_id");

                // Verificar si el SKU de la variación no está en las nuevas variaciones
                if (!in_array($sku_variation, $variaciones_skus)) {
                    // Eliminar la variación si no está en la lista de SKUs nuevos
                    wp_delete_post($variation_id, true);
                    log_message("Variación eliminada con SKU: $sku_variation, ID: $variation_id");

                    // Limpiar caché relacionada con la variación eliminada
                    wp_cache_delete($variation_id, 'posts');  // Limpiar caché de la variación

                    // Limpiar transients del producto
                    wc_delete_product_transients($product_id);
                } else {
                    log_message("Variación NO eliminada, sigue activa con SKU: $sku_variation");
                }
            }

            // Forzar recalculo de precios y sincronización de variaciones
            $product->save();  // Guardar el producto después de eliminar las variaciones innecesarias
            WC_Product_Variable::sync($product_id);  // Forzar la sincronización de variaciones
            log_message("Sincronización de variaciones completada.");


            log_message("Transients borrados y sincronización completada.");

            // Confirmar variaciones restantes
            $variaciones_restantes = wc_get_products([
                'type' => 'variation',
                'parent_id' => $product_id,
                'limit' => -1,
                'status' => 'publish'
            ]);

            foreach ($variaciones_restantes as $v) {
                log_message("Variación final activa -> SKU: {$v->get_sku()}, Precio: {$v->get_price()}");
            }
        }

        $product->save();

        echo json_encode([
            'success' => true,
            'message' => 'Producto y variaciones modificados correctamente.',
            'product_id' => $product_id,
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error al modificar el producto: ' . $e->getMessage(),
        ]);
    }
}
