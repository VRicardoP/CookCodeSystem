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

    // Inicialmente, intentamos buscar por SKU
    $productos = $woocommerce->get('products', ['sku' => $filtro]);

    // Si no se encontraron productos por SKU, intentamos buscar por nombre
    if (empty($productos)) {
        $productos = $woocommerce->get('products', ['search' => $filtro, 'per_page' => 100]);
    }

    // Verificar si se ha encontrado un producto exacto
    if (!empty($productos)) {
        // Si encontramos más de un producto por nombre, debemos asegurarnos de que es el correcto
        foreach ($productos as $producto) {
            if ($producto->sku == $filtro || $producto->name == $filtro) {
                $producto_id = $producto->id;

                // Actualizar el stock del producto encontrado
                $data = [
                    'stock_quantity' => $nuevoStock,
                ];

                $woocommerce->put("products/$producto_id", $data);
                return true;  // Actualización exitosa
            }
        }
    }

    // Si no se encuentra ningún producto con el filtro exacto, retornar falso
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

    // Extraer el número del SKU del padre (número después del guion)
    if (!preg_match('/^ING-(\d+)$/', $sku_padre, $matches_padre)) {
        return false; // El SKU del padre no tiene el formato esperado
    }
    $numero_padre = intval($matches_padre[1]);

    

    // Obtener productos hijos de la categoría hija que tengan relación con el SKU del padre
    $productos = $woocommerce->get('products', [
        'category' => $categoria_id,
        'per_page' => 100, // Ajusta este valor si hay más productos en la categoría
    ]);

    $productosFiltrados = [];

    // Filtrar los productos cuyos SKUs coincidan con el SKU del padre
    foreach ($productos as $producto) {
        $sku = $producto->sku;

        // Verificar que el SKU siga el formato esperado y coincida con el número del padre
        if (preg_match('/^ING-(\d+)-(\d+)$/', $sku, $matches)) {
            $numero1 = intval($matches[1]); // Número después del primer guion
            $numero2 = intval($matches[2]); // Número después del segundo guion

            // Comparar el número después del segundo guion con el número del SKU del padre
            if ($numero2 === $numero_padre) {
                $productosFiltrados[] = $producto; // Solo afectar a este padre y sus hijos
            }
        }
    }

    // Ordenar los productos hijos por el número después del primer guion
    usort($productosFiltrados, function($a, $b) {
        preg_match('/^ING-(\d+)-(\d+)$/', $a->sku, $matchesA);
        preg_match('/^ING-(\d+)-(\d+)$/', $b->sku, $matchesB);

        $numero1A = intval($matchesA[1]);
        $numero1B = intval($matchesB[1]);

        return $numero1A - $numero1B; // Orden ascendente
    });

    // Descontar el stock solo de este producto padre y sus hijos
    foreach ($productosFiltrados as $producto) {
        $producto_id = $producto->id;
        $stockActual = $producto->stock_quantity;

        if ($descuentoStock <= 0) {
            break; // Ya se ha descontado todo el stock necesario
        }

        if ($stockActual >= $descuentoStock) {
            // Si el stock actual es suficiente para cubrir el descuento
            $nuevoStock = $stockActual - $descuentoStock;
            $descuentoStock = 0; // Ya no queda stock por descontar
        } else {
            // Si el stock actual no es suficiente, restar lo que se pueda y seguir con el siguiente producto
            $nuevoStock = 0; // Este producto se queda sin stock
            $descuentoStock -= $stockActual; // Reducir el stock que falta por descontar
        }

        // Actualizar el stock del producto en WooCommerce
        $data = [
            'stock_quantity' => $nuevoStock,
        ];
        $woocommerce->put("products/$producto_id", $data);
    }

    // Si después de recorrer todos los productos aún queda stock por descontar, retornar falso
    if ($descuentoStock > 0) {
        return false;
    }

    return true;
}

$response = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (!empty($_POST['sku']) && isset($_POST['nuevoStock']) && $_POST['nuevoStock'] !== '') {
        $filtro = $_POST['sku'];
        $nuevoStock = $_POST['nuevoStock'];
        $descuentoStock = $_POST['descuentoStock'];

        if (actualizarStockProducto($url_base, $filtro, $nuevoStock)) {
            $response['success'] = true;
            $response['message'] = "El stock del producto con SKU/Nombre $filtro se ha actualizado a $nuevoStock unidades.";
        } else {
            $response['success'] = false;
            $response['message'] = "No se encontró ningún producto con el SKU/Nombre proporcionado.";
        }

        // Verificar si se necesita actualizar el stock de la categoría hija
        if (!empty($descuentoStock)) {
            $categoria_id = 25;
            $sku = $_POST['sku']; // Obtener el SKU desde el POST

            if (actualizarStockCategoriaHija($url_base, $categoria_id, $descuentoStock, $sku)) {
                $response['success'] = true;
                $response['message'] = "El stock del producto más antiguo en la categoría $categoria_id se ha actualizado.";
            } else {
                $response['success'] = false;
                $response['message'] = "No se encontró ningún producto en la categoría $categoria_id.";
            }
        }

    } else if (!empty($_POST['nombre']) && isset($_POST['nuevoStock']) && $_POST['nuevoStock'] !== '') {
        $filtro = $_POST['nombre'];
        $nuevoStock = $_POST['nuevoStock'];

        if (actualizarStockProducto($url_base, $filtro, $nuevoStock)) {
            $response['success'] = true;
            $response['message'] = "El stock del producto con SKU/Nombre $filtro se ha actualizado a $nuevoStock unidades.";
        } else {
            $response['success'] = false;
            $response['message'] = "No se encontró ningún producto con el SKU/Nombre proporcionado.";
        }
    } else {
        $response['success'] = false;
        $response['message'] = "No se proporcionaron suficientes datos.";
    }
}

// Devolver la respuesta como JSON
header('Content-Type: application/json');
echo json_encode($response);
