<?php
require __DIR__ . '/vendor/autoload.php';

use Automattic\WooCommerce\Client;

// Claves de la API
$consumer_key = 'ck_e116116b637f445f1d001e151c8df6f626897364';
$consumer_secret = 'cs_154cea8e9fc1568dd1b0d4fd5d86cb8ff98dae5a';
$url_base = 'http://localhost:8080/ecommerce/';

function mostrarLotesDeProducto($url_base, $sku_padre) {
    // Crear el cliente WooCommerce
    $woocommerce = new Client(
        $url_base,
        $GLOBALS['consumer_key'],
        $GLOBALS['consumer_secret'],
        ['version' => 'wc/v3']
    );

    // Obtener el producto principal usando el SKU del producto
    $productoPrincipal = $woocommerce->get('products', ['sku' => $sku_padre]);

    // Verificar que el producto existe
    if (empty($productoPrincipal)) {
        error_log("No se encontró el producto principal con SKU: " . $sku_padre);
        return;
    }

    // Obtener las variaciones (lotes) del producto principal
    $variaciones = $woocommerce->get("products/{$productoPrincipal[0]->id}/variations");

    // Si no hay variaciones
    if (empty($variaciones)) {
        error_log("El producto principal con SKU {$sku_padre} no tiene variaciones.");
        return;
    }

    // Mostrar las variaciones en el error_log
    foreach ($variaciones as $variacion) {
        error_log("Lote encontrado: SKU: {$variacion->sku}, ID: {$variacion->id}, Precio: {$variacion->regular_price}");
    }
}
// Función para obtener los lotes correspondientes con el SKU principal y la variación
function obtenerLotesProducto($url_base, $sku_padre, $variacion)
{
    $woocommerce = new Client(
        $url_base,
        $GLOBALS['consumer_key'],
        $GLOBALS['consumer_secret'],
        ['version' => 'wc/v3']
    );
    mostrarLotesDeProducto($url_base, $sku_padre);
    // Obtener todos los productos con el SKU del producto principal
    $productos = $woocommerce->get('products', ['sku' => $sku_padre."-004-L370"]);

    // Agregar log para ver qué productos se están obteniendo
    error_log("Productos obtenidos: " . print_r($productos, true));

    // Filtrar los productos que tienen el SKU con la variación 
    $productosFiltrados = [];
    foreach ($productos as $producto) {

        error_log("Producto: " . $producto->name); 
        error_log("Producto sku: " . $producto->sku); 
        // Aquí, la variación debe tener ceros a la izquierda para que coincida con el SKU
        $variacion_formateada = str_pad($variacion, 3, '0', STR_PAD_LEFT);
        error_log("Variación que se está buscando: " . $variacion_formateada);

        // Verifica si el SKU de la variación contiene el SKU base y la variación formateada
        $sku_esperado = $sku_padre . '-' . $variacion_formateada;
        error_log("SKU esperado: " . $sku_esperado);

       
            $productosFiltrados[] = $producto;
      
    }

    // Para depurar, ver los productos encontrados
    error_log("Productos filtrados: " . print_r($productosFiltrados, true));

    return $productosFiltrados;
}

// Función para actualizar el stock de los lotes específicos
function actualizarStockPorLote($url_base, $sku_padre, $variacion, $addStock)
{
    $woocommerce = new Client(
        $url_base,
        $GLOBALS['consumer_key'],
        $GLOBALS['consumer_secret'],
        ['version' => 'wc/v3']
    );

    // Obtener los lotes correspondientes con el SKU principal y la variación
    $productosFiltrados = obtenerLotesProducto($url_base, $sku_padre, $variacion);

    // Si no se encuentran productos, devolver false
    if (empty($productosFiltrados)) {
        error_log("No se encontraron productos para la variación $variacion.");
        return false;
    }

    $productosActualizados = [];
    foreach ($productosFiltrados as $producto) {
        $producto_id = $producto->id;
        $stockActual = $producto->stock_quantity;

        // Restar el stock del lote con el valor de addStock
        $nuevoStock = $stockActual - $addStock;

        // Para depurar, ver el stock actual y el nuevo stock
        error_log("Actualizando producto ID $producto_id: stock anterior = $stockActual, stock nuevo = $nuevoStock");

        // Actualizar el stock del lote en WooCommerce
        $data = ['stock_quantity' => $nuevoStock];
        $response_update = $woocommerce->put("products/$producto_id", $data);

        // Almacenar la información del producto actualizado
        $productosActualizados[] = [
            'id' => $producto_id,
            'sku' => $producto->sku,
            'stock_anterior' => $stockActual,
            'stock_nuevo' => $nuevoStock,
        ];
    }

    global $response;
    $response['productos_actualizados'] = $productosActualizados;

    return true;
}

$response = [];

// Asegúrate de que la solicitud es de tipo JSON
if ($_SERVER["CONTENT_TYPE"] == "application/json") {
    // Obtener los datos JSON del cuerpo de la solicitud
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    // Verificar si los datos contienen los campos necesarios
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $sku = $data['sku'] ?? null;
        $addStock = isset($data['addStock']) ? (int)$data['addStock'] : 0;
        $valueOption = $data['selectValue'] ?? null;  // Variación de producto (por ejemplo, "005")

        // Validar los parámetros
        if ($sku && $addStock > 0 && $valueOption !== null) {
            // Llamar a la función para actualizar el stock
            if (actualizarStockPorLote($url_base, $sku, $valueOption, $addStock)) {
                $response['success'] = true;
                $response['message'] = "Stock actualizado correctamente para los lotes relacionados con la variación.";
            } else {
                $response['success'] = false;
                $response['message'] = "No se encontraron productos para actualizar el stock con la variación $valueOption.";
            }
        } else {
            $response['success'] = false;
            $response['message'] = "Datos insuficientes o inválidos.";
        }
    }
} else {
    $response['success'] = false;
    $response['message'] = "La solicitud debe ser en formato JSON.";
}

// Establecer el tipo de contenido a JSON
header('Content-Type: application/json');
echo json_encode($response);