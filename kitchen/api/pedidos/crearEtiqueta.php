<?php

require_once './../../models/ingredientesDao.php';
require_once './../../models/almacenIngredientesDao.php';
require_once './../../models/almacenIngredientes.php';

function crearEtiqueta($id, $cant, $tipo_cantidad){
    $ing = IngredientesDao::select($id);

    // Asignar valores a los campos de la etiqueta
    $tipoProd = "Ingredient";
    $fName = $ing->getFName();
    $packaging = $ing->getPackaging();
    $cantidad = $cant;
    $fechaElab = date('Y-m-d');
    $date = new DateTime($fechaElab);
    $date->modify('+' . $ing->getCaducidad() . ' days');
    $fechaCad = $date->format('Y-m-d');
    $warehouse = $ing->getWarehouse();
    $costCurrency = $ing->getSaleCurrency();
    $costPrice = $ing->getCostPrice() * $cant * $tipo_cantidad;
    $saleCurrency = $ing->getSaleCurrency();
    $salePrice = $costPrice * 1.5;
    $ingrediente_id = $id;
    $cantidad_paquete = $tipo_cantidad;
    $estado = "Registered";

    // imagen
    $img = $ing->getImage();

    // Initialize response array
    $response = [
        'error' => false,
        'message' => '',
        'sinStock' => false,
        'success' => ''
    ];

    $almacenIng = new AlmacenIngredientes(
        0,
        $tipoProd,
        $fName,
        $packaging,
        $cantidad,
        $fechaElab,
        $fechaCad,
        $warehouse,
        $costCurrency,
        $costPrice,
        $saleCurrency,
        $salePrice,
        'http://192.168.1.147:8080/kitchen/menus/elaborations/datosQrIng.php?productName=' . urlencode($fName) .
            '&img=' . urlencode('./.'.$img) .
            '&productamount=' . urlencode($cantidad) .
            '&pesoPaquete=' . urlencode($cantidad_paquete) .
            '&fechaElab=' . urlencode($fechaElab) .
            '&warehouse=' . urlencode($warehouse) .
            '&costCurrency=' . urlencode($costCurrency) .
            '&saleCurrency=' . urlencode($saleCurrency) .
            '&salePrice=' . urlencode($salePrice) .
            '&costPrice=' . urlencode($costPrice),
        $ingrediente_id,
        $cantidad_paquete,
        $estado
    );

    // Manejo de stock
    $sinStock = false;

    $link = mysqli_connect('localhost', 'root', '', 'kitchentag');
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


    AlmacenIngredientesDao::insert($almacenIng);

    $idUltimoInsetado = AlmacenIngredientesDao::getLastInsertId();

    $almIng = AlmacenIngredientesDao::select($idUltimoInsetado);


    $codeContents = 'http://192.168.1.147:8080/kitchen/menus/elaborations/datosQrIng.php?productName=' . urlencode($fName) .
        '&img=' . urlencode('./.'.$img) .
        '&productamount=' . urlencode($cantidad) .
        '&pesoPaquete=' . urlencode($cantidad_paquete) .
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

    $partesPathImagen = explode("/", $img);
    $nombreImagen = $partesPathImagen[count($partesPathImagen) - 1];

    $unidades = $cantidad * $cantidad_paquete;

    $precioUnidad = floatval($salePrice) / floatval($unidades);
    $precioUnidadFormateado = number_format($precioUnidad, 2, '.', '');


    $costeUnidad = floatval($costPrice) / floatval($unidades);
    $costeUnidadFormateado = number_format($costeUnidad, 2, '.', '');

    // Generar el SKU
    $sku = "ING-" . $idUltimoInsetado . "-" . str_pad($id, 3, '0', STR_PAD_LEFT);

    $response = [
        'name' => $fName,
        'sale_price' => $precioUnidadFormateado,
        'coste_price' => $costeUnidadFormateado,
        'sku' => $sku,
        'imagen' => $img,
        'success' => 'success',
        'message' => 'Elaborado añadido correctamente.',
        'stock_quantity' => $unidades,
        'ing' => $fName,
        'fecha_elab' =>  $fechaElab,
        'fecha_cad' => $fechaCad,
    ];
    // }

    $link->close();
    }
?>