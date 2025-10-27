<?php

require __DIR__ . '/../../models/tagsIngredientes.php';
require_once __DIR__ . '/../../models/tagsIngredientesDao.php';

require __DIR__ . '/../../models/elaboraciones.php';
require_once __DIR__ . '/../../models/elaboracionesDao.php';

require __DIR__ . '/../../models/ElaboracionIngredient.php';
require_once __DIR__ . '/../../models/ElaboracionIngredientDao.php';

require __DIR__ . '/../../models/unit.php';
require_once __DIR__ . '/../../models/unitDao.php';

require __DIR__ . '/../../models/alergenos.php';
require_once __DIR__ . '/../../models/alergenosDao.php';

require __DIR__ . '/../../models/tagsElaboraciones.php';
require_once __DIR__ . '/../../models/tagsElaboracionesDao.php';

require __DIR__ . '/../../models/ingredientes.php';
require_once __DIR__ . '/../../models/ingredientesDao.php';

include('libs/phpqrcode/qrlib.php');
function getUsernameFromEmail($email)
{
    $find = '@';
    $pos = strpos($email, $find);
    $username = substr($email, 0, $pos);
    return $username;
}

function nombreIngrediente($idIngrediente)
{
    $nombreIngrediente = IngredientesDao::getNombreById($idIngrediente);
    return $nombreIngrediente;
}




function crearTagIngrediente($datosPost)
{
    $tagIngrediente = new TagsIngredientes(
        $datosPost['idElaborado'],
        "temp/",
        "",
        $datosPost['idElaborado'] ?? '',
        nombreIngrediente($datosPost['idIngrediente'] ?? ''),
        $datosPost['packaging'],
        $datosPost['productAmount'],
        $datosPost['fechaElab'],
        $datosPost['fechaCad'],
        $datosPost['warehouse'],
        $datosPost['costCurrency'],
        $datosPost['costPrice'],
        $datosPost['saleCurrency'],
        $datosPost['salePrice'],
        'https://cookcode.com?productName=' . urlencode(nombreIngrediente($datosPost['idIngrediente'] ?? '')) .
            '&productamount=' . urlencode($datosPost['productAmount'] ?? '') .
            '&fechaElab=' . urlencode($datosPost['fechaElab'] ?? '') .
            '&warehouse=' . urlencode($datosPost['warehouse'] ?? '') .
            '&costCurrency=' . urlencode($datosPost['costCurrency'] ?? '') .
            '&saleCurrency=' . urlencode($datosPost['saleCurrency'] ?? '') . '&salePrice=' .
            urlencode($datosPost['salePrice'] ?? '') . '&costPrice=' . urlencode($datosPost['costPrice'] ?? ''),
        null,
        $datosPost['idIngrediente'],
        $datosPost['cantidadPaquete']
    );

    TagsIngredientesDao::insert($tagIngrediente);
    $id = TagsIngredientesDao::getLastInsertId();
    $tagCreado =  TagsIngredientesDao::select($id);
   


    $fileName = crearQr($tagCreado);


    $response = [
        'status' => 'success',
        'fileName' => $fileName
    ];


    header('Content-Type: application/json');
    echo json_encode($response);
}

function editarTagIngrediente($datosPost)
{


    $tagIngredienteUpdate = TagsIngredientesDao::select($datosPost['idElaborado']);

    $tagIngredienteUpdate->setEmail("");
    $tagIngredienteUpdate->setFname(nombreIngrediente($datosPost['idIngrediente'] ?? ''));
    $tagIngredienteUpdate->setPackaging($datosPost['packaging']);
    $tagIngredienteUpdate->setProductamount($datosPost['productAmount']);
    $tagIngredienteUpdate->setfechaElab($datosPost['fechaElab']);
    $tagIngredienteUpdate->setFechaCad($datosPost['fechaCad']);
    $tagIngredienteUpdate->setWarehouse($datosPost['warehouse']);
    $tagIngredienteUpdate->setCostCurrency($datosPost['costCurrency']);
    $tagIngredienteUpdate->setCostPrice($datosPost['costPrice']);
    $tagIngredienteUpdate->setSalePrice($datosPost['salePrice']);
    $tagIngredienteUpdate->setSaleCurrency($datosPost['saleCurrency']);
    $tagIngredienteUpdate->setCodeContents('https://cookcode.com?productName=' . urlencode(nombreIngrediente($datosPost['idIngrediente'] ?? '')) .
        '&productamount=' . urlencode($datosPost['productAmount'] ?? '') .
        '&fechaElab=' . urlencode($datosPost['fechaElab'] ?? '') .
        '&warehouse=' . urlencode($datosPost['warehouse'] ?? '') .
        '&costCurrency=' . urlencode($datosPost['costCurrency'] ?? '') .
        '&saleCurrency=' . urlencode($datosPost['saleCurrency'] ?? '') . '&salePrice=' .
        urlencode($datosPost['salePrice'] ?? '') . '&costPrice=' . urlencode($datosPost['costPrice'] ?? ''),);
    $tagIngredienteUpdate->setIngredienteId($datosPost['idIngrediente']);
    $tagIngredienteUpdate->setCantidadPaquete($datosPost['cantidadPaquete']);
    TagsIngredientesDao::update($tagIngredienteUpdate);
}



$idElaborado = $_POST['idElaborado'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {



    if ($idElaborado > 0) {
        editarTagIngrediente($_POST);
    } else {
        crearTagIngrediente($_POST);
    }
}



function crearQr($tagCreado)
{

    QRcode::png($tagCreado->getCodeContents(), $tagCreado->getTempDir() . '' . $tagCreado->getFilename() . '.png', QR_ECLEVEL_M, 3, 4);
    return $tagCreado->getFilename();
}
