<?php
require_once __DIR__ . '/../../models/tagsElaboracionesDao.php';
require_once __DIR__ . '/../../models/tagsIngredientesDao.php';

if (isset($_GET['id'])) {
    $elaboradoId = $_GET['id'];
    $tagElaboracion = TagsElaboracionesDao::select($elaboradoId);

    $host = "localhost";
    $username = "root";
    $password = "";
    $database = "kitchentag";
    /* Attempt to connect to MySQL database */
    $link = new mysqli($host, $username, $password, $database);

    // Check connection
    if ($link->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }



    $idReceta = $tagElaboracion->getRecetaId();



    $queryReceta = "SELECT * FROM `recetas` WHERE id = $idReceta;";


    $resultReceta = $link->query($queryReceta);

    if ($resultReceta->num_rows > 0) {
        while ($rowReceta = $resultReceta->fetch_assoc()) {

            $imagen = $rowReceta["imagen"];
        }
    }










    $queryRecetaIngredient = "SELECT * FROM `receta_ingrediente` WHERE receta = $idReceta;";

    $ingredients = array();
    $resultRecetaIngredient = $link->query($queryRecetaIngredient);

    if ($resultRecetaIngredient->num_rows > 0) {
        while ($rowRecetaIngredient = $resultRecetaIngredient->fetch_assoc()) {

            $ingredients[] = array(
                'ingrediente_id' => $rowRecetaIngredient['ingrediente'],
                'cantidad' => $rowRecetaIngredient['cantidad'],
            );
        }
    }



    $ingredientsName = array();
    $alergenosId = array();

    foreach ($ingredients as $ingredient) {
        $queryIngredient = "SELECT * FROM `ingredients` WHERE ID = " . $ingredient['ingrediente_id'] . ";";


        $resultIngredient = $link->query($queryIngredient);

        if ($resultIngredient->num_rows > 0) {
            while ($rowIngredient = $resultIngredient->fetch_assoc()) {

             
                $ingredientsName[] = array(
                    'nombre_ingrediente' => $rowIngredient["fName"],
                    'cantidad' => $ingredient['cantidad'],
                    'unidad' => $rowIngredient['unidad'],
                );
            }
        }


        $queryIngredientAlergeno = "SELECT * FROM `ingredientesalergenos` WHERE id_ingrediente = ". $ingredient['ingrediente_id'] .";";

        $resultIngredientAlergeno = $link->query($queryIngredientAlergeno);

        if ($resultIngredientAlergeno->num_rows > 0) {
            while ($rowIngredientAlergeno = $resultIngredientAlergeno->fetch_assoc()) {

                $alergenosId[] = $rowIngredientAlergeno["id_alergeno"];
            }
        }
    }



    $alergenosNombre = array();

    foreach ($alergenosId as $alergenoId) {


        $queryAlergeno = "SELECT * FROM `alergenos` WHERE id = $alergenoId;";

        $resultAlergeno = $link->query($queryAlergeno);

        if ($resultAlergeno->num_rows > 0) {
            while ($rowAlergeno = $resultAlergeno->fetch_assoc()) {

                $alergenosNombre[] = $rowAlergeno["nombre"];
            }
        }
    }






    $response['nombre'] = $tagElaboracion->getFName();
    $packaging = $tagElaboracion->getPackaging();
    $amount = $tagElaboracion->getProductamount();

    $response['empaquetado'] = $amount . '(' . $packaging . ')';
    $response['raciones'] = $tagElaboracion->getRationsPackage();
    $response['almacenaje'] = $tagElaboracion->getWarehouse();
    $fechaElab = new DateTime($tagElaboracion->getFechaElab());
    $fechaElabFormatted = $fechaElab->format('d-m-Y');

    $response['fechaElab'] = $fechaElabFormatted;
    $fechaCad = new DateTime($tagElaboracion->getFechaCad());
    $fechaCadFormatted = $fechaCad->format('d-m-Y');


    $response['fechaCad'] = $fechaCadFormatted;
    $monedaCost = $tagElaboracion->getCostCurrency();
    switch ($monedaCost) {
        case 'Euro':
            $cost =  $tagElaboracion->getCostPrice() . "&euro;";
            break;
        case 'Dirham':
            $cost =  $tagElaboracion->getCostPrice() . "&#x62F;&#x2E;&#x625;";
            break;
        case 'Yen':
            $cost =  $tagElaboracion->getCostPrice() . "&yen;";
            break;
        case 'Dolar':
            $cost =  $tagElaboracion->getCostPrice() . "&dollar;";
            break;

        default:
            # code...
            break;
    }
    $response['coste'] = $cost;
    $monedasale = $tagElaboracion->getSaleCurrency();
    switch ($monedasale) {
        case 'Euro':
            $sale =  $tagElaboracion->getSalePrice() . "&euro;";
            break;
        case 'Dirham':
            $sale =  $tagElaboracion->getSalePrice() . "&#x62F;&#x2E;&#x625;";
            break;
        case 'Yen':
            $sale =  $tagElaboracion->getSalePrice() . "&yen;";
            break;
        case 'Dolar':
            $sale =  $tagElaboracion->getSalePrice() . "&dollar;";
            break;

        default:
            # code...
            break;
    }
    $response['venta'] = $sale;
    $response['imagen'] = $imagen;

    $response['ingredientes'] = array();

    for ($i = 0; $i < count($ingredientsName); $i++) {
        $response['ingredientes'][] = array(
            'nombre' => $ingredientsName[$i]['nombre_ingrediente'],
            'cantidad' => $ingredientsName[$i]['cantidad'],
            'unidad' => $ingredientsName[$i]['unidad'],
            'alergeno' => $alergenosNombre[$i] != "Ninguno" ? $alergenosNombre[$i] : null
        );
    }
}
else if(isset($_GET['idIng'])){
    $ingredientId = $_GET['idIng'];
    $tagIngrediente = TagsIngredientesDao::select($ingredientId);

    $host = "localhost";
    $username = "root";
    $password = "";
    $database = "kitchentag";
    /* Attempt to connect to MySQL database */
    $link = new mysqli($host, $username, $password, $database);

    // Check connection
    if ($link->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }



    $idIng = $tagIngrediente->getIngredienteId();



    $queryIngrediente = "SELECT * FROM `ingredients` WHERE ID = $idIng;";


    $resultIngrediente = $link->query($queryIngrediente);

    if ($resultIngrediente->num_rows > 0) {
        while ($rowIngrediente = $resultIngrediente->fetch_assoc()) {

            $imagen = $rowIngrediente["image"];
            $unidad = $rowIngrediente["unidad"];
        }
    }


    $response['nombre'] = $tagIngrediente->getFName();
    $packaging = $tagIngrediente->getPackaging();
    $amount = $tagIngrediente->getProductamount();

    $response['empaquetado'] = $amount . '(' . $packaging . ')';


    $cantidad = $tagIngrediente->getCantidadPaquete().$unidad ;
    $response['cantidad'] = $cantidad;
    $response['almacenaje'] = $tagIngrediente->getWarehouse();
    $fechaElab = new DateTime($tagIngrediente->getFechaElab());
    $fechaElabFormatted = $fechaElab->format('d-m-Y');

    $response['fechaElab'] = $fechaElabFormatted;
    $fechaCad = new DateTime($tagIngrediente->getFechaCad());
    $fechaCadFormatted = $fechaCad->format('d-m-Y');


    $response['fechaCad'] = $fechaCadFormatted;
    $monedaCost = $tagIngrediente->getCostCurrency();
    switch ($monedaCost) {
        case 'Euro':
            $cost =  $tagIngrediente->getCostPrice() . "&euro;";
            break;
        case 'Dirham':
            $cost =  $tagIngrediente->getCostPrice() . "&#x62F;&#x2E;&#x625;";
            break;
        case 'Yen':
            $cost =  $tagIngrediente->getCostPrice() . "&yen;";
            break;
        case 'Dolar':
            $cost =  $tagIngrediente->getCostPrice() . "&dollar;";
            break;

        default:
            # code...
            break;
    }
    $response['coste'] = $cost;
    $monedasale = $tagIngrediente->getSaleCurrency();
    switch ($monedasale) {
        case 'Euro':
            $sale =  $tagIngrediente->getSalePrice() . "&euro;";
            break;
        case 'Dirham':
            $sale = $tagIngrediente->getSalePrice() . "&#x62F;&#x2E;&#x625;";
            break;
        case 'Yen':
            $sale =  $tagIngrediente->getSalePrice() . "&yen;";
            break;
        case 'Dolar':
            $sale =  $tagIngrediente->getSalePrice() . "&dollar;";
            break;

        default:
            # code...
            break;
    }
    $response['venta'] = $sale;
    $response['imagen'] = $imagen;

   
}

header('Content-Type: application/json');
echo json_encode($response);
