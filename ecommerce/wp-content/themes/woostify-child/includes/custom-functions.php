<?php
function schedule_sync_ingredient($post_id)
{
    if (get_post_type($post_id) === 'product') {
        wp_schedule_single_event(time() + 10, 'sync_ingredient_with_kitchen', array($post_id));
    }
}

add_action('save_post', 'schedule_sync_ingredient', 10, 1);
function sync_ingredient_with_kitchen($post_id)
{


    // Continuar con el proceso de sincronización si no existe

    // Obtener el producto
    $product = wc_get_product($post_id);

    // Obtener datos del producto
    $name =  $product->get_name();
    $sku = $product->get_sku();  // Obtener el SKU
    $stock = $product->get_stock_quantity();  // Obtener la cantidad de stock

    // Crear el array de datos a enviar
    $data = array(
        'name' => $name,
        'postId' => $post_id,
        'product ' => $product,
        'sku' => $sku,
        'stock' => $stock,
    );

    // **Depuración: Log de los datos a enviar**
    $log = print_r($data, true);
    file_put_contents(get_template_directory() . '/woocommerce_log.txt', $log, FILE_APPEND);

    // URL de tu aplicación de cocina donde recibirás los datos
    $url = 'http://localhost:8080/kitchen/miseEnPlace/apiEcomerce.php';

    // Configurar la solicitud POST
    $options = array(
        'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data),
        ),
    );
    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);

    // **Depuración: Log de la respuesta**
    if ($result === FALSE) {
        error_log("Error al enviar datos al servidor de Kitchen.");
    } else {
        error_log("Datos enviados correctamente a Kitchen: " . $result);
    }
}


add_action('woocommerce_update_product', 'sync_ingredient_with_kitchen', 10, 3);



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

// Mostrar fechas en la página de producto

/** 
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

    if ($fecha_fabricacion || $fecha_caducidad) {
        echo '<div class="producto-fechas">';
        if ($fecha_fabricacion) {
            echo '<p><strong>Fecha de Fabricación:</strong> ' . esc_html($fecha_fabricacion) . '</p>';
        }
        if ($fecha_caducidad) {
            echo '<p><strong>Fecha de Caducidad:</strong> ' . esc_html($fecha_caducidad) . '</p>';
        }
        echo '</div>';
    }
}
add_action('woocommerce_single_product_summary', 'mostrar_fechas_producto', 20);


*/



// Guardar el campo de precio de coste
add_action('woocommerce_process_product_meta', 'save_custom_cost_price_field');
function save_custom_cost_price_field($post_id)
{
    // Guardar el precio de coste
    if (isset($_POST['cost_price'])) {
        update_post_meta($post_id, 'cost_price', sanitize_text_field($_POST['cost_price']));
    }
}



// Mostrar precio de coste y fechas en la página del producto
/** 
add_action('woocommerce_single_product_summary', 'mostrar_precio_coste_fechas_producto', 20);
function mostrar_precio_coste_fechas_producto()
{
    global $product;

    $product_id = $product->get_id();
    $cost_price = get_post_meta($product_id, 'cost_price', true);
    $fecha_fabricacion = get_post_meta($product_id, 'fecha_elaboracion', true);
    $fecha_caducidad = get_post_meta($product_id, 'fecha_caducidad', true);

    // Verificar y formatear las fechas
    if ($fecha_fabricacion) {
        $fecha_fabricacion = date('d-m-Y', strtotime($fecha_fabricacion)); // Convertir a DD-MM-YYYY
    }
    if ($fecha_caducidad) {
        $fecha_caducidad = date('d-m-Y', strtotime($fecha_caducidad)); // Convertir a DD-MM-YYYY
    }

    // Mostrar el precio de coste y las fechas
    echo '<div class="producto-info-adicional">';
    if ($cost_price) {
        echo '<p><strong>Precio de Coste:</strong> ' . wc_price($cost_price) . '</p>'; // wc_price formatea el precio
    }
    if ($fecha_fabricacion || $fecha_caducidad) {
        echo '<div class="producto-fechas">';
        if ($fecha_fabricacion) {
            echo '<p><strong>Fecha de Fabricación:</strong> ' . esc_html($fecha_fabricacion) . '</p>';
        }
        if ($fecha_caducidad) {
            echo '<p><strong>Fecha de Caducidad:</strong> ' . esc_html($fecha_caducidad) . '</p>';
        }
        echo '</div>';
    }
    echo '</div>';
}

*/

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

function procesar_pedido_con_lotes($order_id) {
    $order = wc_get_order($order_id);

    foreach ($order->get_items() as $item_id => $item) {
        $product_id = $item['product_id']; // Usar el método get_product_id() para obtener el ID del producto
        $product = wc_get_product($product_id);
        $product_sku = $product->get_sku(); // Obtener el SKU del producto padre
        
        // Extraer el sufijo del SKU (el número)
        $sku_parts = explode('-', $product_sku);
        $sku_suffix = end($sku_parts); // Tomar la última parte del SKU (ej: 229)
    
        // Verificar si el producto pertenece a la categoría padre "Elaborado"
        if (has_term('elaborado', 'product_cat', $product_id)) {
            $cantidad_pedida = $item->get_quantity();
    
            // Obtener los lotes (productos de la subcategoría "Lote Elaborado") 
            // Filtrar lotes por SKU que contengan el sufijo extraído
            $lotes = obtener_lotes_disponibles('lotes-de-elaborados', $sku_suffix); // Función personalizada para obtener lotes
    
            // Descontar stock de los lotes
            $lotes_utilizados = descontar_stock_lotes($lotes, $cantidad_pedida);
    
            // Actualizar el pedido con la información del lote utilizado
            $informacion_lote = formatear_informacion_lotes($lotes_utilizados);
            wc_add_order_item_meta($item_id, '_informacion_lote', $informacion_lote);
        }
        else if(has_term('ingredientes', 'product_cat', $product_id)) {
            $cantidad_pedida = $item->get_quantity();
    
            // Obtener los lotes (productos de la subcategoría "lotes-de-ingredientes")
            // Filtrar lotes por SKU que contengan el sufijo extraído
            $lotes = obtener_lotes_disponibles('lotes-de-ingredientes', $sku_suffix); // Función personalizada para obtener lotes
    
            // Descontar stock de los lotes
            $lotes_utilizados = descontar_stock_lotes($lotes, $cantidad_pedida);
    
            // Actualizar el pedido con la información del lote utilizado
            $informacion_lote = formatear_informacion_lotes($lotes_utilizados);
            wc_add_order_item_meta($item_id, '_informacion_lote', $informacion_lote);
        }
    }
}

function obtener_lotes_disponibles($categoria_slug, $sku_suffix) {
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
        if (strpos($product->get_sku(), $sku_suffix) !== false) {
            $lotes[] = $product; // Agregar el lote a la lista si coincide
        }
    }

    return $lotes;
}

// 3. Función para descontar stock de los lotes
function descontar_stock_lotes($lotes, $cantidad_total) {
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
function formatear_informacion_lotes($lotes_utilizados) {
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

function mostrar_informacion_lote_pedido($item_id, $item, $order) {
    $informacion_lote = wc_get_order_item_meta($item_id, '_informacion_lote', true);

    if (!empty($informacion_lote) && is_array($informacion_lote)) {
        echo '<p><strong>' . __('Lote(s) Utilizado(s)', 'woocommerce') . ':</strong><br>';

        // Iterar sobre cada lote y mostrar su información
        foreach ($informacion_lote as $lote) {
            echo 'SKU: ' . esc_html($lote['sku']) . '<br>';
            echo 'Nombre: ' . esc_html($lote['nombre']) . '<br>';
            echo 'Cantidad Utilizada: ' . esc_html($lote['cantidad_utilizada']) . '<br>';
            echo 'Fecha de Caducidad: ' . esc_html($lote['fecha_caducidad']) . '<br><br>';
        }

        echo '</p>';
    } elseif (!empty($informacion_lote)) {
        // Fallback para manejar el caso en que informacion_lote sea una cadena de texto (compatibilidad)
        echo '<p><strong>' . __('Lote(s) Utilizado(s)', 'woocommerce') . ':</strong> ' . nl2br(esc_html($informacion_lote)) . '</p>';
    }
}







/** 













// Permitir cantidades decimales para productos cuyo SKU empieza con "ING"
add_filter('woocommerce_quantity_input_args', 'allow_decimal_quantities_for_lotes_ingredientes', 10, 2);
add_filter('woocommerce_is_sold_individually', 'disable_individual_sale_for_lotes_ingredientes', 10, 2);
add_filter('woocommerce_loop_add_to_cart_link', 'allow_decimal_quantity_display', 10, 2);

// Ajustar la entrada de cantidades decimales
add_filter('woocommerce_quantity_input_min', 'set_min_decimal_quantity', 10, 2);
add_filter('woocommerce_quantity_input_step', 'set_step_decimal_quantity', 10, 2);

function allow_decimal_quantities_for_lotes_ingredientes($args, $product) {
    // Verificamos si el producto existe para evitar errores
    if (!is_a($product, 'WC_Product')) {
        return $args;
    }
    
    // Obtener el SKU del producto
    $sku = $product->get_sku();
    
    // Verifica si el SKU empieza con "ING"
    if ($sku && strpos($sku, 'ING') === 0) {
        // Permitir cantidades decimales
        $args['input_value'] = 0.1; // Cantidad predeterminada
        $args['step'] = 0.01; // Permitir incrementos de 0.01
        $args['min_value'] = 0.01; // Cantidad mínima
    }

    return $args;
}

function disable_individual_sale_for_lotes_ingredientes($sold_individually, $product) {
    // Verificamos si el producto existe para evitar errores
    if (!is_a($product, 'WC_Product')) {
        return $sold_individually;
    }

    // Obtener el SKU del producto
    $sku = $product->get_sku();

    // Verifica si el SKU empieza con "ING"
    if ($sku && strpos($sku, 'ING') === 0) {
        return false; // Permitir que se vendan múltiples cantidades (no venta individual)
    }

    return $sold_individually;
}

// Personalizar la visualización de cantidades en la tienda
function allow_decimal_quantity_display($html, $product) {
    $sku = $product->get_sku();
    if ($sku && strpos($sku, 'ING') === 0) {
        // Cambiar el HTML del botón para incluir el input de cantidad decimal
        $html = '<form action="' . esc_url($product->add_to_cart_url()) . '" method="post" enctype="multipart/form-data">
                     <input type="number" name="quantity" value="0.1" step="0.01" min="0.01" style="width: 60px;" />
                     <button type="submit" class="button add_to_cart_button">Añadir al carrito</button>
                 </form>';
    }
    return $html;
}

// Ajustar el valor mínimo de la cantidad para productos con SKU que empiezan con "ING"
function set_min_decimal_quantity($min, $product) {
    $sku = $product->get_sku();
    if ($sku && strpos($sku, 'ING') === 0) {
        return 0.01; // Mínimo de 0.01
    }
    return $min; // Para otros productos
}

// Ajustar el paso de la cantidad para productos con SKU que empiezan con "ING"
function set_step_decimal_quantity($step, $product) {
    $sku = $product->get_sku();
    if ($sku && strpos($sku, 'ING') === 0) {
        return 0.01; // Paso de 0.01
    }
    return $step; // Para otros productos
}

function custom_decimal_quantity_buttons_script() {
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            // Seleccionamos todos los inputs de cantidad con clase .qty que estén dentro de formularios de productos con SKU que empiezan con ING
            $('form.cart, .product').each(function() {
                var $form = $(this);
                var $qtyInput = $form.find('.qty');
                
                // Solo aplicamos si el producto tiene un SKU que comienza con "ING"
                if ($qtyInput.length > 0 && $qtyInput.closest('.product').data('product_sku').startsWith('ING')) {
                    // Ajustamos el comportamiento de los botones para manejar cantidades decimales
                    $form.on('click', '.quantity .plus', function() {
                        var currentVal = parseFloat($qtyInput.val());
                        var step = parseFloat($qtyInput.attr('step')) || 1;
                        if (!isNaN(currentVal)) {
                            $qtyInput.val((currentVal + step).toFixed(2));
                        }
                    });

                    $form.on('click', '.quantity .minus', function() {
                        var currentVal = parseFloat($qtyInput.val());
                        var step = parseFloat($qtyInput.attr('step')) || 1;
                        if (!isNaN(currentVal) && currentVal > step) {
                            $qtyInput.val((currentVal - step).toFixed(2));
                        }
                    });
                }
            });
        });
    </script>
    <?php
}
add_action('wp_footer', 'custom_decimal_quantity_buttons_script');

*/