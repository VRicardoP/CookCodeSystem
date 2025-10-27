<?php
require_once __DIR__ . '/../DBConnection.php';

header('Content-Type: application/json');

$conn = DBConnection::connectDB();
if (!$conn) exit;

// SQL query to fetch dishes
$sql = "SELECT id, nombre, imagen, instrucciones FROM platos";

try {
    $stmt = $conn->query($sql);
    $dishes = [];

    while ($dish = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $dishId = $dish['id'];

        // Get ingredients
        $ingredientStmt = $conn->prepare("SELECT nombre, cantidad, unidad FROM platos_ingrediente WHERE id_plato = ?");
        $ingredientStmt->execute([$dishId]);
        $ingredients = $ingredientStmt->fetchAll(PDO::FETCH_ASSOC);

        // Get preelaborados
        $preStmt = $conn->prepare("SELECT nombre, cantidad FROM platos_preelaborados WHERE id_plato = ?");
        $preStmt->execute([$dishId]);
        $preElaborados = $preStmt->fetchAll(PDO::FETCH_ASSOC);

        $dish['ingredients'] = $ingredients;
        $dish['pre_elaborados'] = $preElaborados;

        $dishes[] = $dish;
    }

    echo json_encode($dishes);
} catch (PDOException $e) {
    echo json_encode(["error" => "Query failed: " . $e->getMessage()]);
}
