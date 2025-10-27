<?php

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

require_once __DIR__ . '/../../DBConnection.php';

$type = $_POST['type'];

if ($type === 'ing') {
    // Obtener ID del ingrediente
    $id = $_POST['id'];

    // Conexión con PDO
    $conn = DBConnection::connectDB();
    if (!$conn) {
        die("Error de conexión a la base de datos.");
    }

    // Consulta SQL con PDO
    $queryIng = "SELECT * FROM receta_ingrediente WHERE ingrediente = ?";
    $stmt = $conn->prepare($queryIng);
    $stmt->execute([$id]);
    $resultIng = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($resultIng) > 0) {
        $recetasId = [];
        foreach ($resultIng as $rowIng) {
            $recetasId[] = $rowIng['receta'];
        }

        // Eliminar relaciones por receta
        foreach ($recetasId as $recetaId) {
            AlmacenElaboracionesDao::deleteByRecetaId($recetaId);
            TagsElaboracionesDao::deleteByRecetaId($recetaId);
        }

        // Eliminar por ingrediente
        AlmacenIngredientesDao::deleteByIngredienteId($id);
        TagsIngredientesDao::deleteByIngredienteId($id);

        // Eliminar recetas relacionadas
        foreach ($recetasId as $recetaId) {
            $receta = RecetasDao::select($recetaId);
            RecetasDao::delete($receta);
        }

        // Eliminar el ingrediente final
        $ingrediente = IngredientesDao::select($id);
        IngredientesDao::delete($ingrediente);
    }

    echo json_encode(['success' => true]);
}
