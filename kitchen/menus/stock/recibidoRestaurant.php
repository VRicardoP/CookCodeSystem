<?php

require_once __DIR__ . '/../../models/almacenElaboraciones.php';
require_once __DIR__ . '/../../models/almacenElaboracionesDao.php';
require_once __DIR__ . '/../../models/recetas.php';
require_once __DIR__ . '/../../models/recetasDao.php';
require_once __DIR__ . '/../../models/ingredientes.php';
require_once __DIR__ . '/../../models/ingredientesDao.php';
require_once __DIR__ . '/../../DBConnection.php';

header('Content-Type: application/json');

$response = ['error' => false, 'message' => '', 'success' => []];

try {
    $conn = DBConnection::connectDB();
    $connRestaurant = DBConnection::connectDB("restaurant");

    if (!$conn || !$connRestaurant) {
        throw new Exception("Error de conexión a una de las bases de datos.");
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Método no permitido. Usa POST.');
    }

    $id = $_POST['id'] ?? '';
    if (empty($id)) {
        throw new Exception('ID del pedido no proporcionado.');
    }

    $stmt = $conn->prepare("SELECT * FROM pedidos_ecommerce WHERE id = ?");
    $stmt->execute([$id]);
    $pedidoData = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$pedidoData) {
        throw new Exception("No se encontró ningún pedido con el ID proporcionado.");
    }

    $pedido = [
        'id_pedido' => $pedidoData['pedido_id'],
        'id_restaurante' => $pedidoData['id_restaurante'],
        'fecha_pedido' => $pedidoData['fecha_pedido'],
        'total' => $pedidoData['total'],
        'nombre_cliente' => $pedidoData['nombre_cliente'],
        'email_cliente' => $pedidoData['email_cliente'],
        'telefono_cliente' => $pedidoData['telefono_cliente'],
        'direccion_cliente' => $pedidoData['direccion_cliente'],
        'estado_envio' => $pedidoData['estado_envio']
    ];

    $stmt = $conn->prepare("SELECT * FROM productos_pedido WHERE pedido_id = ?");
    $stmt->execute([$pedido['id_pedido']]);
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($productos as $producto) {
        $sku_lote = $producto['sku_lote'];
        $partes = explode('-', $sku_lote);
        $tipoProducto = $partes[0];
        $id_producto = $partes[1];

        $connRestaurant->beginTransaction();

        try {
            if ($tipoProducto === "ING") {
                $ingrediente = IngredientesDao::select($id_producto);
                $pesoUnidad = ltrim($partes[2], '0');
                $imagen = basename($ingrediente->getImage());

                $stmt = $connRestaurant->prepare("SELECT COUNT(*) FROM ingrediente WHERE ingrediente_id = ?");
                $stmt->execute([$id_producto]);
                if (!$stmt->fetchColumn()) {
                    $stmt = $connRestaurant->prepare("INSERT INTO ingrediente (ingrediente_id, nombre) VALUES (?, ?)");
                    $stmt->execute([$id_producto, $producto['nombreProducto']]);
                }

                $stmt = $connRestaurant->prepare("INSERT INTO stock (restaurante_id, ingrediente_id, cantidad_stock, unidad, precio, moneda, caducidad) VALUES (?, ?, ?, 1, 12, 2, ?)");
                $cantidad = sprintf("%s (%skg)", $producto['cantidad_lote'], $pesoUnidad);
                $stmt->execute([$pedido['id_restaurante'], $id_producto, $cantidad, $producto['fecha_caducidad']]);

            } elseif ($tipoProducto === "ELAB") {
                $receta = RecetasDao::select($id_producto);
                $imagen = basename($receta->getImagen());
                $imagenUrl = "http://localhost:8080/kitchen/img/recipes/" . $imagen;

                $stmt = $connRestaurant->prepare("SELECT COUNT(*) FROM receta WHERE receta_id = ?");
                $stmt->execute([$id_producto]);
                if (!$stmt->fetchColumn()) {
                    $stmt = $connRestaurant->prepare("INSERT INTO receta (receta_id, nombre, imagen, descripcion, descripcion_corta) VALUES (?, ?, ?, ?, ?)");
                    $stmt->execute([$id_producto, $producto['nombreProducto'], $imagenUrl, $receta->getInstrucciones(), $receta->getDescripcionCorta()]);
                }

                $stmt = $connRestaurant->prepare("SELECT COUNT(*) FROM elaborado WHERE elaborado_id = ?");
                $stmt->execute([$id_producto]);
                if (!$stmt->fetchColumn()) {
                    $stmt = $connRestaurant->prepare("INSERT INTO elaborado (elaborado_id, nombre) VALUES (?, ?)");
                    $stmt->execute([$id_producto, $producto['nombreProducto']]);
                }

                $stmt = $connRestaurant->prepare("INSERT INTO stock (restaurante_id, elaborado_id, cantidad_stock, unidad, precio, moneda, caducidad) VALUES (?, ?, ?, 1, 23, 2, ?)");
                $stmt->execute([$pedido['id_restaurante'], $id_producto, $producto['cantidad_lote'], $producto['fecha_caducidad']]);
            }

            $connRestaurant->commit();
        } catch (Exception $e) {
            $connRestaurant->rollBack();
            throw new Exception("Error al procesar producto: " . $e->getMessage());
        }
    }

    echo json_encode([
        'error' => false,
        'pedido' => $pedido,
        'productos' => $productos,
        'message' => 'Procesado correctamente.'
    ]);
} catch (Exception $e) {
    echo json_encode([
        'error' => true,
        'message' => $e->getMessage()
    ]);
    exit;
}
