<?php

require __DIR__ . '/../../models/almacenElaboraciones.php';
require_once __DIR__ . '/../../models/almacenElaboracionesDao.php';

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

require __DIR__ . '/../../models/recetas.php';
require_once __DIR__ . '/../../models/recetasDao.php';



include('libs/phpqrcode/qrlib.php');
function getUsernameFromEmail($email)
{
    $find = '@';
    $pos = strpos($email, $find);
    $username = substr($email, 0, $pos);
    return $username;
}

function nombreReceta($idReceta)
{

    $nombreReceta = RecetasDao::selectNameById($idReceta);
    return $nombreReceta;
}


function crearElaborado($datosPost)
{


    $tagElab = new TagsElaboraciones(
        $datosPost['idElaborado'] ?? null,
        "temp/",
         '',
         $datosPost['idReceta'] ?? '',
        nombreReceta($datosPost['idReceta'] ?? ''),
        $datosPost['packaging'] ?? '',
        $datosPost['productAmount'] ?? '',
        $datosPost['fechaElab'] ?? '',
        $datosPost['caducidad'] ?? '',
        $datosPost['warehouse'] ?? '',
        $datosPost['costCurrency'] ?? '',
        $datosPost['costPrice'] ?? '',
        $datosPost['saleCurrency'] ?? '',
        $datosPost['salePrice'] ?? '',
        'https://cookcode.com?productName=' . urlencode(nombreReceta($datosPost['idReceta'] ?? '')) .
            '&productamount=' . urlencode($datosPost['productAmount'] ?? '') .
            '&fechaElab=' . urlencode($datosPost['fechaElab'] ?? '') .
            '&warehouse=' . urlencode($datosPost['warehouse'] ?? '') .
            '&costCurrency=' . urlencode($datosPost['costCurrency'] ?? '') .
            '&saleCurrency=' . urlencode($datosPost['saleCurrency'] ?? '') . '&salePrice=' .
            urlencode($datosPost['salePrice'] ?? '') . '&costPrice=' . urlencode($datosPost['costPrice'] ?? ''),
        null,
        $datosPost['idReceta'] ?? '',
        $datosPost['numRaciones'] ?? ''
    );

    TagsElaboracionesDao::insert($tagElab);

    
}


function editarElaborado($datosPost)
{

    $tagElaboradoUpdate = TagsElaboracionesDao::select($datosPost['idElaborado']);
    $tagElaboradoUpdate->setEmail("");
    $tagElaboradoUpdate->setFname(nombreReceta($datosPost['idReceta']));
    $tagElaboradoUpdate->setPackaging($datosPost['packaging']);
    $tagElaboradoUpdate->setProductamount($datosPost['productAmount']);
    $tagElaboradoUpdate->setfechaElab($datosPost['fechaElab']);
    $tagElaboradoUpdate->setFechaCad($datosPost['caducidad']);
    $tagElaboradoUpdate->setWarehouse($datosPost['warehouse']);
    $tagElaboradoUpdate->setCostCurrency($datosPost['costCurrency']);
    $tagElaboradoUpdate->setCostPrice($datosPost['costPrice']);
    $tagElaboradoUpdate->setSalePrice($datosPost['salePrice']);
    $tagElaboradoUpdate->setSaleCurrency($datosPost['saleCurrency']);
    $tagElaboradoUpdate->setCodeContents('https://cookcode.com?productName=' .
        urlencode(nombreReceta($datosPost['idReceta'] ?? '')) . '&productamount=' .
        urlencode($datosPost['productAmount'] ?? '') . '&fechaElab=' .
        urlencode($datosPost['fechaElab'] ?? '') . '&warehouse=' .
        urlencode($datosPost['warehouse'] ?? '') . '&costCurrency=' .
        urlencode($datosPost['costCurrency'] ?? '') . '&saleCurrency=' .
        urlencode($datosPost['saleCurrency'] ?? '') . '&salePrice=' .
        urlencode($datosPost['salePrice'] ?? '') . '&costPrice=' .
        urlencode($datosPost['costPrice'] ?? ''));
    $tagElaboradoUpdate->setRecetaId($datosPost['idReceta']);
    $tagElaboradoUpdate->setRationsPackage($datosPost['numRaciones']);

    TagsElaboracionesDao::update($tagElaboradoUpdate);

    return $tagElaboradoUpdate->getIDTag();
}

function crearQr($tagCreado)
{

    QRcode::png($tagCreado->getCodeContents(), $tagCreado->getTempDir() . '' . $tagCreado->getFilename() . '.png', QR_ECLEVEL_M, 3, 4);
    return $tagCreado->getFilename();
}



$idElaborado = $_POST['idElaborado'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if ($idElaborado > 0) {
       
       $id =  editarElaborado($_POST);

       $tagEditado =  TagsElaboracionesDao::select($id);
    
       $fileName = crearQr($tagEditado);
   
       $response = [
           'status' => 'success',
           'fileName' => $fileName
       ];
   
       header('Content-Type: application/json');
       echo json_encode($response);

    } else {

        crearElaborado($_POST);

        $id = TagsElaboracionesDao::getLastInsertId();
        $tagCreado =  TagsElaboracionesDao::select($id);
    
        $fileName = crearQr($tagCreado);
    
        $response = [
            'status' => 'success',
            'fileName' => $fileName
        ];
    
        header('Content-Type: application/json');
        echo json_encode($response);

    }
}




