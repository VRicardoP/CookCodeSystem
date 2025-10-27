<?php

require __DIR__ . '/../DBConnection.php';
require __DIR__ . '/../models/ingredientes.php';
require_once __DIR__ . '/../models/ingredientesDao.php';
require __DIR__ . '/../models/almacenElaboraciones.php';
require_once __DIR__ . '/../models/almacenElaboracionesDao.php';
require __DIR__ . '/../models/almacenIngredientes.php';
require_once __DIR__ . '/../models/almacenIngredientesDao.php';
require __DIR__ . '/../models/tagsElaboraciones.php';
require_once __DIR__ . '/../models/tagsElaboracionesDao.php';
require __DIR__ . '/../models/tagsIngredientes.php';
require_once __DIR__ . '/../models/tagsIngredientesDao.php';
require __DIR__ . '/../models/recetas.php';
require_once __DIR__ . '/../models/recetasDao.php';
require __DIR__ . '/../models/recetaIngrediente.php';
require_once __DIR__ . '/../models/recetaIngredienteDao.php';
require __DIR__ . '/../models/stockIngKitchen.php';
require_once __DIR__ . '/../models/stockIngKitchenDao.php';
require __DIR__ . '/../models/stockElabKitchen.php';
require_once __DIR__ . '/../models/stockElabKitchenDao.php';

header("Content-Type: application/json");

if (!isset($_POST['type']) || !isset($_POST['id'])) {
    echo json_encode(['success' => false, 'error' => 'Faltan parámetros obligatorios.']);
    exit;
}

$type = $_POST['type'];
$id = intval($_POST['id']);

if ($type !== 'ing') {
    echo json_encode(['success' => false, 'error' => 'Tipo no válido.']);
    exit;
}

try {
    $pdo = DBConnection::connectDB();
    if (!$pdo) {
        throw new Exception("No se pudo conectar a la base de datos.");
    }

    // Eliminar alérgenos del ingrediente
    $queryAlergenos = "DELETE FROM ingredientesalergenos WHERE id_ingrediente = ?";
    $stmt = $pdo->prepare($queryAlergenos);
    $stmt->execute([$id]);

    // Obtener recetas asociadas al ingrediente
    $queryIng = "SELECT receta FROM receta_ingrediente WHERE ingrediente = ?";
    $stmt = $pdo->prepare($queryIng);
    $stmt->execute([$id]);

    $recetasId = $stmt->fetchAll(PDO::FETCH_COLUMN);

    if ($recetasId) {
        foreach ($recetasId as $recetaId) {
            AlmacenElaboracionesDao::deleteByRecetaId($recetaId);
            StockElabKitchenDao::deleteByRecetaId($recetaId);
        }

        AlmacenIngredientesDao::deleteByIngredienteId($id);
        StockIngKitchenDao::deleteByIngredienteId($id);

        foreach ($recetasId as $recetaId) {
            $receta = RecetasDao::select($recetaId);
            if ($receta) {
                RecetaIngredienteDao::deleteByRecetaId($recetaId);
                RecetasDao::delete($receta);
            }
        }
    }

    if (IngredientesDao::deleteWithDependencies($id)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'No se pudo eliminar el ingrediente.']);
    }

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
