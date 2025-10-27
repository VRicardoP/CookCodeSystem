<?php

require __DIR__ . '/../models/almacenElaboraciones.php';
require_once __DIR__ . '/../models/almacenElaboracionesDao.php';
require __DIR__ . '/../models/elaboraciones.php';
require_once __DIR__ . '/../models/elaboracionesDao.php';
require __DIR__ . '/../models/ElaboracionIngredient.php';
require_once __DIR__ . '/../models/ElaboracionIngredientDao.php';
require __DIR__ . '/../models/unit.php';
require_once __DIR__ . '/../models/unitDao.php';
require __DIR__ . '/../models/alergenos.php';
require_once __DIR__ . '/../models/alergenosDao.php';
require __DIR__ . '/../models/stockIngKitchen.php';
require_once __DIR__ . '/../models/stockIngKitchenDao.php';
require __DIR__ . '/../models/stockElabKitchen.php';
require_once __DIR__ . '/../models/stockElabKitchenDao.php';
require __DIR__ . '/../models/autoconsumo.php';
require_once __DIR__ . '/../models/autoconsumoDao.php';
require_once __DIR__ . '/../models/ingredientes.php';
require_once __DIR__ . '/../models/ingredientesDao.php';
require_once __DIR__ . '/../models/stockLotesIng.php';
require_once __DIR__ . '/../models/stockLotesIngDao.php';
require_once __DIR__ . '/../models/stockLotesElab.php';
require_once __DIR__ . '/../models/stockLotesElabDao.php';

require_once __DIR__ . '/../models/recetas.php';
require_once __DIR__ . '/../models/recetasDao.php';
require_once __DIR__ . '/../DBConnection.php';
require_once __DIR__ . '/funcionesCrearElaborado.php';

// Initialize response array
$response = [
    'error' => false,
    'message' => '',
    'sinStock' => false,
    'success' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $imagenNuevoNombre = handleImage();

    $link = DBConnection::connectDB();



    // Retrieve POST data
    $idElaborado = $_POST['idElaborado'] ?? 0;
    $tipoProduct = $_POST['tipoProduct'] ?? '';
    $merma = $_POST['merma'] ?? '';
    $packaging = $_POST['packaging'] ?? '';
    $numRaciones = $_POST['numRaciones'] ?? 0;
    $productamount = $_POST['productAmount'] ?? '';
    $fechaElab = $_POST['fechaElab'] ?? '';
    $caducidad = $_POST['caducidad'] ?? '';
    $warehouse = $_POST['warehouse'] ?? '';
    $costCurrency = $_POST['costCurrency'] ?? '';
    $costPrice = $_POST['costPrice'] ?? 0;
    $salePrice = $_POST['salePrice'] ?? 0;
    $saleCurrency = $_POST['saleCurrency'] ?? '';
    $ingredientesJson = $_POST['ingredientData'] ?? '';
    $id_receta = $_POST['idReceta'] ?? 0;
    $estado = "Registered";

    $fecha = new DateTime($fechaElab);
    $fecha->modify($caducidad . ' days');
    $fechaCad = $fecha->format('Y-m-d');

    
    
    // Check if receta ID is provided
    if ($id_receta <= 0) {
        $response['error'] = "error : ID de receta no válido";
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
    
    $receta = RecetasDao::select($id_receta);
    
    
    $nombreReceta = $receta->getReceta();
    $imagenReceta = $receta->getImagen();
    $instruccionesReceta = $receta->getInstrucciones();
    $numeroRaciones = $receta->getNumRaciones();
    $tipoReceta = $receta->getTipo();

   $response = insertarElaborado(
        $nombreReceta,
        $tipoReceta,
        $packaging,
        $numRaciones,
        $productamount,
        $fechaElab,
        $fechaCad,
        $warehouse,
        $costCurrency,
        $costPrice,
        $salePrice,
        $saleCurrency,
        $id_receta,
        $merma,
        $caducidad,
        $imagenNuevoNombre,
        $tipoProduct,
        $estado,
        $imagenReceta,
        $instruccionesReceta,
        $numeroRaciones,
        $link
    );

    // Return the JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
    http_response_code(200);
    exit;
}
