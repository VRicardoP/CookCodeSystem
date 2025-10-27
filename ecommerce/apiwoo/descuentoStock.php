<?php
require __DIR__ . '/vendor/autoload.php';

use Automattic\WooCommerce\Client;

// Definir las claves de la API
$consumer_key = 'ck_e116116b637f445f1d001e151c8df6f626897364';
$consumer_secret = 'cs_154cea8e9fc1568dd1b0d4fd5d86cb8ff98dae5a';
$url_base = 'http://localhost:8080/ecommerce/';

function actualizarStockProducto($url_base, $filtro, $nuevoStock)
{
    $woocommerce = new Client(
        $url_base,
        $GLOBALS['consumer_key'],
        $GLOBALS['consumer_secret'],
        [
            'version' => 'wc/v3',
        ]
    );

    // Intentar buscar el producto por SKU o nombre
    $productos = $woocommerce->get('products', ['sku' => $filtro]);
    if (empty($productos)) {
        $productos = $woocommerce->get('products', ['search' => $filtro, 'per_page' => 100]);
    }

    if (!empty($productos)) {
        foreach ($productos as $producto) {
            if ($producto->sku == $filtro || $producto->name == $filtro) {
                $producto_id = $producto->id;

                // Actualizar el stock
                $data = [
                    'stock_quantity' => $nuevoStock,
                ];
                $woocommerce->put("products/$producto_id", $data);
                return true;
            }
        }
    }
    return false;
}

function actualizarStockCategoriaHija($url_base, $categoria_id, $descuentoStock, $sku_padre)
{
    $woocommerce = new Client(
        $url_base,
        $GLOBALS['consumer_key'],
        $GLOBALS['consumer_secret'],
        [
            'version' => 'wc/v3',
        ]
    );

    // Validar el formato del SKU padre
    if (!preg_match('/^ING-(\d+)$/', $sku_padre, $matches_padre)) {
        return false;
    }
    $numero_padre = intval($matches_padre[1]);

    // Obtener productos de la categoría hija
    $productos = $woocommerce->get('products', [
        'category' => $categoria_id,
        'per_page' => 100,
    ]);

    $productosFiltrados = [];
    foreach ($productos as $producto) {
        if (preg_match('/^ING-(\d+)-(\d+)$/', $producto->sku, $matches)) {
            if (intval($matches[2]) === $numero_padre) {
                $productosFiltrados[] = $producto;
            }
        }
    }

    // Ordenar productos hijos
    usort($productosFiltrados, function ($a, $b) {
        preg_match('/^ING-(\d+)-(\d+)$/', $a->sku, $matchesA);
        preg_match('/^ING-(\d+)-(\d+)$/', $b->sku, $matchesB);
        return intval($matchesA[1]) - intval($matchesB[1]);
    });

    $productosDescontados = [];
    foreach ($productosFiltrados as $producto) {
        $producto_id = $producto->id;
        $stockActual = $producto->stock_quantity;

        if ($descuentoStock <= 0) {
            break;
        }

        // Calcular nuevo stock
        if ($stockActual >= $descuentoStock) {
            $nuevoStock = $stockActual - $descuentoStock;
            $descuentoStock = 0;
        } else {
            $nuevoStock = 0;
            $descuentoStock -= $stockActual;
        }

        // Actualizar en WooCommerce
        $data = ['stock_quantity' => $nuevoStock];
        $woocommerce->put("products/$producto_id", $data);

        // Obtener metadatos relevantes
        $productoCompleto = $woocommerce->get("products/$producto_id");
        $metadatos = [];
        if (isset($productoCompleto->meta_data)) {
            foreach ($productoCompleto->meta_data as $meta) {
                if (in_array($meta->key, ['fecha_elaboracion', 'fecha_caducidad', 'cost_price', 'type_unit'])) {
                    $metadatos[$meta->key] = $meta->value;
                }
            }
        }

        $productosDescontados[] = [
            'id' => $producto_id,
            'sku' => $producto->sku,
            'stock_anterior' => $stockActual,
            'stock_nuevo' => $nuevoStock,
            'metadatos' => $metadatos, // Aquí se agregan los metadatos específicos
        ];
    }

    global $response;
    $response['productos_descontados'] = $productosDescontados;
    return $descuentoStock === 0;
}

$response = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sku = $_POST['sku'] ?? null;
    $nuevoStock = $_POST['nuevoStock'] ?? null;
    $descuentoStock = isset($_POST['descuentoStock']) ? (int)$_POST['descuentoStock'] : 0;

    if ($sku && $nuevoStock !== null) {
        if (actualizarStockProducto($url_base, $sku, $nuevoStock)) {
            $response['success'] = true;
            $response['message'] = "El stock del producto con SKU/Nombre $sku se ha actualizado.";
        } else {
            $response['success'] = false;
            $response['message'] = "No se encontró ningún producto con el SKU/Nombre proporcionado.";
        }

        if ($descuentoStock > 0) {
            $categoria_id = 25;
            if (actualizarStockCategoriaHija($url_base, $categoria_id, $descuentoStock, $sku)) {
                $response['success'] = true;
                $response['message'] .= " El stock de productos en la categoría $categoria_id se actualizó.";
            } else {
                $response['success'] = false;
                $response['message'] = "No se encontraron productos para descontar en la categoría $categoria_id.";
            }
        }
    } else {
        $response['success'] = false;
        $response['message'] = "Datos insuficientes.";
    }
}

header('Content-Type: application/json');
echo json_encode($response);
