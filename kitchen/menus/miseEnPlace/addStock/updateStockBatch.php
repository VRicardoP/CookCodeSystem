<?php
require __DIR__ . '/../../../models/stockIngKitchen.php';
require_once __DIR__ . '/../../../models/stockIngKitchenDao.php';
require_once __DIR__ . '/../../../models/ingredientesDao.php';
require_once __DIR__ . '/../../../models/autoconsumo.php';
require_once __DIR__ . '/../../../models/autoconsumoDao.php';

$response = [];

try {
    // Obtener contenido crudo de la solicitud
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    if (!isset($data['idIng'], $data['addStock'], $data['selectedValue'])) {
        throw new Exception('Parámetros inválidos.');
    }

    $ingredientStockKitchenId = (int)$data['idIng'];
    $addStock = (int)$data['addStock'];
    $selectedValue = $data['selectedValue'];

    if ($addStock <= 0) {
        throw new Exception('El valor de stock a añadir debe ser positivo.');
    }

    $ingStockKitchen = StockIngKitchenDao::select($ingredientStockKitchenId);
    if (!$ingStockKitchen) {
        throw new Exception('Ingrediente no encontrado.');
    }

    $response = [
        'status' => 'success',
        'message' => 'Stock actualizado correctamente.',
        'sku' => 'ING-' . $ingStockKitchen->getIngredientId(),
        'selected_value' => $selectedValue,
        'add_stock' => $addStock
    ];
} catch (Exception $e) {
    $response = [
        'status' => 'error',
        'message' => $e->getMessage(),
    ];
}

header('Content-Type: application/json');
echo json_encode($response);
