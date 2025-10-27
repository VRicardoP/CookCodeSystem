<?php
require_once __DIR__ . '/../../../DBConnection.php';
require __DIR__ . '/../../../models/stockIngKitchen.php';
require_once __DIR__ . '/../../../models/stockIngKitchenDao.php';

$conn = DBConnection::connectDB(); // Obtener la conexión PDO

if (!$conn) {
    error_log('No se pudo establecer conexión con la base de datos.');
    echo json_encode(['status' => 'error', 'message' => 'Conexión a la base de datos fallida']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['lotes'])) {
    $lotes = json_decode($_POST['lotes'], true);

    if ($lotes === null) {
        error_log('Error al decodificar JSON: ' . json_last_error_msg());
        echo json_encode(['status' => 'error', 'message' => 'JSON inválido']);
        exit;
    }

    foreach ($lotes as $lote) {
        if (isset($lote['discounter_lots']) && is_array($lote['discounter_lots'])) {
            foreach ($lote['discounter_lots'] as $loteData) {
                // Obtener los datos del lote
                $sku = $loteData['lot_sku'];
                $cantidad_descontada = $loteData['deducted_quantity'];
                $fecha_elaboracion = $loteData['fecha_elaboracion'];
                $fecha_caducidad = $loteData['fecha_caducidad'];
                $cost_price = $loteData['cost_price'];
                $tipo_unidad = $loteData['type_unit'];

                // Dividir el SKU por "-"
                $skuParts = explode('-', $sku);
                $ingrediente_id = $skuParts[1]; // ID del ingrediente
                $cantidad = ltrim($skuParts[2], '0') /10; // Eliminar ceros a la izquierda
                $cantidad_total = $cantidad_descontada * $cantidad;
                // Preparar la consulta para insertar en la base de datos
                $sql = "
                    INSERT INTO stock_lotes_ing (ingrediente_id, lote, cantidad, unidades, elaboracion, caducidad, coste, tipo_unidad, cantidad_total)
                    VALUES (:ingrediente_id, :lote, :cantidad, :unidades, :elaboracion, :caducidad, :coste, :tipo_unidad, :cantidad_total)
                ";
                $stmt = $conn->prepare($sql);

                try {
                    // Ejecutar la inserción
                    $stmt->execute([
                        ':ingrediente_id' => $ingrediente_id,
                        ':lote' => $sku,
                        ':cantidad' => $cantidad,
                        ':unidades' => $cantidad_descontada,
                        ':elaboracion' => $fecha_elaboracion,
                        ':caducidad' => $fecha_caducidad,
                        ':coste' => $cost_price,
                        ':tipo_unidad' => $tipo_unidad,
                        ':cantidad_total' => $cantidad_total,
                    ]);
                    error_log("Lote guardado exitosamente: ingrediente_id=$ingrediente_id, cantidad=$cantidad_descontada");

                    // Actualizar el stock
                    $stockKitchen = StockIngKitchenDao::selectByIngredientId($ingrediente_id);

                    if ($stockKitchen) {
                        $stock = $stockKitchen->getStock();
                        $stockAdd = $cantidad_descontada * $cantidad;
                        $newStock = $stock + $stockAdd;

                        $stockKitchen->setStock($newStock);

                        StockIngKitchenDao::update($stockKitchen);
                        error_log("Stock actualizado: ingrediente_id=$ingrediente_id, nuevo_stock=$newStock");
                    } else {
                        error_log("No se encontró el ingrediente con ID $ingrediente_id");
                    }

                } catch (PDOException $e) {
                    error_log('Error al guardar el lote: ' . $e->getMessage());
                }
            }
        }
    }

    echo json_encode(['status' => 'success', 'message' => 'Lotes guardados correctamente']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Datos inválidos']);
}
?>
