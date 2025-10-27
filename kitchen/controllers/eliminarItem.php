<?php

require __DIR__ . '/../DBConnection.php';
require __DIR__ . '/../models/almacenElaboraciones.php';
require_once __DIR__ . '/../models/almacenElaboracionesDao.php';
require __DIR__ . '/../models/almacenIngredientes.php';
require_once __DIR__ . '/../models/almacenIngredientesDao.php';

header("Content-Type: application/json");

$id = $_POST['id'] ?? null;
$type = $_POST['type'] ?? null;

if (!$id || !$type) {
    echo json_encode(['success' => false, 'error' => 'Parámetros inválidos.']);
    exit;
}

try {
    $pdo = DBConnection::connectDB();
    if (!$pdo) {
        throw new Exception("Error de conexión a la base de datos.");
    }

    if ($type === 'elab') {
        $elaboracion = AlmacenElaboracionesDao::select($id);
        AlmacenElaboracionesDao::delete($elaboracion);

    } elseif ($type === 'ing') {
        // Si tienes un método DAO puedes usarlo aquí
        // $ingrediente = AlmacenIngredientesDao::select($id);
        // AlmacenIngredientesDao::delete($ingrediente);

        // Eliminar alérgenos
        $stmt = $pdo->prepare("DELETE FROM ingredientesalergenos WHERE id_ingrediente = ?");
        $stmt->execute([$id]);

        // Eliminar del stock
        $stmt = $pdo->prepare("DELETE FROM stock_ing_kitchen WHERE ingredient_id = ?");
        $stmt->execute([$id]);

        // Eliminar ingrediente
        $stmt = $pdo->prepare("DELETE FROM ingredients WHERE ID = ?");
        $stmt->execute([$id]);
    }

    echo json_encode(['success' => true]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
