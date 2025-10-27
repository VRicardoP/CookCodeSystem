<?php

require __DIR__ . '/../../models/almacenElaboraciones.php';
require_once __DIR__ . '/../../models/almacenElaboracionesDao.php';

require __DIR__ . '/../../models/recetas.php';
require_once __DIR__ . '/../../models/recetasDao.php';

require __DIR__ . '/../../models/recetaIngrediente.php';
require_once __DIR__ . '/../../models/recetaIngredienteDao.php';

require __DIR__ . '/../../models/ingredientes.php';
require_once __DIR__ . '/../../models/ingredientesDao.php';

$response = [
    'error' => false,
    'message' => '',
    'sinStock' => false,
    'success' => ''
];

// Leer los datos JSON enviados en el cuerpo de la solicitud
$data = json_decode(file_get_contents('php://input'), true);

// Verificar si los datos JSON son válidos
if (json_last_error() !== JSON_ERROR_NONE) {
    $response['error'] = true;
    $response['message'] = 'Error en los datos JSON enviados';
    echo json_encode($response);
    exit;
}

// Verificar si los datos requeridos existen
if (!isset($data['idElaborado']) || !isset($data['estado'])) {
    $response['error'] = true;
    $response['message'] = 'Faltan parámetros en la solicitud';
    echo json_encode($response);
    exit;
}

$id = $data['idElaborado'];
$estado = $data['estado'];

// Obtener la elaboración por ID
$elaboracion = AlmacenElaboracionesDao::select($id);

// Verificar si se encontró la elaboración
if ($elaboracion === null) {
    $response['error'] = true;
    $response['message'] = 'No se encontró la elaboración con el ID especificado';
    echo json_encode($response);
    exit;
}

// Lógica según el estado de la elaboración
if ($estado == "Registered") {
    $elaboracion->setEstado('Sent');
} else if ($estado == "Sent") {
    $nombreReceta = $elaboracion->getFName();
    $elaboracion->getPackaging();
    $productamount = $elaboracion->getProductamount();
    $fechaElab = $elaboracion->getFechaElab();
    $fechaCad = $elaboracion->getFechaCad();
    $elaboracion->getWarehouse();
    $elaboracion->getCostCurrency();
    $costPrice = $elaboracion->getCostPrice();
    $elaboracion->getSaleCurrency();
    $salePrice = $elaboracion->getSalePrice();
    $numRaciones = $elaboracion->getRationsPackage();
    $recetaId = $elaboracion->getRecetaId();
    $tipoProduct = $elaboracion->getTipoProd();

    // Obtener receta y imagen
    $receta = RecetasDao::select($recetaId);
    $imagenReceta = $receta->getImagen();

    // Calcular raciones y precios por unidad
    $totalRaciones = (float)$productamount * (float)$numRaciones;
    $precioUnidad = (float)$salePrice / (float)$totalRaciones;
    $costeUnidad = (float)$costPrice / (float)$totalRaciones;

    // Procesar la imagen y SKU
    $partesPathImagen = explode("/", $imagenReceta);
    $nombreImagen = $partesPathImagen[count($partesPathImagen) - 1];

    // Generar el SKU
    //  $sku = "ELAB-" .$recetaId. "-" . str_pad($id, 3, '0', STR_PAD_LEFT);

    // Obtener ingredientes de la receta
    $arrayIngIds = RecetaIngredienteDao::getIngredientesByRecetaId($recetaId);
    $arrayIng = [];

    foreach ($arrayIngIds as $arrayIngId) {
        $ing = IngredientesDao::select($arrayIngId['ingrediente']);  // Aquí ajustamos el acceso al campo correcto
        if ($ing !== null) {
            $arrayIng[] = $ing;
        }
    }

    // Crear lista de ingredientes
    $listaIng = "";
    foreach ($arrayIng as $ing) {
        $recetaIng = RecetaIngredienteDao::selectByRecetaAndIngrediente($recetaId, $ing->getId());

        // Verifica si recetaIng no es null antes de acceder a sus métodos
        if ($recetaIng !== null) {
            $listaIng .= $ing->getFName() . " (" . $recetaIng->getCantidad() . " " . $ing->getUnidad() . ")  ";
        } else {
            // Si recetaIngrediente no se encuentra, puedes agregar un mensaje de error o simplemente continuar
            $listaIng .= $ing->getFName() . " (Ingrediente no encontrado) ";
        }
    }

    $regularPrice = round($totalRaciones * $precioUnidad * 1.5, 2);


    if ($tipoProduct == "Elaborado") {
        $sku = sprintf("ELAB-%d-L%d", $recetaId, $id);
        $parent_id = sprintf("ELAB-%d", $recetaId);
    } else if ($tipoProduct == "Pre-Elaborado") {

        $sku = sprintf("PRE-%d-L%d", $recetaId, $id);
        $parent_id = sprintf("PRE-%d", $recetaId);
    }


    // Respuesta final con éxito
    $response = [
        'name' => $nombreReceta,
        'cost_price' => number_format($precioUnidad, 2, '.', ''),
        'coste_price' => number_format($costeUnidad, 2, '.', ''),
        'sku' => $sku,
        'imagen' => $nombreImagen,
        'success' => 'success',
        'message' => 'Elaborado añadido correctamente.',
        'stock_quantity' => $totalRaciones,
        'listaIng' => $listaIng,
        'fecha_elab' => $fechaElab,
        'fecha_cad' => $fechaCad,
        'tipo_unidad' => 'Und',
        'parent_id' => $parent_id,
        'regular_price' =>  $regularPrice,
        'tipo_product' => $tipoProduct,
       
    ];



    // Cambiar estado de la elaboración
    $elaboracion->setEstado('Received');
}

// Actualizar la elaboración en la base de datos
AlmacenElaboracionesDao::update($elaboracion);

// Responder con éxito
$response['success'] = "Elaboración guardada correctamente.";
header('Content-Type: application/json');
echo json_encode($response);
http_response_code(200);
exit;
