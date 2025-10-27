<?php
require_once __DIR__ . '/../models/autoconsumo.php';
require_once __DIR__ . '/../models/autoconsumoDao.php';

require_once __DIR__ . '/../models/stockIngKitchen.php';
require_once __DIR__ . '/../models/stockIngKitchenDao.php';

require_once __DIR__ . '/../models/ingredientes.php';
require_once __DIR__ . '/../models/ingredientesDao.php';

header('Content-Type: application/json');

try {
    // Obtiene todos los registros de autoconsumo
    $autoconsumos = AutoconsumoDao::getAll();
    $stocks = StockIngKitchenDao::getAll();
    $data = [];

    foreach ($stocks as $stock) {

        $id = $stock->getId();

       $ingId = $stock->getIngredientId();
        $ing = IngredientesDao::select($ingId);


        $name = $ing->getFName();
        $cost = $ing->getCostPrice();
        $cantidad = $stock->getStock();


        $data[] = [
            'id' => $id,
            'name' => $name,
            'cantidad' => $cantidad,
          //  'fecha_consumo' => $autoconsumo->getFechaConsumo(),
            'coste' => $cost
        ];
    }


    if ($stocks) {
        $response = [
            'status' => 'success',
            'data' => $data
        ];
    } else {
        $response = [
            'status' => 'error',
            'message' => 'No se encontraron registros.'
        ];
    }
} catch (Exception $e) {
    $response = [
        'status' => 'error',
        'message' => 'Error: ' . $e->getMessage()
    ];
}

echo json_encode($response);
