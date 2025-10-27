<?php

require __DIR__ . '/../../models/almacenIngredientes.php';
require_once __DIR__ . '/../../models/almacenIngredientesDao.php';

require __DIR__ . '/../../models/ingredientes.php';
require_once __DIR__ . '/../../models/ingredientesDao.php';

$response = [
    'error' => false,
    'message' => '',
    'success' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Validación básica de datos
    if (empty($_POST['idIngrediente']) || empty($_POST['estado'])) {
        $response['error'] = true;
        $response['message'] = 'Datos incompletos';
        echo json_encode($response);
        http_response_code(400);
        exit;
    }

    $id = $_POST['idIngrediente'];
    $estado = $_POST['estado'];

    $ingTag = AlmacenIngredientesDao::select($id);
    if (!$ingTag) {
        $response['error'] = true;
        $response['message'] = 'Ingrediente no encontrado';
        echo json_encode($response);
        http_response_code(404);
        exit;
    }

    if ($estado == "Registered") {
        $ingTag->setEstado('Sent');
    } else if ($estado == "Sent") {
        $ingId = $ingTag->getIngredienteId();
        $ing = IngredientesDao::select($ingId);

        $nombreIngrediente = $ingTag->getFName();
        $salePrice = $ingTag->getSalePrice();
        $costPrice = $ingTag->getCostPrice();
        $cantidad = $ingTag->getCantidadPaquete();
        $pesoPaquete = $ingTag->getProductamount();

        if (floatval($cantidad) <= 0 || floatval($pesoPaquete) <= 0) {
            $response['error'] = true;
            $response['message'] = 'Unidades inválidas para calcular el precio';
            echo json_encode($response);
            http_response_code(422);
            exit;
        }

        $unidades = $pesoPaquete;

        //   $unidadesFormateado = number_format($unidades, 2, '.', '');

        $precioUnidad = floatval($salePrice) / $unidades;
        $precioUnidadFormateado = number_format($precioUnidad, 2, '.', '');

        $costeUnidad = floatval($costPrice) / $unidades;
        $costeUnidadFormateado = number_format($costeUnidad, 2, '.', '');

        $partesPathImagen = explode("/", $ing->getImage());
        $nombreImagen = end($partesPathImagen);

        // Generar el SKU con formato
        $sku = sprintf("ING-%d-%03d-L%d",$ingId , $ingTag->getCantidadPaquete() * 10, $id);
        $parent_id = sprintf("ING-%d", $ingId);
        $regularPrice = round($ing->getCostPrice() * $ingTag->getCantidadPaquete() * 1.5, 2);
        $response = [
            'name' => $nombreIngrediente,
            'sale_price' => $precioUnidadFormateado,
            'coste_price' => $costeUnidadFormateado,
            'sku' => $sku,
            'imagen' => $nombreImagen,
            'success' => 'success',
            'message' => 'Elaborado añadido correctamente.',
            'stock_quantity' => $unidades,
            'ing' => $nombreIngrediente,
            'fecha_elab' => $ingTag->getFechaElab(),
            'fecha_cad' => $ingTag->getFechaCad(),
            'tipo_unidad' => $ing->getUnidad(),
            'atr_name_tienda' => $ing->getAtrNameTienda(),
            'atr_valores_tienda' => $ing->getAtrValoresTienda(),
            'option_cantidad' => $ingTag->getCantidadPaquete(),
            'parent_id' => $parent_id,
            'regular_price' =>  $regularPrice,
        ];

        $ingTag->setEstado('Received');
    }

    AlmacenIngredientesDao::update($ingTag);
    $response['success'] = "Ingrediente guardado correctamente.";
}

header('Content-Type: application/json');
echo json_encode($response);
http_response_code(200);
exit;
