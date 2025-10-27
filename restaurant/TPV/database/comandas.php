<?php
require_once 'conexion.php';  // Asegúrate de que la conexión esté correcta

// Verifica si es una solicitud GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['mesa_id'])) {  // Cambié 'mesa' por 'mesa_id'
        $mesa_id = $_GET['mesa_id'];  // Cambié 'mesa' por 'mesa_id'

        // Prepara la consulta para obtener las comandas de la mesa
        $stmt = $pdo->prepare("SELECT * FROM comandas WHERE mesa_id = ?");
        $stmt->execute([$mesa_id]);  // Cambié 'mesa' por 'mesa_id'
        $comandas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Retorna las comandas en formato JSON
        echo json_encode($comandas);
    } else {
        echo json_encode(['error' => 'Mesa no especificada']);
    }

} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    // ✅ Si se desea eliminar un producto específico
    if (isset($data['accion']) && $data['accion'] === 'eliminar') {
        if (isset($data['mesa_id'], $data['producto_id'])) {  // Cambié 'mesa' por 'mesa_id'
            $mesa_id = $data['mesa_id'];  // Cambié 'mesa' por 'mesa_id'
            $producto_id = $data['producto_id'];

            $stmt = $pdo->prepare("DELETE FROM comandas WHERE mesa_id = ? AND producto_id = ?");
            if ($stmt->execute([$mesa_id, $producto_id])) {  // Cambié 'mesa' por 'mesa_id'
                echo json_encode(['success' => true, 'mensaje' => 'Producto eliminado']);
            } else {
                echo json_encode(['error' => 'No se pudo eliminar el producto']);
            }
        } else {
            echo json_encode(['error' => 'Datos incompletos para eliminar']);
        }
        exit;
    }

    // ✅ Si se desea finalizar (vaciar) la comanda
    if (isset($data['accion']) && $data['accion'] === 'finalizar') {
        if (isset($data['mesa_id'])) {  // Cambié 'mesa' por 'mesa_id'
            $mesa_id = $data['mesa_id'];  // Cambié 'mesa' por 'mesa_id'

            $stmt = $pdo->prepare("DELETE FROM comandas WHERE mesa_id = ?");
            if ($stmt->execute([$mesa_id])) {  // Cambié 'mesa' por 'mesa_id'
                echo json_encode(['success' => true, 'mensaje' => 'Comanda finalizada']);
            } else {
                echo json_encode(['error' => 'No se pudo finalizar la comanda']);
            }
        } else {
            echo json_encode(['error' => 'Mesa no especificada para finalizar']);
        }
        exit;
    }

    // ✅ Inserción o actualización de productos en la comanda
    if (isset($data['mesa_id'], $data['producto_id'], $data['cantidad'])) {  // Cambié 'mesa' por 'mesa_id'
        $mesa_id = $data['mesa_id'];  // Cambié 'mesa' por 'mesa_id'
        $producto_id = $data['producto_id'];
        $cantidad = $data['cantidad'];

        $stmt = $pdo->prepare("SELECT * FROM comandas WHERE mesa_id = ? AND producto_id = ?");
        $stmt->execute([$mesa_id, $producto_id]);  // Cambié 'mesa' por 'mesa_id'
        $comanda = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($comanda) {
            $stmt = $pdo->prepare("UPDATE comandas SET cantidad = ? WHERE id = ?");
            $stmt->execute([$cantidad, $comanda['id']]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO comandas (mesa_id, producto_id, cantidad) VALUES (?, ?, ?)");
            $stmt->execute([$mesa_id, $producto_id, $cantidad]);  // Cambié 'mesa' por 'mesa_id'
        }

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'Datos incompletos']);
    }
} else {
    echo json_encode(['error' => 'Método no soportado']);
}

?>
