<?php
require __DIR__ . '/../../models/stockIngKitchen.php';
require_once __DIR__ . '/../../models/stockIngKitchenDao.php';
require_once __DIR__ . '/../../models/ingredientesDao.php';
require_once __DIR__ . '/../../models/recetasDao.php';
require_once __DIR__ . '/../../DBConnection.php';

if (isset($_GET['idIng'])) {
    $ingredientId = $_GET['idIng'];

    // Obtener datos del ingrediente desde DAO
    $tagIngrediente = StockIngKitchenDao::select($ingredientId);

    // Conectar a la base de datos usando PDO
    $conn = DBConnection::connectDB();
    if (!$conn) {
        http_response_code(500);
        echo json_encode(['error' => 'No se pudo conectar a la base de datos']);
        exit;
    }

    $idIng = $tagIngrediente->getIngredientId();

    // Usar consulta segura con parámetros para evitar inyección SQL
    $stmt = $conn->prepare("SELECT * FROM `ingredients` WHERE ID = :id");
    $stmt->bindParam(':id', $idIng, PDO::PARAM_INT);
    $stmt->execute();
    $ingredienteData = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($ingredienteData) {
        $imagen = $ingredienteData["image"];
        $unidad = $ingredienteData["unidad"];
        $name = $ingredienteData["fName"];
    } else {
        $imagen = null;
        $unidad = null;
        $name = null;
    }

    $stock = $tagIngrediente->getStock();
    $stockEcommerce = $tagIngrediente->getStockEcommerce();

    $response = [
        'id' => $tagIngrediente->getId(),
        'name' => $name,
        'unidad' => $unidad,
        'stock' => $stock,
        'stockEcommerce' => $stockEcommerce,
        'imagen' => $imagen
    ];
}

header('Content-Type: application/json');
echo json_encode($response ?? []);
