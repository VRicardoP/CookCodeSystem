<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");



require __DIR__ . '/vendor/autoload.php';

// Incluye el archivo wp-load.php para cargar el entorno de WordPress
require_once __DIR__ . '/../wp-load.php';  // Cambia esta ruta al directorio correcto de tu instalación de WordPress

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
    crearProductoVariable($data);
} else {
    // Responder con error si no se reciben datos válidos
    echo json_encode([
        'success' => false,
        'message' => 'No se recibieron datos válidos.'
    ]);
}

function crearProductoVariable($data)
{
    try {
        // Crear el producto variable
        $product = new WC_Product_Variable();

        // Configuración básica del producto
        $product->set_name($data['name'] ?? 'Producto sin nombre');
        $product->set_sku($data['sku'] ?? uniqid('SKU_'));
        $product->set_description($data['description'] ?? '');
        $product->set_short_description($data['short_description'] ?? '');
        $product->set_status('publish');
        $product->set_catalog_visibility('visible');

        // Categorías
        if (!empty($data['categories'])) {
            $category_ids = array_map(function ($category) {
                return $category['id'];
            }, $data['categories']);
            $product->set_category_ids($category_ids);
        }

        // Imágenes
        if (!empty($data['images']) && isset($data['images'][0]['src'])) {
            $image_id = upload_image_to_media_library($data['images'][0]['src']);
            if ($image_id) {
                $product->set_image_id($image_id);
            }
        }

        // Atributos
        $attributes = [];
        if (!empty($data['attributes'])) {
            foreach ($data['attributes'] as $attribute_data) {
                $attribute = new WC_Product_Attribute();
                $attribute->set_name($attribute_data['name']);
                $attribute->set_options($attribute_data['options']);
                $attribute->set_variation(true);  // Establecer como atributo de variación
                $attribute->set_visible(true);
                $attributes[] = $attribute;
            }
            $product->set_attributes($attributes);
        }

        // Agregar campos personalizados 

        if (!empty($data['localizacion'])) {
            $product->update_meta_data('localizacion', $data['localizacion']);
        }

        if (!empty($data['empaquetado'])) {
            $product->update_meta_data('empaquetado', $data['empaquetado']);
        }


        if (!empty($data['alergeno'])) {
            $product->update_meta_data('alergeno', $data['alergeno']);
        }

        if (!empty($data['type_unit'])) {
            $product->update_meta_data('type_unit', $data['type_unit']);
        }

        if (!empty($data['cost_price'])) {
            $product->update_meta_data('cost_price', $data['cost_price']);
        }

        if (!empty($data['peso'])) {
            $product->update_meta_data('peso', $data['peso']);
        }




        // Guardar el producto principal
        $product->save();



        // Etiquetas
        if (!empty($data['tags'])) {
            wp_set_object_terms($product->get_id(), $data['tags'], 'product_tag');
        }







        // Crear variaciones
        if (!empty($data['variations'])) {
            foreach ($data['variations'] as $variation_data) {
                $variation = new WC_Product_Variation();
                $variation->set_parent_id($product->get_id());

                // Crear el arreglo de atributos para la variación
                $variation_attributes = [];
                foreach ($variation_data['attributes'] as $attribute) {
                    // Asegúrate de que el atributo se asigne correctamente
                    $variation_attributes[sanitize_title($attribute['name'])] = $attribute['option'];
                    $variationSku = $data['sku'] . '-' . str_pad($attribute['option']*10, 3, '0', STR_PAD_LEFT);
                }

                // Asignar los atributos a la variación
                $variation->set_attributes($variation_attributes);

                // Establecer el precio y la cantidad en stock
                $variation->set_regular_price($variation_data['regular_price'] ?? 0);
                $variation->set_manage_stock(true);
                $variation->set_stock_quantity($variation_data['stock_quantity'] ?? 0);

                // **Asignar el SKU de la variación**


                $variation->set_sku($variationSku);  // El SKU será algo como ING-317-005 si stock_quantity es 5


                // Guardar la variación
                $variation->save();
            }
        }

        echo json_encode([
            'success' => true,
            'message' => 'Producto variable y variaciones creados correctamente.',
            'product_id' => $product->get_id(),
        ]);
    } catch (WC_Data_Exception $e) {
        error_log("Error al crear el producto: " . $e->getMessage());
        echo json_encode([
            'success' => false,
            'message' => 'Error al crear el producto: ' . $e->getMessage(),
        ]);
    }
}

function upload_image_to_media_library($image_url)
{
    // Usamos wp_upload_dir() de WordPress para obtener el directorio adecuado
    $upload_dir = wp_upload_dir();
    $image_data = file_get_contents($image_url);

    if (!$image_data) {
        error_log("Error: No se pudo descargar la imagen desde $image_url.");
        return null;
    }

    $filename = basename($image_url);
    $file_path = $upload_dir['path'] . '/' . $filename;

    // Guardar la imagen en el directorio de uploads
    file_put_contents($file_path, $image_data);

    // Registrar la imagen en la biblioteca de medios
    $filetype = wp_check_filetype($filename, null);
    $attachment = [
        'guid'           => $upload_dir['url'] . '/' . $filename,
        'post_mime_type' => $filetype['type'],
        'post_title'     => sanitize_file_name($filename),
        'post_content'   => '',
        'post_status'    => 'inherit',
    ];

    $attachment_id = wp_insert_attachment($attachment, $file_path);

    if (is_wp_error($attachment_id)) {
        error_log("Error: No se pudo registrar la imagen en la biblioteca de medios.");
        return null;
    }

    // Generar metadatos
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    $attach_data = wp_generate_attachment_metadata($attachment_id, $file_path);
    wp_update_attachment_metadata($attachment_id, $attach_data);

    return $attachment_id;
}
