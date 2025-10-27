<?php
require_once __DIR__ . '/../../models/recetasDao.php';
require_once __DIR__ . '/../../models/precioProductoDao.php';

if (isset($_GET["recipeName"])) {
    $recipeName = $_GET["recipeName"];
    $recipes = RecetasDao::selectByName($recipeName);
    

    // Preparar un array para almacenar los datos de las recetas en el formato esperado
    $formattedRecipes = array();
    foreach ($recipes as $recipe) {
        $ingredient = PreciosProductoDAO::select($recipe->getId());
        $formattedRecipe = array(
            'recipeName' => $recipe->getRecipeName(),
            'ingredient' => $ingredient->getProducto(),
            'amount' => $recipe->getAmount(),
            'units' => $ingredient->getUnidad(),
            'merma' => $ingredient->getMerma(),
            'sale' => $ingredient->getPrecio(),
            'ingrediente_id' => $recipe->getIngredientId(),
        );
        // Agregar la receta formateada al array
        $formattedRecipes[] = $formattedRecipe;
    }

    // Devolver los resultados formateados como JSON
    header('Content-Type: application/json');
    echo json_encode($formattedRecipes);
}
?>
