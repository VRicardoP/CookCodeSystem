
<?php

function updateElaborado(
    $idElaborado,
    $nombreReceta,
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
    $codeContents,
    $id_receta,
    $caducidad
) {


    // Determine if this is an update or an insert operation
    if ($idElaborado > 0) {
        // Update existing elaborated product
        $elaboradoUpdate = AlmacenElaboracionesDao::select($idElaborado);
        $elaboradoUpdate->setFname($nombreReceta);
        $elaboradoUpdate->setPackaging($packaging);
        $elaboradoUpdate->setRationsPackage($numRaciones);
        $elaboradoUpdate->setProductamount($productamount);
        $elaboradoUpdate->setfechaElab($fechaElab);

        $fecha = new DateTime($fechaElab);
        $fecha->modify($caducidad . ' days');
        $fechaCad = $fecha->format('Y-m-d');

        $elaboradoUpdate->setFechaCad($fechaCad);
        $elaboradoUpdate->setWarehouse($warehouse);
        $elaboradoUpdate->setCostCurrency($costCurrency);
        $elaboradoUpdate->setCostPrice($costPrice);
        $elaboradoUpdate->setSalePrice($salePrice);
        $elaboradoUpdate->setSaleCurrency($saleCurrency);
        $elaboradoUpdate->setCodeContents($codeContents);
        $elaboradoUpdate->setRecetaId($id_receta);

        AlmacenElaboracionesDao::update($elaboradoUpdate);
        $response['success'] = "Elaboración actualizada correctamente.";
        return $response;
    }
}



function insertarElaborado(
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

) {
    $response = [];
    $fecha = new DateTime($fechaElab);
    $fecha->modify($caducidad . ' days');
    $fechaCad = $fecha->format('Y-m-d');

    $elaboradosElab = getElaboradosReceta($id_receta, $link);
    $listaElabs = "";

    $listaElabs = getListaElaboradosReceta($elaboradosElab, $link);

    $ingredientesElab = getIngredientesReceta($id_receta, $link);

    $listaIng = getListaIngredientesReceta($ingredientesElab, $link);

    $codeContents = 'http://192.168.1.147:8080/kitchen/menus/elaborations/datosQrElab.php?Na=' . urlencode($nombreReceta) . '&am=' . urlencode($productamount) .
        '&fE=' . urlencode($fechaElab) . '&hou=' . urlencode($warehouse) . '&ctCu=' .  '&saP=' .
        urlencode($salePrice) . '&coP=' . urlencode($costPrice) . '&numRaciones=' . urlencode($numRaciones) . '&img=' .
        urlencode('./.' . $imagenReceta) . '&ings=' . urlencode($listaIng) . '&instruc=' . urlencode($instruccionesReceta) . '&id=' . urlencode(0);

    $almacenElab = new AlmacenElaboraciones(
        0,
        $tipoProduct,
        $nombreReceta,
        $packaging,
        $productamount,
        $fechaElab,
        $fechaCad,
        $warehouse,
        $costCurrency,
        $costPrice,
        $saleCurrency,
        $salePrice,
        $codeContents,
        $id_receta,
        $numRaciones,
        $imagenNuevoNombre,
        $estado
    );

    $returnVerificarElab = "";


    $returnVerificarElab = verificarElaborados($elaboradosElab, $numeroRaciones, $productamount, $numRaciones, $link);
    $sinStockElab = $returnVerificarElab['sinStock'];
    $response["error"] = $returnVerificarElab['response'];

    $returnVerificarIng = verificarIngredientes($ingredientesElab, $numeroRaciones, $productamount, $numRaciones, $link);
    $sinStockIng = $returnVerificarIng['sinStock'];
    $response["error"] = $returnVerificarIng['response'];

    // Si no hubo problemas de stock, proceder con la inserción de la elaboración
    if ($sinStockElab != true && $sinStockIng != true) {
        AlmacenElaboracionesDao::insert($almacenElab);
        $idElabInsertado = AlmacenElaboracionesDao::getLastInsertId();

        $elaboracionRegistrada = AlmacenElaboracionesDao::select($idElabInsertado);


        $codeContents = 'http://192.168.1.147:8080/kitchen/menus/elaborations/datosQrElab.php?Na=' . urlencode($nombreReceta) . '&am=' . urlencode($productamount) .
            '&fE=' . urlencode($fechaElab) . '&hou=' . urlencode($warehouse) . '&ctCu=' .  '&saP=' .
            urlencode($salePrice) . '&coP=' . urlencode($costPrice) . '&numRaciones=' . urlencode($numRaciones) . '&img=' .
            urlencode('./.' . $imagenReceta) . '&ings=' . urlencode($listaIng) . '&instruc=' . urlencode($instruccionesReceta) . '&id=' . urlencode($idElabInsertado);

        $elaboracionRegistrada->setCodeContents($codeContents);
        AlmacenElaboracionesDao::update($elaboracionRegistrada);


        $response['success'] = "Elaboration saved successfully.";

        // Cálculo de raciones y precios por unidad
        $totalRaciones = (float)$productamount * (float)$numRaciones;
        $precioUnidad = (float)$salePrice / (float)$totalRaciones;
        $costeUnidad = (float) $costPrice / (float)$totalRaciones;

        // Procesar la imagen y SKU
        $partesPathImagen = explode("/", $imagenReceta);
        $nombreImagen = $partesPathImagen[count($partesPathImagen) - 1];

        // Generar el SKU
        $sku = "ELAB-" . $id_receta  . "-" . str_pad($idElabInsertado, 3, '0', STR_PAD_LEFT);

        // Respuesta final con éxito
        $response = [
            'name' => $nombreReceta,
            'cost_price' => number_format($precioUnidad, 2, '.', ''),
            'coste_price' => number_format($costeUnidad, 2, '.', ''),
            'sku' => $sku,
            'imagen' => $nombreImagen,
            'success' => 'success',
            'message' => 'Elaborado añadido correctamente.',
            'stock_quantity' => $totalRaciones,
            'listaIng' => $listaIng,
            'fecha_elab' => $fechaElab,
            'fecha_cad' => $fechaCad,
        ];

        // var_dump($response['error']);
    }
    return $response;
}




function verificarIngredientes($ingredientesElab, $numeroRaciones, $productamount, $numRaciones, $pdo)
{
    $sinStock = false;
    $response = "";

    if (!empty($ingredientesElab)) {
        foreach ($ingredientesElab as $ing) {
            $idIngrediente = $ing['idIngrediente'];
            $ingrediente = IngredientesDao::select($idIngrediente);

            // Calcular la cantidad requerida
            $cantidadIngrediente = $ing['cantidadIngrediente'];
            $cantidadIngredienteUnitario = $cantidadIngrediente / $numeroRaciones;
            $cantidadProduct = $productamount * $numRaciones;
            $cantidadIng = $cantidadIngredienteUnitario * $cantidadProduct;

            // Consulta para obtener el stock del ingrediente
            $sql = "SELECT id, stock FROM stock_ing_kitchen WHERE ingredient_id = :idIngrediente ";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':idIngrediente', $idIngrediente, PDO::PARAM_INT);
            $stmt->execute();
            $resultadoStock = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($resultadoStock)) {
                foreach ($resultadoStock as $filaStock) {
                    $idStock = $filaStock['id'];
                    $stockKitchen = (float) $filaStock['stock'];

                    // Verificar si hay suficiente stock
                    if ($cantidadIng <= $stockKitchen) {
                        // Actualizar el stock
                        $nuevoStock = $stockKitchen - $cantidadIng;
                        $updateSql = "UPDATE stock_ing_kitchen SET stock = :nuevoStock WHERE id = :idStock";
                        $updateStmt = $pdo->prepare($updateSql);
                        $updateStmt->bindValue(':nuevoStock', $nuevoStock, PDO::PARAM_STR);
                        $updateStmt->bindValue(':idStock', $idStock, PDO::PARAM_INT);
                        $updateStmt->execute();
                        

                        // Actualización exitosa, salir del bucle
                        break;
                    } else {
                        // No hay suficiente stock
                        $response = "There are not enough " . $ingrediente->getFName() . " in stock.";
                        $sinStock = true;
                        break 2;
                    }
                }
            } else {
                // No se encontró stock para este ingrediente
                $response = "No se encontró stock para " . $ingrediente->getFName();
                $sinStock = true;
                break;
            }

            // Manejo de lotes
            $lotes = StockLotesIngDao::selectByIngredientId($idIngrediente);
            if ($lotes === false || $lotes === null) {
                $response = "Error al obtener los lotes del ingrediente " . $ingrediente->getFName();
                $sinStock = true;
                break;
            }
            $lotesActualizados = StockLotesIngDao::descontarStock($lotes, $cantidadIng);
            if ($lotesActualizados === false || $lotesActualizados === null) {
                $response = "Error al descontar stock de lotes para " . $ingrediente->getFName();
                $sinStock = true;
                break;
            }
        }
    }
    return [
        'response' => $response,
        'sinStock' => $sinStock,
    ];
}



function verificarElaborados($elaboradosElab, $numeroRaciones, $productamount, $numRaciones, $pdo)
{
    $sinStock = false;
    $response = [];
    if (!empty($elaboradosElab)) {

        foreach ($elaboradosElab as $elab) {
            $idElaborado = $elab['idElaborado'];
            $elaborado = RecetasDao::select($idElaborado); // Aquí no hay cambios ya que este método parece ser de otra capa (DAO)

            $cantidadElaborado = $elab['cantidadElaborado'];
            $cantidadElaboradoUnitario = $cantidadElaborado / $numeroRaciones;
            $cantidadProduct = $productamount * $numRaciones;
            $cantidadElaborado = $cantidadElaboradoUnitario * $cantidadProduct;

            // Consulta para obtener el stock del elaborado
            $sql = "SELECT * FROM stock_elab_kitchen WHERE receta_id = :idElaborado";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':idElaborado', $idElaborado, PDO::PARAM_INT);
            $stmt->execute();
            $resultadoStock = $stmt->fetchAll(PDO::FETCH_ASSOC); // Usamos fetchAll para obtener todos los resultados

            if (!empty($resultadoStock)) {
                // Si hay stock del elaborado
                foreach ($resultadoStock as $filaStock) {
                    $idStock = $filaStock['id'];
                    $elabStock = StockElabKitchenDao::select($idStock);
                    $stockKitchen = $elabStock->getStock();

                    // Verifica si hay suficiente stock
                    if ($cantidadElaborado <= $stockKitchen) {
                        // Resta la cantidad de elaborado del stock
                        $stockKitchen -= $cantidadElaborado;

                        // Actualiza el stock en la base de datos
                        $elabStock->setStock($stockKitchen);
                        StockElabKitchenDao::update($elabStock);
                    } else {
                        // No hay suficiente stock para este elaborado
                        $response["error"] = "There are not enough " . $elaborado->getReceta() . " in stock.";
                        $sinStock = true;
                        break 2; // Rompe ambos bucles si no hay suficiente stock
                    }
                }
            } else {
                // No se encontró stock para el elaborado
                $response["error"] = "No se encontró stock para " . $elaborado->getReceta();
                $sinStock = true;
                break; // Rompe el bucle si no se encuentra stock
            }


            // Manejo de lotes
            $lotes = StockLotesElabDao::selectByRecetaId($idElaborado);
            $lotesActualizados = StockLotesElabDao::descontarStock($lotes, $cantidadElaborado);
        }
    }
    return [
        'response' => $response,
        'sinStock' => $sinStock,
    ];
}


function getIngredientesReceta($id_receta, $pdo)
{
    // Consulta SQL para obtener los ingredientes de la receta
    $sql = "SELECT * FROM receta_ingrediente WHERE receta = :receta";
    $stmt = $pdo->prepare($sql);

    // Vincular el parámetro a la consulta
    $stmt->bindValue(':receta', $id_receta, PDO::PARAM_INT);

    // Ejecutar la consulta
    $stmt->execute();

    // Recuperar los resultados
    $ingredientesElab = [];
    while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $idIngrediente = $fila['ingrediente'];
        $cantidadIngrediente = $fila['cantidad'];
        $ingredientesElab[] = [
            'idIngrediente' => $idIngrediente,
            'cantidadIngrediente' => $cantidadIngrediente
        ];
    }

    return $ingredientesElab;
}


function getElaboradosReceta($id_receta, $pdo)
{
    // Get elaborations for the recipe
    $sql = "SELECT * FROM receta_elaborado WHERE receta = ?";
    $stmt = $pdo->prepare($sql);

    // Ejecuta la consulta pasando el parámetro directamente al execute
    $stmt->execute([$id_receta]);

    $elaboradosElab = [];

    while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $idElaborado = $fila['elaborado'];
        $cantidadElaborado = $fila['cantidad'];
        $elaboradosElab[] = ['idElaborado' => $idElaborado, 'cantidadElaborado' => $cantidadElaborado];
    }

    return $elaboradosElab;
}


function getListaIngredientesReceta($ingredientesElab, $pdo)
{
    $listaIng = "";

    if (!empty($ingredientesElab)) {
        foreach ($ingredientesElab as $ing) {
            // Consulta SQL para obtener el ingrediente
            $sql = "SELECT * FROM ingredients WHERE ID = :idIngrediente";
            $stmt = $pdo->prepare($sql);

            // Vincular el parámetro a la consulta
            $stmt->bindValue(':idIngrediente', $ing['idIngrediente'], PDO::PARAM_INT);

            // Ejecutar la consulta
            $stmt->execute();

            // Recuperar el ingrediente
            $fila = $stmt->fetch(PDO::FETCH_ASSOC);

            // Si se encontró el ingrediente, concatenar al string de ingredientes
            if ($fila) {
                $nombreIngrediente = $fila['fName'];
                $unidadIngrediente = $fila['unidad'];

                $listaIng .= $nombreIngrediente . " (" . $ing['cantidadIngrediente'] . " " . $unidadIngrediente . ")  ";
            }
        }
    }

    return $listaIng;
}


function getListaElaboradosReceta($elaboradosElab, $pdo)
{
    $listaElabs = "";

    if (!empty($elaboradosElab)) {
        foreach ($elaboradosElab as $elab) {

            // Obtener los detalles del elaborado
            $sql = "SELECT * FROM recetas WHERE ID = :idElaborado";

            $stmt = $pdo->prepare($sql);

            // Vincular el parámetro a la consulta
            $stmt->bindValue(':idElaborado', $elab['idElaborado'], PDO::PARAM_INT);
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC); // Utilizamos fetch para obtener una fila de resultados

            if ($resultado) {
                $nombreElaborado = $resultado['receta'];
            }

            $listaElabs .= $nombreElaborado . " (" . $elab['cantidadElaborado'] . ") ";
        }
    }

    return $listaElabs;
}



function handleImage()
{


    // Handle image upload
    $imagenNuevoNombre = "";
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $imagen = $_FILES['imagen'];
        $imagenNombre = $imagen['name'];
        $imagenTmpNombre = $imagen['tmp_name'];
        $imagenExtension = pathinfo($imagenNombre, PATHINFO_EXTENSION);
        $extensionesPermitidas = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array(strtolower($imagenExtension), $extensionesPermitidas)) {
            $response['error'] = 'Solo se permiten archivos con las siguientes extensiones: ' . implode(', ', $extensionesPermitidas);
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }

        $imagenNuevoNombre = uniqid() . '.' . $imagenExtension;
        $carpetaDestino = __DIR__ . '/../img/uploads/';
        if (!file_exists($carpetaDestino)) {
            mkdir($carpetaDestino, 0777, true);
        }
        $rutaDestino = $carpetaDestino . $imagenNuevoNombre;
        if (!move_uploaded_file($imagenTmpNombre, $rutaDestino)) {
            $response['error'] = 'Error: Hubo un error al mover el archivo.';
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }
    }
    return $imagenNuevoNombre;
}
