<?php
require_once __DIR__ . '/../models/recetasDao.php';
require_once __DIR__ . '/../models/recetas.php';

require_once __DIR__ . '/../models/recetaIngredienteDao.php';
require_once __DIR__ . '/../models/recetaIngrediente.php';

require_once __DIR__ . '/../models/recetaElaboradoDao.php';
require_once __DIR__ . '/../models/recetaElaborado.php';

require_once __DIR__ . '/../models/ingredientesDao.php';
require_once __DIR__ . '/../models/ingredientes.php';

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
    // Obtener la receta por ID
    $receta = RecetasDao::select($id);

    // Obtener los ingredientes y elaborados asociados
    $listIngs = RecetaIngredienteDao::getIngredientesByRecetaId($id);
    $listElaborados = RecetaElaboradoDao::getElaboradosByRecetaId($id);


    // Obtener detalles de los ingredientes
    $detallesIngredientes = [];
    foreach ($listIngs as $ingrediente) {
        $ingredienteId = $ingrediente['ingrediente']; // Extraer el ID del ingrediente

        $objIngrediente = IngredientesDao::select($ingredienteId);

        if ($objIngrediente) {
            // Agregar los detalles encontrados al array
            $detalleIngrediente['id'] =$objIngrediente->getId();
            $detalleIngrediente['nombre'] =$objIngrediente->getFName();
            $detalleIngrediente['unidad'] =$objIngrediente->getUnidad();
            $detalleIngrediente['cantidad'] = $ingrediente['cantidad']; // Incluye la cantidad de la receta
            $detallesIngredientes[] = $detalleIngrediente;
        }
    }

    // Obtener detalles de los elaborados
    $detallesElaborados = [];
    foreach ($listElaborados as $elaborado) {
        $elaboradoId = $elaborado['elaborado']; // Extraer el ID del elaborado
        $objElaborado = RecetasDao::select($elaboradoId);
       
       
        if ($objElaborado) {
            // Agregar los detalles encontrados al array

            $detalleElaborado['id'] = $objElaborado->getId();
            $detalleElaborado['nombre'] = $objElaborado->getReceta();
            $detalleElaborado['cantidad'] = $elaborado['cantidad']; // Incluye la cantidad de la receta
            $detallesElaborados[] = $detalleElaborado;
        }
    }



    // Verificar si se encontró la receta
    if ($receta === null) {
        http_response_code(404); // Not Found
        exit("Receta no encontrada.");
    }

    // Construir la respuesta con la receta y las listas
    $response = [
        "receta" => [
            "id" => $receta->getId(),
            "receta" => $receta->getReceta(),
            "instrucciones" => $receta->getInstrucciones(),
            "num_raciones" => $receta->getNumRaciones(),
            "imagen" => $receta->getImagen(),
            "peso" => $receta->getPeso(),
            "expira_dias" => $receta->getCaducidad(),
            "empaquetado" => $receta->getEmpaquetado(),
            "localizacion" => $receta->getLocalizacion(),
            "descripcion_corta" => $receta->getDescripcionCorta()
        ],
        "ingredientes" => $detallesIngredientes,
        "elaborados" => $detallesElaborados
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
