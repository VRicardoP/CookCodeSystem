<?php

require_once __DIR__ . '/../models/ingredientesDao.php';
require_once __DIR__ . '/../models/ingredientes.php';

require_once __DIR__ . '/../models/ingredientesAlergenos.php';
require_once __DIR__ . '/../models/ingredientesAlergenosDao.php';


require_once __DIR__ . '/../models/alergenos.php';
require_once __DIR__ . '/../models/alergenosDao.php';


// Verificar que la solicitud sea GET
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    exit("Solo se permiten solicitudes GET.");
}

// Verificar si el parámetro 'id' está presente en la URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    http_response_code(400); // Bad Request
    exit("El parámetro 'id' es requerido.");
}

$id = intval($_GET['id']); // Convertir el ID a entero para mayor seguridad

try {
    // Obtener ingrediente por ID
    $ingredient = IngredientesDao::select($id);




   $ingAlergen = IngredientesAlergenosDao::selectByIngrediente($id);

   $alergeno = AlergenoDao::select($ingAlergen->getId_alergeno());



    // Verificar si se encontró ingrediente
    if ($ingredient === null) {
        http_response_code(404); // Not Found
        exit("Ingrediente no encontrado.");
    }

    // Construir la respuesta con el ingrediente
    $response = [
        "ingrediente" => [
            "id" => $ingredient->getId(),
            "nombre" => $ingredient->getFName(),
            "merma" => $ingredient->getMerma(),
            "empaquetado" => $ingredient->getPackaging(),
            "unidad" => $ingredient->getUnidad(),
            "warehouse" => $ingredient->getWarehouse(),
            "expira_dias" => $ingredient->getCaducidad(),
            "coste" => $ingredient->getCostPrice(),
            "venta" => $ingredient->getSalePrice(),
            "saleCurrency" => $ingredient->getSaleCurrency(),
            "imagen" => $ingredient->getImage(),
            "peso" => $ingredient->getPeso(),
            "nombre_valores_tienda" => $ingredient->getAtrNameTienda(),
            "cantidades_tienda" => $ingredient->getAtrValoresTienda(),
            "alergeno" =>$alergeno->getNombre(),
            "descripcion_corta" => $ingredient->getDescripcionCorta(),
            "clasificacion_ing" => $ingredient->getClasificacionIng(),
        ],
       
    ];

    // Devolver la respuesta como JSON
    echo json_encode($response);
    http_response_code(200);
    exit();
} catch (\Throwable $th) {
    http_response_code(500); // Error interno del servidor
    echo json_encode(["error" => $th->getMessage()]);
    exit();
}
