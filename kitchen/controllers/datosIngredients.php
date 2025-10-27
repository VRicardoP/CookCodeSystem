<?php
  require_once __DIR__ . '/../models/ingredientesDao.php';

  $listIngredients = IngredientesDAO::getAll();
  
  // Preparar un array para almacenar los datos de las recetas en el formato esperado
  $formattedIngs = array();
  foreach ($listIngredients as $ing) {
    //   $ingredient = PreciosProductoDAO::select($recipe->getId());
      $formattedIng = array(
          'id' => $ing->getId(),
          'producto' => $ing->getfName(),
          'unidad' => $ing->getUnidad(),
          'precio' => $ing->getCostPrice(),
          'merma' => $ing->getMerma(),
      );
      // Agregar la receta formateada al array
      $formattedIngs[] = $formattedIng;
  }

  header('Content-Type: application/json');
  echo json_encode($formattedIngs);
?>
