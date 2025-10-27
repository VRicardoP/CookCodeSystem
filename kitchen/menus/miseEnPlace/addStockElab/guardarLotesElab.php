<?php

require_once __DIR__ . '/../../../DBConnection.php';
require __DIR__ . '/../../../models/stockElabKitchen.php';
require_once __DIR__ . '/../../../models/stockElabKitchenDao.php';

$conn = DBConnection::connectDB(); // Obtener la conexión PDO

if (!$conn) {
    error_log('No se pudo establecer conexión con la base de datos.');
    echo json_encode(['status' => 'error', 'message' => 'Conexión a la base de datos fallida']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['lotes'])) {
    $data = json_decode($_POST['lotes'], true);

    if ($data === null) {
        error_log('Error al decodificar JSON: ' . json_last_error_msg());
        echo json_encode(['status' => 'error', 'message' => 'JSON inválido']);
        exit;
    }

    $lotes = $data['lotes']; // Accede al array de lotes

    $processed_count = 0; // Contador de lotes procesados
    $ignored_count = 0; // Contador de lotes ignorados

    foreach ($lotes as $lote) {
        $sku = $lote['sku']; // SKU del lote
        $cantidad_descontada = $lote['deducted_quantity']; // Cantidad descontada
        $fecha_elaboracion = $lote['fecha_elaboracion']; // Fecha de elaboración
        $fecha_caducidad = $lote['fecha_caducidad']; // Fecha de caducidad
        $cost_price = $lote['cost_price']; // Precio de coste
        $tipo_unidad = $lote['type_unit']; // Tipo de unidad (puede ser null)

        // Dividir el SKU por "-" para extraer receta_id
        $skuParts = explode('-', $sku);
        $receta_id = $skuParts[1] ?? null; // Asegurarse de que exista el ID de receta

        // Filtrar lotes sin cambios
        if ($cantidad_descontada <= 0) {
            $ignored_count++;
            error_log("Lote ignorado (sin stock descontado): SKU=$sku");
            continue; // Saltar este lote
        }

        try {
            // Preparar la consulta para insertar en la base de datos
            $sql = "
                INSERT INTO stock_lotes_elab (receta_id, lote, cantidad, unidades, elaboracion, caducidad, coste, tipo_unidad, cantidad_total)
                VALUES (:receta_id, :lote, :cantidad, :unidades, :elaboracion, :caducidad, :coste, :tipo_unidad, :cantidad_total)
            ";
            $stmt = $conn->prepare($sql);

            // Ejecutar la inserción
            $stmt->execute([
                ':receta_id' => $receta_id,
                ':lote' => $sku,
                ':cantidad' => 0, // Cantidad inicial de este lote
                ':unidades' => $cantidad_descontada, // Unidades descontadas
                ':elaboracion' => $fecha_elaboracion,
                ':caducidad' => $fecha_caducidad,
                ':coste' => $cost_price,
                ':tipo_unidad' => $tipo_unidad ?? 'N/A', // Evitar valores null en la base de datos
                ':cantidad_total' => $cantidad_descontada, // Total descontado
            ]);

            $processed_count++;
            error_log("Lote procesado exitosamente: receta_id=$receta_id, lote=$sku, cantidad=$cantidad_descontada");



            $stockElab = StockElabKitchenDao::selectByRecetaId($receta_id);
            $stock = $stockElab->getStock();

            $stock = $stock +  $cantidad_descontada;

            $stockElab->setStock($stock);

            StockElabKitchenDao::update($stockElab);
        } catch (PDOException $e) {
            error_log("Error al procesar lote: SKU=$sku, Error=" . $e->getMessage());
        }
    }

    // Respuesta final con contadores
    echo json_encode([
        'status' => 'success',
        'message' => 'Procesamiento completado',
        'details' => [
            'processed_count' => $processed_count,
            'ignored_count' => $ignored_count,
        ],
    ]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Datos inválidos']);
}
