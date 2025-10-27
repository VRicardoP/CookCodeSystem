<?php
require __DIR__ . '/../../models/stockIngKitchen.php';
require_once __DIR__ . '/../../models/stockIngKitchenDao.php';
require_once __DIR__ . '/../../models/ingredientesDao.php';
require_once __DIR__ . '/../../models/autoconsumo.php';
require_once __DIR__ . '/../../models/autoconsumoDao.php';


$response = [];

if (isset($_GET['idIng']) && isset($_GET['addStock'])) {
    $ingredientId = (int)$_GET['idIng'];
    $addStock = (float)$_GET['addStock'];



    if ($addStock > 0) { // Validar que el stock a añadir sea positivo
        $tagIngrediente = StockIngKitchenDao::select($ingredientId);


        $ing = IngredientesDao::select($tagIngrediente->getIngredientId()); // Obtener el ingrediente por ID
        $name = $ing->getFName(); // Obtener el nombre del ingrediente
        $fechaHoy = date("Y-m-d"); // Obtener la fecha de hoy
        $coste = $ing->getCostPrice();


        if ($tagIngrediente) {



            // Crear un nuevo objeto Autoconsumo
            $consumo = new Autoconsumo(
                0, // ID del autoconsumo (0 para nuevo registro)
                $name, // Nombre del ingrediente
                $addStock, // Cantidad añadida
                $fechaHoy, // Fecha de hoy
                $coste
            );

            // Aquí podrías proceder a insertar el objeto $consumo en la base de datos usando tu DAO
            AutoconsumoDao::insert($consumo);





            // Obtiene el stock actual y añade el nuevo stock
            $currentStock = $tagIngrediente->getStock();
            $newStock = $currentStock + $addStock;
            $stockEcommerce = $tagIngrediente->getStockEcommerce();
            //  $newStockEcommerce = (float)$stockEcommerce - (float)$addStock;
            $tagIngrediente->setStock($newStock);
            //   $tagIngrediente->setStockEcommerce($newStockEcommerce);



            // Actualiza el registro en la base de datos

            $result = StockIngKitchenDao::update($tagIngrediente);

            if ($result) {
                $response['status'] = 'success';
                $response['message'] = 'Stock actualizado correctamente.';
                $response['newStock'] = $newStock;
                $response['sku'] = 'ING-' . $tagIngrediente->getIngredientId();
                $response['stock_ecommerce'] = $tagIngrediente->getStockEcommerce();
                $response['addStock'] = $addStock;
            } else {
                $response['status'] = 'error';
                $response['message'] = 'Error al actualizar el stock.';
            }
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Ingrediente no encontrado.';
        }
    } else {
        $response['status'] = 'error';
        $response['message'] = 'El valor de stock a añadir debe ser positivo.';
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'Parámetros inválidos.';
}

header('Content-Type: application/json');
echo json_encode($response);
