<?php

require_once(ABSPATH . 'apiwoo/vendor/autoload.php');
//Function to apply styles from style.css
function custom_styles()
{
    wp_enqueue_style('custom-admin-styles', get_stylesheet_directory_uri() . '/style.css');
}

//Call custom styles
add_action('admin_enqueue_scripts', 'custom_styles');




function cookcode_custom_script($nombre_script)
{
    wp_register_script($nombre_script, get_stylesheet_directory_uri() . '/js/' . $nombre_script . '.js', array('jquery'), filemtime(get_stylesheet_directory() . '/js/' . $nombre_script . '.js'), true);

    wp_enqueue_script($nombre_script);
}

// Enqueue script 'adminmenu.js' on admin menu 
add_action('admin_enqueue_scripts', function () {
    cookcode_custom_script('adminmenu');
});




//add cart button shop page
add_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 20);

add_filter('woocommerce_product_add_to_cart_text', 'cambiar_texto_boton_agotado', 10, 2);


function cambiar_texto_boton_agotado($text, $product)
{
    if (!$product->is_in_stock()) {
        return __('Read more', 'tu-dominio');
    }
    return $text;
}

add_action('woocommerce_widget_shopping_cart_buttons', 'custom_view_cart_button');

function custom_view_cart_button()
{
    echo '<a href="' . esc_url(wc_get_cart_url()) . '" class="button wc-forward">' . __('View Cart', 'woocommerce') . '</a>';
}

// Enqueue script 'manejadorEstilos.js' on webpage 
add_action('wp_enqueue_scripts', function () {
    cookcode_custom_script('manejadorEstilos');
});


//update item of the cart
function update_cart_item_ajax_handler()
{

    if (isset($_POST['cart_item_key']) && !empty($_POST['cart_item_key']) && isset($_POST['quantity']) && !empty($_POST['quantity'])) {
        $cart_item_key = sanitize_text_field($_POST['cart_item_key']);
        $quantity = intval($_POST['quantity']);

        WC()->cart->set_quantity($cart_item_key, $quantity, true);

        wp_send_json_success(array('message' => 'Cantidad actualizada con éxito'));
    } else {
        wp_send_json_error(array('message' => 'Datos insuficientes'));
    }

    wp_die();
}

add_action('wp_ajax_update_cart_item', 'update_cart_item_ajax_handler');
add_action('wp_ajax_nopriv_update_cart_item', 'update_cart_item_ajax_handler');

//remove item of the cart
function remove_from_cart_ajax_handler()
{
    // Verificar el nonce (por hacer: hay que activarlo)
    // if (!wp_verify_nonce($_POST['nonce'], 'remove-cart-nonce')) {
    //    wp_send_json_error(array('message' => 'Nonce verification failed'));
    //    wp_die();
    //}

    if (isset($_POST['cart_item_key']) && !empty($_POST['cart_item_key'])) {
        $cart_item_key = sanitize_text_field($_POST['cart_item_key']);

        WC()->cart->remove_cart_item($cart_item_key);

        wp_send_json_success(array('message' => 'Producto eliminado del carrito con éxito'));
    } else {
        wp_send_json_error(array('message' => 'Datos insuficientes para eliminar el producto'));
    }

    wp_die();
}


add_action('wp_ajax_remove_from_cart', 'remove_from_cart_ajax_handler');
add_action('wp_ajax_nopriv_remove_from_cart', 'remove_from_cart_ajax_handler');

//order by a-z

add_filter('woocommerce_catalog_orderby', 'custom_woocommerce_catalog_orderby');

function custom_woocommerce_catalog_orderby($sortby)
{

    $sortby['menu_order'] = 'Sort by name: a-z';

    return $sortby;
}

//remove rating order
add_filter('woocommerce_catalog_orderby', 'custom_remove_orderby_options');

function custom_remove_orderby_options($orderby)
{
    unset($orderby['rating']);
    return $orderby;
}

//Login:
function autologin()
{
    $username = $_POST['username'];
    $password = $_POST['password'];
    $creds = array(
        'user_login'    => $username,
        'user_password' => $password,
        'remember'      => true
    );

    $user = wp_signon($creds, false);

    if (is_wp_error($user)) {
        wp_send_json_error(array('message' => $user->get_error_message()));
    } else {
        wp_send_json_success(array('redirect_url' => admin_url()));
    }

    die();
}

add_action('wp_ajax_nopriv_custom_login', 'autologin');

function check_if_product_exists_and_return_sku($post_id)
{
    // Obtener el producto
    $product = wc_get_product($post_id);

    if ($product) {
        // Obtener el SKU del producto
        $sku = $product->get_sku();
        // Comprobar si el SKU existe
        if ($sku) {
            // Si existe, devolver el SKU
            return $sku;
        } else {
            // Si no existe, verificar si el nombre del producto existe en la base de datos
            $name = $product->get_name();
            $existing_product = wc_get_product_id_by_sku($name);
            if ($existing_product) {
                // Si existe, devolver el SKU del producto existente
                $existing_product_sku = get_post_meta($existing_product, '_sku', true);
                return $existing_product_sku;
            }
        }
    }
    // Si no existe, continuar con el proceso de guardado
    return false;
}



//add_action('woocommerce_product_options_general_product_data', 'add_custom_date_fields');
// Agregar campos de fecha al producto
add_action('woocommerce_product_options_general_product_data', 'add_custom_date_fields');
function add_custom_date_fields()
{
    global $post;
    // ID del producto basado en el objeto $post
    $product_id = $post->ID;
    // Obtener los valores actuales de las fechas desde los metadatos
    $fecha_elab = get_post_meta($product_id, 'fecha_elaboracion', true);
    $fecha_cad = get_post_meta($product_id, 'fecha_caducidad', true);
    $cost_price = get_post_meta($product_id, 'cost_price', true);
    $type_unit = get_post_meta($product_id, 'type_unit', true);

    // Verificar si la fecha de elaboración está bien almacenada y convertirla correctamente
    if (!empty($fecha_elab) && strtotime($fecha_elab)) {
        $fecha_fabricacion = date('Y-m-d', strtotime($fecha_elab));
    } else {
        $fecha_fabricacion = ''; // Si no se guarda correctamente, deja el campo vacío
    }

    // Verificar si la fecha de caducidad está bien almacenada y convertirla correctamente
    if (!empty($fecha_cad) && strtotime($fecha_cad)) {
        $fecha_caducidad = date('Y-m-d', strtotime($fecha_cad));
    } else {
        $fecha_caducidad = ''; // Si no se guarda correctamente, deja el campo vacío
    }



    // Campo para la fecha de elaboración
    woocommerce_wp_text_input(array(
        'id' => 'fecha_elab',
        'label' => __('Fecha de Elaboración', 'woocommerce'),
        'placeholder' => 'YYYY-MM-DD',
        'desc_tip' => 'true',
        'description' => __('Ingrese la fecha de elaboración.', 'woocommerce'),
        'type' => 'date',
        'value' => $fecha_fabricacion // Cargar el valor actual
    ));

    // Campo para la fecha de caducidad
    woocommerce_wp_text_input(array(
        'id' => 'fecha_cad',
        'label' => __('Fecha de Caducidad', 'woocommerce'),
        'placeholder' => 'YYYY-MM-DD',
        'desc_tip' => 'true',
        'description' => __('Ingrese la fecha de caducidad.', 'woocommerce'),
        'type' => 'date',
        'value' => $fecha_caducidad // Cargar el valor actual
    ));




    woocommerce_wp_text_input(array(
        'id' => 'cost_price',
        'label' => __('Precio de Coste', 'woocommerce'),
        'placeholder' => '0.00',
        'desc_tip' => 'true',
        'description' => __('Ingrese el precio de coste del producto.', 'woocommerce'),
        'type' => 'number',
        'step' => '0.01',
        'value' => $cost_price // Cargar el valor actual
    ));


    woocommerce_wp_text_input(array(
        'id' => 'type_unit',
        'label' => __('Tipo de unidad', 'woocommerce'),
        'placeholder' => '',
        'desc_tip' => 'true',
        'description' => __('Ingrese el tipo de unidad del producto.', 'woocommerce'),
        'type' => 'text',
        'value' => $type_unit // Cargar el valor actual
    ));
}

// Guardar campos de fecha personalizados
add_action('woocommerce_process_product_meta', 'save_custom_date_fields');
function save_custom_date_fields($post_id)
{
    // Guardar fecha de elaboración
    if (isset($_POST['fecha_elab'])) {
        update_post_meta($post_id, 'fecha_elaboracion', sanitize_text_field($_POST['fecha_elab']));
    }

    // Guardar fecha de caducidad
    if (isset($_POST['fecha_cad'])) {
        update_post_meta($post_id, 'fecha_caducidad', sanitize_text_field($_POST['fecha_cad']));
    }
}



add_action('woocommerce_process_product_meta', 'guardar_campo_tipo_unidad_producto');

function guardar_campo_tipo_unidad_producto($post_id)
{
    // Verificar si el valor ha sido enviado y guardarlo como metadata
    $type_unit = isset($_POST['type_unit']) ? sanitize_text_field($_POST['type_unit']) : '';
    update_post_meta($post_id, 'type_unit', $type_unit);
}



// Guardar el campo de precio de coste
add_action('woocommerce_process_product_meta', 'save_custom_cost_price_field');
function save_custom_cost_price_field($post_id)
{
    // Guardar el precio de coste
    if (isset($_POST['cost_price'])) {
        update_post_meta($post_id, 'cost_price', sanitize_text_field($_POST['cost_price']));
    }
}



// Mostrar fechas en la página de producto
function mostrar_fechas_producto()
{
    global $product;

    // Obtener el ID del producto
    $product_id = $product->get_id();

    // Buscar las fechas por ID del producto
    $fecha_fabricacion = get_post_meta($product_id, 'fecha_elaboracion', true);
    $fecha_caducidad = get_post_meta($product_id, 'fecha_caducidad', true);

    // Verificar y formatear las fechas
    if ($fecha_fabricacion) {
        $fecha_fabricacion = date('d-m-Y', strtotime($fecha_fabricacion)); // Convertir a DD-MM-YYYY
    }
    if ($fecha_caducidad) {
        $fecha_caducidad = date('d-m-Y', strtotime($fecha_caducidad)); // Convertir a DD-MM-YYYY
    }
/*
    if ($fecha_fabricacion || $fecha_caducidad) {
        echo '<div class="producto-fechas">';
        if ($fecha_fabricacion) {
            echo '<p><strong>Fecha de Fabricación:</strong> ' . esc_html($fecha_fabricacion) . '</p>';
        }
        if ($fecha_caducidad) {
            echo '<p><strong>Fecha de Caducidad:</strong> ' . esc_html($fecha_caducidad) . '</p>';
        }
        echo '</div>';
    } */
}
add_action('woocommerce_single_product_summary', 'mostrar_fechas_producto', 20);









// Mostrar precio de coste y fechas en la página del producto
add_action('woocommerce_single_product_summary', 'mostrar_precio_coste_fechas_producto', 20);
function mostrar_precio_coste_fechas_producto()
{
    global $product;

    $product_id = $product->get_id();
    $cost_price = get_post_meta($product_id, 'cost_price', true);
    $fecha_fabricacion = get_post_meta($product_id, 'fecha_elaboracion', true);
    $fecha_caducidad = get_post_meta($product_id, 'fecha_caducidad', true);
    $type_unit = get_post_meta($product_id, 'type_unit', true);

    // Verificar y formatear las fechas
    if ($fecha_fabricacion) {
        $fecha_fabricacion = date('d-m-Y', strtotime($fecha_fabricacion)); // Convertir a DD-MM-YYYY
    }
    if ($fecha_caducidad) {
        $fecha_caducidad = date('d-m-Y', strtotime($fecha_caducidad)); // Convertir a DD-MM-YYYY
    }

   
}

add_action('woocommerce_single_product_summary', 'mostrar_campo_tipo_unidad_producto', 25);

function mostrar_campo_tipo_unidad_producto()
{
    global $post;

    // Obtener el valor guardado del campo personalizado
    $type_unit = get_post_meta($post->ID, 'type_unit', true);

}






// Función para enviar datos de productos a procesar_datos.php
function enviar_datos_productos()
{
    // Obtener todos los productos
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => -1,
    );
    $productos = get_posts($args);

    // Crear array para almacenar los datos de los productos
    $data_productos = array();

    foreach ($productos as $producto_post) {
        $product = wc_get_product($producto_post->ID);

        // Obtener datos del producto
        $data_productos[] = array(
            'id' => $product->get_id(),
            'name' => $product->get_name(),
            'sku' => $product->get_sku(),
            'price' => $product->get_price(),
            'stock_quantity' => $product->get_stock_quantity(),
            'fecha_elaboracion' => get_post_meta($product->get_id(), 'fecha_elaboracion', true),
            'fecha_caducidad' => get_post_meta($product->get_id(), 'fecha_caducidad', true),
        );
    }

    // URL del archivo procesar_datos.php (ajusta la ruta según tu entorno)
    $url = 'http://localhost:8080/kitchen/dashboard/procesar_datos.php';

    // Configurar la solicitud POST
    $response = wp_remote_post($url, array(
        'method'    => 'POST',
        'body'      => array('productos' => json_encode($data_productos)),
        'headers'   => array(
            'Content-Type' => 'application/x-www-form-urlencoded',
        ),
    ));

    // Verificar la respuesta
    if (is_wp_error($response)) {
        error_log('Error al enviar datos a procesar_datos.php: ' . $response->get_error_message());
    } else {
        error_log('Datos enviados correctamente a procesar_datos.php');
    }
}

// Activar el envío de datos cada vez que un producto es creado o actualizado
add_action('save_post_product', 'enviar_datos_productos', 10, 1);

// Activar el envío de datos al cargar el sitio
add_action('init', 'enviar_datos_productos');


// 1. Detectar cuando se procesa un pedido
add_action('woocommerce_order_status_processing', 'procesar_pedido_con_lotes', 10, 1);

function procesar_pedido_con_lotes($order_id)
{
    $order = wc_get_order($order_id);

    foreach ($order->get_items() as $item_id => $item) {
        $product_id = $item['product_id'];
        $product = wc_get_product($product_id);
        $product_sku = $product->get_sku();

        // Extraer prefijo (sin el peso) y sufijo del SKU
        $sku_parts = explode('-', $product_sku);
        $sku_base = implode('-', array_slice($sku_parts, 0, 3)); // Ej: ING-338
        $sku_weight = isset($sku_parts[2]) ? $sku_parts[2] : ''; // Peso (Ej: 001, 010) (si existe)

        // Verificar si el producto es "Elaborado"
        if (has_term('elaborado', 'product_cat', $product_id)) {
            // Productos "Elaborados" no usan el peso
            $cantidad_pedida = $item->get_quantity();
            $lotes = obtener_lotes_disponibles('lotes-de-elaborados', $sku_base); // Usamos solo la base del SKU
            $lotes_utilizados = descontar_stock_lotes($lotes, $cantidad_pedida);
            $informacion_lote = formatear_informacion_lotes($lotes_utilizados);
            wc_add_order_item_meta($item_id, '_informacion_lote', $informacion_lote);
        }
        // Caso para los ingredientes (productos variables)
        elseif (has_term('ingredientes', 'product_cat', $product_id)) {
            $cantidad_pedida = $item->get_quantity();
            $sku_buscar = $sku_base . '-' . str_pad($sku_weight, 3, '0', STR_PAD_LEFT);

         
            // Si es un producto variable, buscar variación
            $variation_id = $item['variation_id'];
            if ($variation_id) {
                $variation_product = wc_get_product($variation_id);
                $variation_sku = $variation_product->get_sku();
                log_sku_debug("Sku ing de variacion que busca: " . $variation_sku);
                // Buscar lotes que coincidan con la base + peso específico de la variación
                $lotes = obtener_lotes_disponibles_ingredientes('lotes-de-ingredientes', $variation_sku);
                $lotes_utilizados = descontar_stock_lotes($lotes, $cantidad_pedida);
                $informacion_lote = formatear_informacion_lotes($lotes_utilizados);
                wc_add_order_item_meta($item_id, '_informacion_lote', $informacion_lote);
            }
        }
    }
}
// Función específica para ingredientes que filtra según peso correcto
function obtener_lotes_disponibles_ingredientes($categoria_slug, $sku_base)
{
    log_sku_debug("Buscando lotes para SKU base: " . $sku_base);
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => -1,
        'tax_query' => array(
            array(
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => $categoria_slug,
            ),
        ),
    );

    $query = new WP_Query($args);
    $lotes = array();

    foreach ($query->posts as $post) {
        $product = wc_get_product($post->ID);
        $product_sku = $product->get_sku(); // Obtener el SKU del lote

        // Obtener el peso del producto
        $product_weight = $product->get_weight(); // Peso del producto (puede ser null o vacío)

        // Si hay peso, construir el SKU buscando el prefijo + peso (como ING-338-001)
        if ($product_weight) {
            // Suponiendo que el SKU sigue el formato "ING-338-001-L397"
            $sku_correcto = $sku_base . '-' . str_pad($product_weight, 3, '0', STR_PAD_LEFT);  // Ajustamos el peso al formato 3 dígitos
            log_sku_debug("Buscando lote con SKU correcto: " . $sku_correcto);

            // Comparamos el SKU del lote con el SKU generado
            if ($product_sku === $sku_correcto) {
                log_sku_debug('Lote encontrado - SKU: ' . $product_sku);
                $lotes[] = $product; // Agregar el lote a la lista si coincide
            }
        } else {
            // Si no hay peso, podría buscar con el SKU base (aunque esto dependería de cómo gestionas los lotes sin peso)
            log_sku_debug('Sin peso definido para el producto SKU: ' . $product_sku);
            if (strpos($product_sku, $sku_base) === 0) {
                log_sku_debug('Lote encontrado - SKU (sin peso): ' . $product_sku);
                $lotes[] = $product; // Agregar el lote a la lista si coincide
            }
        }
    }

    return $lotes;
}
// Función para obtener lotes para productos elaborados (solo con la base del SKU)
function obtener_lotes_disponibles($categoria_slug, $sku_base)
{
    log_sku_debug("Buscando lotes para SKU base: " . $sku_base);
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => -1,
        'tax_query' => array(
            array(
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => $categoria_slug,
            ),
        ),
    );

    $query = new WP_Query($args);
    $lotes = array();

    foreach ($query->posts as $post) {
        $product = wc_get_product($post->ID);
        // Verificar si el SKU del lote contiene el sufijo del SKU del producto padre
        if (strpos($product->get_sku(), $sku_base) !== false) {
            log_sku_debug('Lote encontrado - SKU: ' . $product->get_sku());
            $lotes[] = $product; // Agregar el lote a la lista si coincide
        }
    }

    return $lotes;
}

// 3. Función para descontar stock de los lotes
function descontar_stock_lotes($lotes, $cantidad_total)
{
    $lotes_utilizados = array();

    foreach ($lotes as $lote) {
        $stock_disponible = $lote->get_stock_quantity();
        if ($cantidad_total <= 0) break;

        if ($stock_disponible >= $cantidad_total) {
            // Suficiente stock en este lote
            $lote->set_stock_quantity($stock_disponible - $cantidad_total);
            $lote->save();

            $lotes_utilizados[] = array(
                'lote_id' => $lote->get_id(),
                'name' => $lote->get_name(),
                'cantidad_utilizada' => $cantidad_total,
                'sku' => $lote->get_sku(),
                'fecha_caducidad' => $lote->get_meta('fecha_caducidad'),
            );

            $cantidad_total = 0; // Todo cubierto, salir del bucle
        } else {
            // No suficiente stock, usar todo el lote y continuar con el siguiente
            $lote->set_stock_quantity(0);
            $lote->save();

            $lotes_utilizados[] = array(
                'lote_id' => $lote->get_id(),
                'name' => $lote->get_name(),
                'cantidad_utilizada' => $stock_disponible,
                'sku' => $lote->get_sku(),
                'fecha_caducidad' => $lote->get_meta('fecha_caducidad'),
            );

            $cantidad_total -= $stock_disponible;
        }
    }

    return $lotes_utilizados;
}

// 4. Función para formatear la información del lote
function formatear_informacion_lotes($lotes_utilizados)
{
    $informacion_lote = array(); // Inicializar como array

    foreach ($lotes_utilizados as $lote) {
        // Crear un array para cada lote y agregarlo al array principal
        $informacion_lote[] = array(
            'sku' => $lote['sku'],
            'nombre' => $lote['name'],
            'cantidad_utilizada' => $lote['cantidad_utilizada'],
            'fecha_caducidad' => $lote['fecha_caducidad'],
        );
    }

    return $informacion_lote; // Devolver el array de lotes
}

// 5. Mostrar la información del lote en los detalles del pedido
add_action('woocommerce_order_item_meta_end', 'mostrar_informacion_lote_pedido', 10, 3);

function mostrar_informacion_lote_pedido($item_id, $item, $order)
{
    $informacion_lote = wc_get_order_item_meta($item_id, '_informacion_lote', true);

    if (!empty($informacion_lote) && is_array($informacion_lote)) {
        echo '<p><strong>' . __('Batches', 'woocommerce') . ':</strong><br>';

        // Iterar sobre cada lote y mostrar su información
        foreach ($informacion_lote as $lote) {
            echo 'SKU: ' . esc_html($lote['sku']) . '<br>';
            echo 'Name: ' . esc_html($lote['nombre']) . '<br>';
            echo 'Quantity used: ' . esc_html($lote['cantidad_utilizada']) . '<br>';
            echo 'Expiration date: ' . esc_html($lote['fecha_caducidad']) . '<br><br>';
        }

        echo '</p>';
    } elseif (!empty($informacion_lote)) {
        // Fallback para manejar el caso en que informacion_lote sea una cadena de texto (compatibilidad)
        echo '<p><strong>' . __('Batches', 'woocommerce') . ':</strong> ' . nl2br(esc_html($informacion_lote)) . '</p>';
    }
}

// Registrar información de SKU en un archivo de log personalizado
function log_sku_debug($message) {
    $log_file = WP_CONTENT_DIR . '/sku_debug.log';
    error_log(date('[Y-m-d H:i:s] ') . $message . "\n", 3, $log_file);
}


// Enqueue script 'adminmenu.js' on admin menu 
add_action('wp_enqueue_scripts', function () {
    cookcode_custom_script('enviar-pedido');
});





use Automattic\WooCommerce\Client;
function enviar_pedido_a_kitchentag($order_id) {
  

    // Obtener parámetros
    // 1. Obtener el restaurant_id de la COOKIE
    $restaurant_id = isset($_COOKIE['restaurant_id']) ? sanitize_text_field($_COOKIE['restaurant_id']) : null;
   

   

    // Crear el cliente de WooCommerce
    $woocommerce = new Client(
        'http://localhost:8080/ecommerce/',
        'ck_e116116b637f445f1d001e151c8df6f626897364',
        'cs_154cea8e9fc1568dd1b0d4fd5d86cb8ff98dae5a',
        [
            'version' => 'wc/v3',
            'timeout' => 60,
        ]
    );

    try {
       
        $order = $woocommerce->get("orders/$order_id");

        if ($order) {
           $datos_a_enviar = [
            'id_pedido' => $order_id,
            'fecha_pedido' => $order->date_created,
            'total' => $order->total,
            'cliente' => [
                'nombre' => $order->billing->first_name . ' ' . $order->billing->last_name,
                'email' => $order->billing->email,
                'telefono' => $order->billing->phone,
                'direccion' => $order->billing->address_1,
                'restaurante_id' => $restaurant_id,
            ],
            'productos' => []
        ];

        foreach ($order->line_items as $item) {
            $product_info = [
                'nombre_producto' => $item->name,
                'sku_producto' => $item->sku ?? '',
                'cantidad' => $item->quantity,
                'informacion_lote' => []
            ];

            // Método ALTERNATIVO para obtener meta-datos
            if (isset($item->meta_data)) {
                foreach ($item->meta_data as $meta) {
                    if ($meta->key === '_informacion_lote') {
                        $product_info['informacion_lote'] = is_serialized($meta->value) ? 
                            unserialize($meta->value) : 
                            $meta->value;
                        break;
                    }
                }
            }

            $datos_a_enviar['productos'][] = $product_info;
        }

            // Enviar a KitchenTag
            $response = wp_remote_post('http://localhost:8080/kitchen/menus/stock/guardarPedidosBd.php', [
                'body' => json_encode($datos_a_enviar),
                'headers' => ['Content-Type' => 'application/json'],
                'timeout' => 60
            ]);

            // Log para depuración
            file_put_contents(
                __DIR__ . '/log_pedidos.txt',
                print_r($datos_a_enviar, true),
                FILE_APPEND
            );

          
        }
    } catch (Exception $e) {
        wp_send_json_error($e->getMessage());
    }
}



add_action('woocommerce_payment_complete', 'enviar_pedido_a_kitchentag', 20, 1);
add_action('woocommerce_order_status_processing', 'enviar_pedido_a_kitchentag', 20, 1);
add_action('woocommerce_order_status_completed', 'enviar_pedido_a_kitchentag', 20, 1);







add_filter('woocommerce_get_price_html', 'personalizar_formato_precio', 10, 2);

function personalizar_formato_precio($price, $product)
{
    $price = '<span class="custom-price">' . $price . '</span>';
    return $price;
}





//***************************************************************** */
// Botón "Comprar" en las tarjetas de producto (shop, categorías, relacionados, vistos recientemente)
function agregar_boton_comprar_en_cards() {
    global $product;
    
    // Mostrar siempre el botón (sin restricción de página)
    $url = get_permalink($product->get_id());
    echo '<a href="' . esc_url($url) . '" class="button comprar-now" style="margin-top: 10px;">Buy</a>';
}

// Agregar el botón en diferentes lugares
add_action('woocommerce_after_shop_loop_item', 'agregar_boton_comprar_en_cards', 20);
add_action('woocommerce_after_related_products_loop_item', 'agregar_boton_comprar_en_cards', 20);
add_action('woocommerce_after_recently_viewed_loop_item', 'agregar_boton_comprar_en_cards', 20);


// Mostrar campo personalizado de cantidad y botón de añadir al carrito
function custom_add_to_cart_button()
{
    global $product;

    if ($product->is_in_stock()) {
        echo '<div class="custom-add-to-cart" data-product-id="' . esc_attr($product->get_id()) . '">';
        echo '<div class="quantity-button" >
                <span class="subtract"  style=" cursor: pointer;">-</span>
                <input id="customQuantity" class="input-button" type="number" min="0" max="' . esc_attr($product->get_stock_quantity()) . '" value="0" step="1"  readonly>
                <span class="add" style=" cursor: pointer;">+</span>
              </div>';
        echo '<button id="customAddToCart" >Add to cart</button>';
        echo '</div>';
    }
}
add_action('woocommerce_single_product_summary', 'custom_add_to_cart_button', 35);

// Ajustar precios de variaciones en el carrito
function custom_adjust_cart_item_price($price, $cart_item, $cart_item_key)
{
    if (isset($cart_item['variation_id']) && $cart_item['variation_id'] > 0) {
        $variation = wc_get_product($cart_item['variation_id']);
        if ($variation) {
            $price = $variation->get_price();
        }
    }
    return wc_price($price);
}
add_filter('woocommerce_cart_item_price', 'custom_adjust_cart_item_price', 10, 3);


function custom_enqueue_scripts()
{
    if (is_product()) {
        global $product;

        // Verificar si el producto es variable o simple
        if ($product->is_type('variable')) {
            // Para productos variables, obtener el stock de cada variación
            $variations = $product->get_available_variations();
            $variation_stock = array();
            foreach ($variations as $variation) {
                $variation_id = $variation['variation_id'];
                $stock_quantity = get_post_meta($variation_id, '_stock', true);
                $variation_stock[$variation_id] = $stock_quantity;
            }
        } else {
            // Para productos simples, solo obtener el stock del producto
            $variation_stock = array($product->get_id() => $product->get_stock_quantity());
        }

        wp_enqueue_script('custom-add-to-cart', get_stylesheet_directory_uri() . '/js/custom-add-to-cart.js', array('jquery'), null, true);

        wp_localize_script('custom-add-to-cart', 'customAddToCartParams', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'home_url' => home_url(),
            'product_id' => $product->get_id(),
            'variation_stock' => $variation_stock, // Pasar el stock de las variaciones
        ));
    }
}
add_action('wp_enqueue_scripts', 'custom_enqueue_scripts');

function custom_add_to_cart_ajax()
{
    if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
        $product_id = intval($_POST['product_id']);
        $quantity = intval($_POST['quantity']);
        $variation_id = isset($_POST['variation_id']) ? intval($_POST['variation_id']) : 0;

        // Depuración
        error_log("Product ID: $product_id");
        error_log("Quantity: $quantity");
        error_log("Variation ID: $variation_id");

        // Asegúrate de que el producto exista y esté disponible
        $product = wc_get_product($product_id);
        if (!$product || !$product->is_in_stock()) {
            wp_send_json([
                'success' => false,
                'data' => ['message' => 'Producto no disponible o no encontrado.'],
            ]);
        }

        // Añadir al carrito
        if ($variation_id > 0) {
            $result = WC()->cart->add_to_cart($product_id, $quantity, $variation_id);
        } else {
            $result = WC()->cart->add_to_cart($product_id, $quantity);
        }

        if ($result) {
            wp_send_json([
                'success' => true,
                'data' => ['message' => 'Producto añadido al carrito.'],
            ]);
        } else {
            wp_send_json([
                'success' => false,
                'data' => ['message' => 'No se pudo añadir al carrito.'],
            ]);
        }
    }

    // Respuesta de error genérico
    wp_send_json([
        'success' => false,
        'data' => ['message' => 'Solicitud inválida. Por favor, intenta de nuevo.'],
    ]);
}
add_action('wp_ajax_custom_add_to_cart', 'custom_add_to_cart_ajax');
add_action('wp_ajax_nopriv_custom_add_to_cart', 'custom_add_to_cart_ajax');


// Asegurar que se pasa el precio de la variación al carrito
function custom_add_variation_price_to_cart($cart_item_data, $product_id, $variation_id)
{
    if ($variation_id) {
        $variation = wc_get_product($variation_id);
        if ($variation) {
            $cart_item_data['variation_price'] = $variation->get_price();
        }
    }
    return $cart_item_data;
}
add_filter('woocommerce_add_cart_item_data', 'custom_add_variation_price_to_cart', 10, 3);

// Mostrar el precio correcto de la variación en el carrito
function custom_display_variation_price_in_cart($price, $cart_item, $cart_item_key)
{
    if (isset($cart_item['variation_id']) && $cart_item['variation_id'] > 0) {
        $variation = wc_get_product($cart_item['variation_id']);
        if ($variation) {
            $price = wc_price($variation->get_price());
        }
    }
    return $price;
}
add_filter('woocommerce_cart_item_price', 'custom_display_variation_price_in_cart', 10, 3);

// Función AJAX para obtener el stock de una variación
function get_variation_stock()
{
    if (isset($_POST['variation_id'])) {
        $variation_id = intval($_POST['variation_id']);

        $variation = wc_get_product($variation_id);
        if ($variation) {
            $stock_quantity = $variation->get_stock_quantity();
            wp_send_json_success(['stock' => $stock_quantity]);
        }
    }
    wp_send_json_error();
}
add_action('wp_ajax_get_variation_stock', 'get_variation_stock');
add_action('wp_ajax_nopriv_get_variation_stock', 'get_variation_stock');


// Mostrar campos personalizados en la página de edición de producto en el backend
add_action('woocommerce_product_options_general_product_data', 'agregar_campos_personalizados_producto');

function agregar_campos_personalizados_producto()
{
    // Campo de Localización
    woocommerce_wp_text_input(array(
        'id' => 'localizacion',  // ID del campo
        'label' => __('Localización', 'woocommerce'),  // Etiqueta del campo
        'placeholder' => 'Ingrese la localización del producto',  // Texto de sugerencia
        'desc_tip' => 'true',  // Activa la sugerencia al pasar el ratón
        'description' => __('La localización del producto.', 'woocommerce'),  // Descripción del campo
    ));

    // Campo de Empaquetado
    woocommerce_wp_text_input(array(
        'id' => 'empaquetado',  // ID del campo
        'label' => __('Empaquetado', 'woocommerce'),  // Etiqueta del campo
        'placeholder' => 'Ingrese el empaquetado del producto',  // Texto de sugerencia
        'desc_tip' => 'true',  // Activa la sugerencia al pasar el ratón
        'description' => __('Descripción del empaquetado del producto.', 'woocommerce'),  // Descripción del campo
    ));

       // Campo de alergeno
       woocommerce_wp_text_input(array(
        'id' => 'alergeno',  // ID del campo
        'label' => __('Alergeno', 'woocommerce'),  // Etiqueta del campo
        'placeholder' => 'Ingrese el alergeno del producto',  // Texto de sugerencia
        'desc_tip' => 'true',  // Activa la sugerencia al pasar el ratón
        'description' => __('El alergeno del producto.', 'woocommerce'),  // Descripción del campo
    ));

      // Campo de alergeno
      woocommerce_wp_text_input(array(
        'id' => 'peso',  // ID del campo
        'label' => __('Peso', 'woocommerce'),  // Etiqueta del campo
        'placeholder' => 'Ingrese el peso del producto',  // Texto de sugerencia
        'desc_tip' => 'true',  // Activa la sugerencia al pasar el ratón
        'description' => __('El peso del producto.', 'woocommerce'),  // Descripción del campo
    ));

}



