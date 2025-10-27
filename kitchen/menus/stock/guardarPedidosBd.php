<?php
require_once __DIR__ . '/../../DBConnection.php'; 
header('Content-Type: application/json');

$file_path = __DIR__ . '/log_recibidos.txt'; // Archivo de log para verificar si llegan los datos
$input = file_get_contents("php://input"); // Captura el JSON recibido
file_put_contents($file_path, $input . "\n----------------------\n", FILE_APPEND | LOCK_EX);

// Decodifica el JSON
$pedido = json_decode($input, true);

// Verifica si el JSON es válido
if (!$pedido || !isset($pedido['id_pedido']) || !isset($pedido['productos'])) {
    error_log("Error: JSON recibido no válido.");
    echo json_encode(["status" => "error", "message" => "Datos del pedido no válidos."]);
    exit;
}

// Conexión usando tu clase DBConnection y PDO
$conn = DBConnection::connectDB();
if (!$conn) {
    error_log("Error de conexión a la base de datos.");
    echo json_encode(["status" => "error", "message" => "Error de conexión a la base de datos."]);
    exit;
}

// Extraer los detalles del pedido
$id_pedido = $pedido['id_pedido'];
$fecha_pedido = $pedido['fecha_pedido'];
$total = $pedido['total'];
$nombre_cliente = $pedido['cliente']['nombre'];
$email_cliente = $pedido['cliente']['email'];
$telefono_cliente = $pedido['cliente']['telefono'];
$direccion_cliente = $pedido['cliente']['direccion'];
$estado_envio = "Registered";
$estado = "procesando";
$idRestaurante = $pedido['cliente']['restaurante_id'];

// Insertar pedido en la base de datos
$sql_pedido = "INSERT INTO pedidos_ecommerce 
    (pedido_id, id_restaurante, total, estado, fecha_pedido, nombre_cliente, email_cliente, telefono_cliente, direccion_cliente, estado_envio) 
    VALUES (:pedido_id, :id_restaurante, :total, :estado, :fecha_pedido, :nombre_cliente, :email_cliente, :telefono_cliente, :direccion_cliente, :estado_envio)";

$stmt_pedido = $conn->prepare($sql_pedido);

try {
    $stmt_pedido->execute([
        ':pedido_id' => $id_pedido,
        ':id_restaurante' => $idRestaurante,
        ':total' => $total,
        ':estado' => $estado,
        ':fecha_pedido' => $fecha_pedido,
        ':nombre_cliente' => $nombre_cliente,
        ':email_cliente' => $email_cliente,
        ':telefono_cliente' => $telefono_cliente,
        ':direccion_cliente' => $direccion_cliente,
        ':estado_envio' => $estado_envio,
    ]);
} catch (PDOException $e) {
    error_log("Error al guardar el pedido: " . $e->getMessage());
    echo json_encode(["status" => "error", "message" => "Error al guardar el pedido."]);
    exit;
}

// Preparar inserción de productos
$sql_producto = "INSERT INTO productos_pedido 
    (pedido_id, nombre_producto, sku_producto, cantidad, sku_lote, fecha_caducidad, cantidad_lote) 
    VALUES (:pedido_id, :nombre_producto, :sku_producto, :cantidad, :sku_lote, :fecha_caducidad, :cantidad_lote)";

$stmt_producto = $conn->prepare($sql_producto);

foreach ($pedido['productos'] as $producto) {
    $nombre_producto = $producto['nombre_producto'];
    $sku_producto = $producto['sku_producto'];
    $cantidad = $producto['cantidad'];

    if (isset($producto['informacion_lote']) && is_array($producto['informacion_lote'])) {
        foreach ($producto['informacion_lote'] as $lote) {
            $sku_lote = $lote['sku'];
            $fecha_caducidad = $lote['fecha_caducidad'];
            $cantidad_utilizada = $lote['cantidad_utilizada'];

            try {
                $stmt_producto->execute([
                    ':pedido_id' => $id_pedido,
                    ':nombre_producto' => $nombre_producto,
                    ':sku_producto' => $sku_producto,
                    ':cantidad' => $cantidad,
                    ':sku_lote' => $sku_lote,
                    ':fecha_caducidad' => $fecha_caducidad,
                    ':cantidad_lote' => $cantidad_utilizada,
                ]);
            } catch (PDOException $e) {
                error_log("Error al insertar producto: " . $e->getMessage());
                // No termina el script, solo registra error para continuar con otros productos
            }
        }
    }
}

// Respuesta JSON de éxito
echo json_encode(["status" => "success", "message" => "Pedido y productos guardados correctamente."]);
