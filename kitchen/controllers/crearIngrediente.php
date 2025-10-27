<?php

require __DIR__ . '/../models/almacenIngredientes.php';
require_once __DIR__ . '/../models/almacenIngredientesDao.php';
require __DIR__ . '/../models/ingredientes.php';
require_once __DIR__ . '/../models/ingredientesDao.php';
require __DIR__ . '/../models/unit.php';
require_once __DIR__ . '/../models/unitDao.php';
require __DIR__ . '/../models/alergenos.php';
require_once __DIR__ . '/../models/alergenosDao.php';
require __DIR__ . '/../models/stockIngKitchen.php';
require_once __DIR__ . '/../models/stockIngKitchenDao.php';

// Initialize response array
$response = [
    'error' => false,
    'message' => '',
    'sinStock' => false,
    'success' => ''
];

define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'kitchentag');

/* Attempt to connect to MySQL database */
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($link === false) {
    $response['error'] = "Error: No se pudo conectar a la base de datos. " . mysqli_connect_error();
    echo json_encode($response);
    exit;
}

// Recoger los datos enviados por AJAX
$idIngedienteAlmacen = $_POST['idIngedienteAlmacen'];
$id_ingredient = $_POST['idIngredient'];
$tipoProduct = $_POST['tipoProduct'];
$merma = $_POST['merma'];
$packaging = $_POST['packaging'];
$cantidad = $_POST['cantidad'];
$pesoPaquete = $_POST['pesoPaquete'];
$unidad = $_POST['unidad'];
$fechaElab = $_POST['fechaElab'];
$caducidad = $_POST['caducidad'];
$warehouse = $_POST['warehouse'];
$costCurrency = $_POST['costCurrency'];
$costPrice = $_POST['costPrice'];
$salePrice = $_POST['salePrice'];
$saleCurrency = $_POST['saleCurrency'];
$estado = "Registered";

// Obtener nombre del ingrediente
$nombreIngrediente = "";
$imagenIngrediente  = "";
$sql = "SELECT fName, image FROM ingredients WHERE id = ?";
$stmt = $link->prepare($sql);
$stmt->bind_param("i", $id_ingredient);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    $fila = $resultado->fetch_assoc();
    $nombreIngrediente = $fila['fName'];
    $imagenIngrediente = $fila['image'];
} else {
    $response['error'] = "No se encontraron resultados para el ingrediente.";
    echo json_encode($response);
    exit;
}

// Calcular fecha de caducidad
$fecha = new DateTime($fechaElab);
$fecha->modify($caducidad . ' days');
$fechaCad = $fecha->format('Y-m-d');

// Crear objeto AlmacenIngredientes
$almacenIng = new AlmacenIngredientes(
    0,
    $tipoProduct,
    $nombreIngrediente,
    $packaging,
    $cantidad,
    $fechaElab,
    $fechaCad,
    $warehouse,
    $costCurrency,
    $costPrice,
    $saleCurrency,
    $salePrice,
    'http://192.168.1.147:8080/kitchen/menus/elaborations/datosQrIng.php?productName=' . urlencode($nombreIngrediente) .
        '&img=' . urlencode('./.'.$imagenIngrediente) .
        '&productamount=' . urlencode($cantidad) .
        '&pesoPaquete=' . urlencode($pesoPaquete) .
        '&fechaElab=' . urlencode($fechaElab) .
        '&warehouse=' . urlencode($warehouse) .
        '&costCurrency=' . urlencode($costCurrency) .
        '&saleCurrency=' . urlencode($saleCurrency) .
        '&salePrice=' . urlencode($salePrice) .
        '&costPrice=' . urlencode($costPrice),
    $id_ingredient,
    $pesoPaquete,
    $estado
);

// Manejo de stock
$sinStock = false;

$sql = "SELECT id, stock FROM stock_ing_kitchen WHERE ingredient_id = ?";
$stmt = $link->prepare($sql);
$stmt->bind_param("i", $_POST['idIngredient']);
$stmt->execute();
$resultadoStock = $stmt->get_result();

if ($resultadoStock->num_rows > 0) {
    $filaStock = $resultadoStock->fetch_assoc();
    $idStock = $filaStock['id'];
    $stockKitchen = $filaStock['stock'];

    if ($cantidad <= $stockKitchen) {
        $stockKitchen -= $cantidad;
        $sqlUpdateStock = "UPDATE stock_ing_kitchen SET stock = ? WHERE id = ?";
        $stmtUpdate = $link->prepare($sqlUpdateStock);
        $stmtUpdate->bind_param("ii", $stockKitchen, $idStock);
        $stmtUpdate->execute();
    } else {
        $response['error'] = "No hay suficiente ingrediente en stock.";
        $sinStock = true;
    }
} else {
    $response['error'] = "No se encontró stock para el ingrediente.";
    $sinStock = true;
}

// if (!$sinStock) {


AlmacenIngredientesDao::insert($almacenIng);

$idUltimoInsetado = AlmacenIngredientesDao::getLastInsertId();

$almIng = AlmacenIngredientesDao::select($idUltimoInsetado);


$codeContents = 'http://192.168.1.147:8080/kitchen/menus/elaborations/datosQrIng.php?productName=' . urlencode($nombreIngrediente) .
    '&img=' . urlencode('./.'.$imagenIngrediente) .
    '&productamount=' . urlencode($cantidad) .
    '&pesoPaquete=' . urlencode($pesoPaquete) .
    '&fechaElab=' . urlencode($fechaElab) .
    '&warehouse=' . urlencode($warehouse) .
    '&costCurrency=' . urlencode($costCurrency) .
    '&saleCurrency=' . urlencode($saleCurrency) .
    '&salePrice=' . urlencode($salePrice) .
    '&costPrice=' . urlencode($costPrice) .
    '&id=' . urlencode($almIng->getID());

$almIng->setCodeContents($codeContents);

AlmacenIngredientesDao::update($almIng);


$response['success'] = "Elaboración guardada correctamente.";

$partesPathImagen = explode("/", $imagenIngrediente);
$nombreImagen = $partesPathImagen[count($partesPathImagen) - 1];

$unidades = $cantidad * $pesoPaquete;

$precioUnidad = floatval($salePrice) / floatval($unidades);
$precioUnidadFormateado = number_format($precioUnidad, 2, '.', '');


$costeUnidad = floatval($costPrice) / floatval($unidades);
$costeUnidadFormateado = number_format($costeUnidad, 2, '.', '');

// Generar el SKU
$sku = "ING-" . $idUltimoInsetado . "-" . str_pad($id_ingredient, 3, '0', STR_PAD_LEFT);

$response = [
    'name' => $nombreIngrediente,
    'sale_price' => $precioUnidadFormateado,
    'coste_price' => $costeUnidadFormateado,
    'sku' => $sku,
    'imagen' => $nombreImagen,
    'success' => 'success',
    'message' => 'Elaborado añadido correctamente.',
    'stock_quantity' => $unidades,
    'ing' => $nombreIngrediente,
    'fecha_elab' =>  $fechaElab,
    'fecha_cad' => $fechaCad,
];
// }

$link->close();

// Return the JSON response
header('Content-Type: application/json');
echo json_encode($response);
exit;
