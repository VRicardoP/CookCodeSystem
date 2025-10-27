<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../models/ingredientesDao.php';
require_once __DIR__ . '/../models/ingredientes.php';
require_once __DIR__ . '/../DBConnection.php';

// Verificar si la solicitud es POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit("Solo se permiten solicitudes POST.");
}

// Verificar si se proporcionaron archivos o JSON
if (empty($_FILES) && empty($_POST)) {
    http_response_code(400);
    exit("No se ha proporcionado ningún archivo o JSON.");
}
$file = "";
// Manejar la subida de archivos
$uploadDir = './../img/ingredients/';
$uploadedFile = null;

if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == UPLOAD_ERR_OK) {
    $file = basename($_FILES['imagen']['name']);
    $uploadFile = $uploadDir . basename($_FILES['imagen']['name']);

    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    if (move_uploaded_file($_FILES['imagen']['tmp_name'], $uploadFile)) {
        $uploadedFile = $uploadFile;
    } else {
        http_response_code(500);
        exit("Error al subir la imagen.");
    }
}

if ($uploadedFile == null) {
    $uploadedFile = './../img/sin-imagen.jpg';
}

// Decodificar el JSON
$inputJSON = $_POST['ingrediente'];
$data = json_decode($inputJSON, true);

// Verificar si el JSON es válido
if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    exit("El JSON proporcionado es inválido.");
}

// Conectar a la base de datos
$conn = DBConnection::connectDB();
if (!$conn) {
    http_response_code(500);
    exit("Error al conectar a la base de datos.");
}

$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

try {

    $merma = $data["ing_merma"] / 100;

    // Insertar el ingrediente en la tabla 'ingredients'
    $stmt = $conn->prepare("INSERT INTO ingredients (fName, merma, packaging, unidad, warehouse, costPrice, salePrice, image, expira_dias, saleCurrency, peso, atr_name_tienda, atr_valores_tienda, descripcion_corta, clasificacion_ing)
                            VALUES (:ingrediente_name, :merma, :packaging, :unit, :warehouse, :cost, :sale, :image, :expire, :saleCurrency, :peso, :atr_name_tienda, :atr_valores_tienda, :descripcion_corta, :clasificacion_ing)");

    $stmt->execute([
        ':ingrediente_name' => $data["ing_name"],
        ':merma' => $merma,
        ':packaging' => $data["ing_packaging"],
        ':unit' => $data["ing_unit"],
        ':warehouse' => $data["ing_warehouse"],
        ':cost' => $data["ing_cost_price"],
        ':sale' => $data["ing_sale_price"],
        ':image' => $uploadedFile,
        ':expire' => $data["ing_expire"],
        ':saleCurrency' => "Euro",
        ':peso' => $data["ing_unit_weight"],
        ':atr_name_tienda' => $data["ing_atr_name"],
        ':atr_valores_tienda' => $data["ing_atr_values"],
        ':descripcion_corta' => $data["ing_short_description"],
        ':clasificacion_ing' => $data["ing_food_classification"]]
    );

    // Obtener el ID del ingrediente recién insertado
    $idIngrediente = $conn->lastInsertId();

    // Insertar el alérgeno en la tabla 'ingredientesalergenos'
    $stmt = $conn->prepare("INSERT INTO ingredientesalergenos (id_ingrediente, id_alergeno)
                            VALUES (:id_ingrediente, :id_alergeno)");

    $stmt->execute([
        ':id_ingrediente' => $idIngrediente,
        ':id_alergeno' => $data["ing_alergeno_id"],
    ]);

    saveIngStock($idIngrediente);
     // Generar el SKU
     $sku = "ING-" . str_pad($idIngrediente, 3, '0', STR_PAD_LEFT);

    // Enviar respuesta JSON
    $response = [
        'name' => $data["ing_name"],
        'cost_price' => $data["ing_cost_price"],
        'sku' => $sku,
        'imagen' => $file,
        'status' => 'success',
        'message' => 'Ingrediente añadido correctamente.'
    ];
    echo json_encode($response);
    http_response_code(200);
    exit();
} catch (PDOException $e) {
    // En caso de error, envía una respuesta de error
    echo json_encode(["error" => "Error: " . $e->getMessage()]);
    http_response_code(500);
    exit();
}




function saveIngStock($idIng)
{
    $conn = DBConnection::connectDB();
    
        if (!is_null($conn)) {
            $stmt = $conn->prepare("INSERT INTO stock_ing_kitchen
                                    (ingredient_id) 
                                    VALUES (:ingredient_id)");
            $stmt->execute([
                'ingredient_id' => $idIng,
                
            ]);
        }
    
}
