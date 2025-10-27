<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

require __DIR__ . '/vendor/autoload.php';

// Incluye el archivo wp-load.php para cargar el entorno de WordPress
require_once __DIR__ . '/../wp-load.php';  

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
    crearProductoSimple($data);
} else {
    // Responder con error si no se reciben datos válidos
    echo json_encode([
        'success' => false,
        'message' => 'No se recibieron datos válidos.'
    ]);
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


function crearProductoSimple($data)
{

    try {
        $product = new WC_Product_Simple();

        // General info
        $product->set_name($data['name'] ?? 'Producto sin nombre');
        $product->set_sku($data['sku'] ?? uniqid('SKU_'));
        $product->set_description($data['description'] ?? '');
        $product->set_short_description($data['short_description'] ?? '');



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


        // Visibility info
        $product->set_status('publish');  // can be publish, pending, draft, etc
        $product->set_catalog_visibility('visible'); // add the product visibility status

        // Stock info
        $product->set_manage_stock(true);
       $product->set_stock_quantity(0);
       // $product->set_stock_status();

        // Price info
       // $product->set_price(100);
        $product->set_regular_price($data['regular_price']);
        $product->set_sale_price( $data['sale_price']);








        // Agregar metadatos personalizados enviados como array
if (!empty($data['meta_data']) && is_array($data['meta_data'])) {
    foreach ($data['meta_data'] as $meta) {
        if (!empty($meta['key']) && isset($meta['value'])) {
            $product->update_meta_data($meta['key'], $meta['value']);
        }
    }
}


// Guardar los datos recibidos en un archivo log
guardar_log("Datos recibidos: " . print_r($data, true));
        // Taxonomy info
       // $product->set_category_ids([280]); // array of category ids, 15 is cat id
       // $product->set_tag_ids([302, 304]); // array of tag ids

        // Image info
      //  $product->set_image_id(57); // image id from media library

        // Dimension info
    //    $product->set_height(10);
      //  $product->set_length(10);
       // $product->set_width(10);
       // $product->set_weight(1.2);

        // Attributes info
      //  $brand = new WC_Product_Attribute();
       // $brand->set_name('Marca');
       // $brand->set_options(['Lacoste', 'Tommy Hilfiger']);
       // $brand->set_position(1);
       // $brand->set_variation(false);
       // $brand->set_visible(true);

       // $product->set_attributes([$brand]);

        // Save product
        $product->save();


        echo json_encode([
            'success' => true,
            'message' => 'Producto simple creado correctamente.',
            'product_id' => $product->get_id(),
        ]);


    } catch (WC_Data_Exception $e) {
        error_log(print_r($e->getMessage(), true));
    }
}


function guardar_log($mensaje) {
    $log_file = __DIR__ . '/debug_log.txt'; // Archivo donde se guardará el log
    $fecha = date('Y-m-d H:i:s');
    file_put_contents($log_file, "[$fecha] $mensaje" . PHP_EOL, FILE_APPEND);
}

