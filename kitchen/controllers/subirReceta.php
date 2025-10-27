<?php

require_once __DIR__ . '/../models/recetasDao.php';
require_once __DIR__ . '/../models/recetas.php';
require_once __DIR__ . '/../DBConnection.php';

// Verificar que la solicitud sea POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit("Solo se permiten solicitudes POST.");
}

// Verificar si se ha enviado contenido en el archivo o formulario
if (empty($_FILES) && empty($_POST)) {
    http_response_code(400);
    exit("No se ha proporcionado ningún archivo o JSON.");
}

// Manejo de la imagen


//************************************ GUARDAR IMG PARA QUE SALGA EN TPV*****************************/
$uploadDir = './../img/recipes/';
$uploadDirTPV = './../menus/plating/img_dishes/';

// Subir la imagen al primer directorio
$uploadedFile = handleImageUpload($uploadDir);

// Copiar la imagen a la segunda ruta
$uploadedFileTPV = $uploadDirTPV . basename($uploadedFile);

// Asegúrate de que exista el directorio destino
if (!file_exists($uploadDirTPV)) {
    mkdir($uploadDirTPV, 0755, true);
}

// Copiar el archivo si fue subido correctamente
if ($uploadedFile !== './../img/sin-imagen.jpg') {
    copy($uploadedFile, $uploadedFileTPV);
}
//************************************* *************************************************************/

// Decodificar y validar el JSON
$inputJSON = $_POST['receta'];
$data = json_decode($inputJSON, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    exit("El JSON proporcionado es inválido.");
}


$pesoIngs= 0;

$pesoElabs= 0;


$peso = 0;
// Crear una nueva receta con los datos proporcionados
$recetaData = createRecipe($data, $peso, $uploadedFile);
RecetasDao::insert($recetaData);

// Obtener el ID de la receta recién creada
$idRecetaNueva = RecetasDao::selectByName($data["recipe_name"])[0]->id;

// Asociar ingredientes y elaborados a la receta solo si existen
if (!empty($data['recipe_ingredients'])) {




    $pesoIngs = array_reduce($data['recipe_ingredients'] ?? [], function ($carry, $ing) {
        if ($ing['unit'] === "Kg") {
            // Si la unidad es 'kg', sumamos directamente el amount.
            return $carry + $ing['amount'];
        } else {
            // Si no es 'kg', consultamos el peso desde la base de datos.
    
            $pesoPorUnidad = getIngredientWeight($ing['id']); // Consultar el peso del ingrediente.
            if ($pesoPorUnidad === null) {
                throw new Exception("No se encontró el peso para el ingrediente con ID: " . $ing['id']);
            }
            // Multiplicamos el peso por la cantidad (amount) y lo sumamos.
            return $carry + ($pesoPorUnidad * $ing['amount']);
        }
    }, 0);




    saveRecipeIngredients($data['recipe_ingredients'], $idRecetaNueva);
}
if (!empty($data['recipe_elabs'])) {



    $pesoElabs = array_reduce($data['recipe_elabs'] ?? [], function ($carry, $elab) {

        $objElaboracion = RecetasDao::select($elab['id']);
    
        $pesoReceta = $objElaboracion->getPeso();
        $pesoCantidadReceta = $pesoReceta * $elab['amount'];
        return $carry + $pesoCantidadReceta;
    }, 0);





    saveRecipeElaborados($data['recipe_elabs'], $idRecetaNueva);
}

$peso = $pesoIngs + $pesoElabs;

$pesoUnidad = $peso / $data['recipe_num_rations'];
setRecipeWeight($idRecetaNueva, $peso);

saveRecipeStock($idRecetaNueva);

$partesPathImg = explode('/', $uploadedFile); // Divide la cadena en un array
$nombreImg = end($partesPathImg); // Obtiene el último elemento del array


if($data["recipe_type"] == "Elaborado"){

$sku = "ELAB-" . str_pad($idRecetaNueva, 3, '0', STR_PAD_LEFT);
}else if($data["recipe_type"] == "Pre-Elaborado"){
$sku = "PRE-" . str_pad($idRecetaNueva, 3, '0', STR_PAD_LEFT);

}
  // Generar el SKU
  

  // Enviar respuesta JSON
  $response = [
      'name' => $data["recipe_name"],
      'cost_price' => 0,
      'sku' => $sku,
      'imagen' => $nombreImg,
      'status' => 'success',
      'message' => 'Ingrediente añadido correctamente.',
      'regular_price' => 0,
      'warehouse' => $data["recipe_warehouse"],
      'packaging' => $data["recipe_packaging"],
      'alergeno_name' => "",
      'peso_unidad' => $pesoUnidad,
      'descripcion_corta' =>  $data["descripcion_corta"],
      'instrucciones' =>  $data["instrucciones"],
  ];



// Enviar la respuesta
echo json_encode($response);
http_response_code(200);

/** 
 * Función para manejar la carga de imagen.
 * @param string $uploadDir Directorio de subida
 * @return string Ruta del archivo subido o imagen predeterminada
 */
function handleImageUpload($uploadDir)
{
    $defaultImage = './../img/sin-imagen.jpg';
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == UPLOAD_ERR_OK) {
        $uploadFile = $uploadDir . basename($_FILES['imagen']['name']);

        // Crear el directorio si no existe
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Mover el archivo subido
        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $uploadFile)) {
            return $uploadFile;
        } else {
            http_response_code(500);
            exit("Error al subir la imagen.");
        }
    }
    return $defaultImage;
}

/**
 * Crear una nueva instancia de receta con los datos proporcionados.
 * @param array $data Datos de la receta
 * @param float $peso Peso total de los ingredientes
 * @param string $uploadedFile Ruta de la imagen subida
 * @return Recetas Instancia de Recetas con los datos configurados
 */
function createRecipe($data, $peso, $uploadedFile)
{
    $recetaData = new Recetas;
    $recetaData->setTipo($data["recipe_type"]);
    $recetaData->setRecetaName($data["recipe_name"]);
    $recetaData->setNumRaciones($data["recipe_num_rations"]);
    $recetaData->setInstrucciones($data["instrucciones"]);
    $recetaData->setPeso($peso);
    $recetaData->setCaducidad($data["recipe_expires"]);
    $recetaData->setEmpaquetado($data["recipe_packaging"]);
    $recetaData->setLocalizacion($data["recipe_warehouse"]);
    $recetaData->setDescripcionCorta($data["descripcion_corta"]);
    $recetaData->setImagen($uploadedFile);
    $recetaData->setCategoria($data["recipe_category"]);
    return $recetaData;
}

/**
 * Guardar los ingredientes asociados a una receta en la base de datos.
 * @param array $ingredientes Lista de ingredientes de la receta
 * @param int $idReceta ID de la receta recién creada
 */
function saveRecipeIngredients($ingredientes, $idReceta)
{
    $conn = DBConnection::connectDB();
    foreach ($ingredientes as $ingrediente) {
        if (!is_null($conn)) {
            $stmt = $conn->prepare("INSERT INTO receta_ingrediente 
                                    (ID, receta, ingrediente, elaborado, cantidad, tipo_cantidad) 
                                    VALUES (null, :receta, :ingrediente, :elaborado, :cantidad, :tipo_cantidad)");
            $stmt->execute([
                'receta' => $idReceta,
                'ingrediente' => $ingrediente['id'],
                'elaborado' => null,
                'cantidad' => $ingrediente['amount'],
                'tipo_cantidad' => $ingrediente['unit']
            ]);
        }
    }
}

/**
 * Guardar los elaborados asociados a una receta en la base de datos.
 * @param array $elaborados Lista de elaborados de la receta
 * @param int $idReceta ID de la receta recién creada
 */
function saveRecipeElaborados($elaborados, $idReceta)
{
    $conn = DBConnection::connectDB();
    foreach ($elaborados as $elaborado) {
        if (!is_null($conn)) {
            $stmt = $conn->prepare("INSERT INTO receta_elaborado 
                                    (id, receta, elaborado, cantidad) 
                                    VALUES (null, :receta, :elaborado, :cantidad)");
            $stmt->execute([
                'receta' => $idReceta,
                'elaborado' => $elaborado['id'],
                'cantidad' => $elaborado['amount']
            ]);
        }
    }
}

function getIngredientWeight($id)
{
    // Conectar a la base de datos
    $conn = DBConnection::connectDB();

    // Preparar y ejecutar la consulta para obtener el peso
    $stmt = $conn->prepare("SELECT peso FROM ingredients WHERE ID = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    // Obtener el resultado
    $peso = $stmt->fetchColumn();

    // Verificar si se encontró el ingrediente
    if ($peso === false) {
        throw new Exception("No se encontró el peso para el ingrediente con ID: " . $id);
    }

    return $peso; // Devolver el peso
}


function setRecipeWeight($id, $newWeight)
{
    try {
        // Conectar a la base de datos
        $conn = DBConnection::connectDB();

        if (!is_null($conn)) {
            // Preparar y ejecutar la consulta para actualizar el peso
            $stmt = $conn->prepare("UPDATE recetas SET peso = :peso WHERE ID = :id");
            $stmt->bindParam(':peso', $newWeight, PDO::PARAM_STR); // Asegúrate de que el tipo coincida
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            // Verificar si se actualizó alguna fila
            if ($stmt->rowCount() > 0) {
                return $newWeight; // Retorna el peso actualizado
            } else {
                throw new Exception("No se pudo actualizar el peso para la receta con ID: " . $id);
            }
        } else {
            throw new Exception("No se pudo conectar a la base de datos.");
        }
    } catch (PDOException $e) {
        // Registrar el error para depuración
        error_log("Error en setRecipeWeight: " . $e->getMessage());
        throw new Exception("Error al intentar actualizar el peso.");
    }
}



function saveRecipeStock($idReceta)
{
    $conn = DBConnection::connectDB();
    
        if (!is_null($conn)) {
            $stmt = $conn->prepare("INSERT INTO stock_elab_kitchen
                                    (receta_id) 
                                    VALUES (:receta_id)");
            $stmt->execute([
                'receta_id' => $idReceta,
                
            ]);
        }
    
}

