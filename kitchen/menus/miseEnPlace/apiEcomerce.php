<?php

require_once __DIR__ . '/../../DBConnection.php';
require __DIR__ . '/../../models/stockIngKitchen.php';
require_once __DIR__ . '/../../models/stockIngKitchenDao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Registrar log de entrada
    file_put_contents('webhook_log.txt', print_r($_POST, true), FILE_APPEND);

    // Validar datos entrantes
    if (!isset($_POST['sku'], $_POST['stock'])) {
        http_response_code(400);
        exit("Error: Datos incompletos.");
    }

    $sku = $_POST['sku'];
    $stock = $_POST['stock'];

    $partes = explode("-", $sku);
    if (count($partes) < 2) {
        http_response_code(400);
        exit("Error: Formato de SKU inválido.");
    }

    $producto_id = $partes[1];
    $tipoProducto = $partes[0];

    try {
        // Conectar a la base de datos
        $conn = DBConnection::connectDB();
        if (!$conn) {
            throw new Exception("Error al conectar a la base de datos.");
        }
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if ($tipoProducto === "ING") {
            $stockIng = StockIngKitchenDao::selectByIngredientId($producto_id);
            if ($stockIng) {
                $stockIng->setStockEcommerce($stock);
                StockIngKitchenDao::update($stockIng);
            } else {
                $stmt = $conn->prepare("
                    INSERT INTO stock_ing_kitchen (ingredient_id, stock_ecommerce)
                    VALUES (:ingredient_id, :stock_ecommerce)
                ");
                $stmt->execute([
                    ':ingredient_id' => $producto_id,
                    ':stock_ecommerce' => $stock
                ]);
            }
        } elseif ($tipoProducto === "ELAB") {
            $stmt = $conn->prepare("
                INSERT INTO stock_elab_kitchen (receta_id)
                VALUES (:receta_id)
            ");
            $stmt->execute([
                ':receta_id' => $producto_id
            ]);
        } else {
            http_response_code(400);
            exit("Error: Tipo de producto desconocido.");
        }

        echo "Operación completada con éxito.";
    } catch (Exception $e) {
        http_response_code(500);
        file_put_contents('error_log.txt', $e->getMessage() . PHP_EOL, FILE_APPEND);
        exit("Error: " . $e->getMessage());
    }
} else {
    http_response_code(405);
    echo "Método no permitido.";
}
