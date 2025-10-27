<?php
require_once __DIR__ . '/../DBConnection.php';
require_once __DIR__ . '/../models/almacenElaboracionesDao.php';
require_once __DIR__ . '/../models/almacenIngredientesDao.php';
require_once __DIR__ . '/../models/ingredientesDao.php';
require_once __DIR__ . '/../models/recetasDao.php';
require_once __DIR__ . '/../models/recetaElaboradoDao.php';
require_once __DIR__ . '/../models/recetaElaborado.php';
require_once __DIR__ . '/../models/ingredientesAlergenosDao.php';
require_once __DIR__ . '/../models/ingredientesAlergenos.php';

$response = [];
$pdo = DBConnection::connectDB();

if (!$pdo) {
    echo json_encode(['success' => false, 'error' => 'Error de conexión a la base de datos.']);
    exit;
}

if (isset($_GET['id'])) {
    $elaboradoId = $_GET['id'];
    $tagElaboracion = AlmacenElaboracionesDao::select($elaboradoId);

    $idReceta = $tagElaboracion->getRecetaId();
    $stmt = $pdo->prepare("SELECT * FROM recetas WHERE id = ?");
    $stmt->execute([$idReceta]);
    $rowReceta = $stmt->fetch(PDO::FETCH_ASSOC);
    $imagen = $rowReceta['imagen'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM receta_ingrediente WHERE receta = ?");
    $stmt->execute([$idReceta]);
    $ingredients = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $ingredientsName = [];
    $alergenosId = [];

    foreach ($ingredients as $ingredient) {
        $stmtIng = $pdo->prepare("SELECT * FROM ingredients WHERE ID = ?");
        $stmtIng->execute([$ingredient['ingrediente']]);
        $rowIng = $stmtIng->fetch(PDO::FETCH_ASSOC);

        if ($rowIng) {
            $ingredientsName[] = [
                'nombre_ingrediente' => $rowIng['fName'],
                'cantidad' => $ingredient['cantidad'],
                'unidad' => $rowIng['unidad']
            ];
        }

        $stmtAler = $pdo->prepare("SELECT * FROM ingredientesalergenos WHERE id_ingrediente = ?");
        $stmtAler->execute([$ingredient['ingrediente']]);
        while ($rowAler = $stmtAler->fetch(PDO::FETCH_ASSOC)) {
            $alergenosId[] = $rowAler['id_alergeno'];
        }
    }

    $alergenosNombre = [];
    foreach ($alergenosId as $alergenoId) {
        $stmtAl = $pdo->prepare("SELECT * FROM alergenos WHERE id = ?");
        $stmtAl->execute([$alergenoId]);
        $rowAl = $stmtAl->fetch(PDO::FETCH_ASSOC);
        if ($rowAl) {
            $alergenosNombre[] = $rowAl['nombre'];
        }
    }

    $response['nombre'] = $tagElaboracion->getFName();
    $packaging = $tagElaboracion->getPackaging();
    $amount = $tagElaboracion->getProductamount();
    $response['empaquetado'] = "$amount($packaging)";
    $response['raciones'] = $tagElaboracion->getRationsPackage();
    $response['almacenaje'] = $tagElaboracion->getWarehouse();
    $response['fechaElab'] = (new DateTime($tagElaboracion->getFechaElab()))->format('d-m-Y');
    $response['fechaCad'] = (new DateTime($tagElaboracion->getFechaCad()))->format('d-m-Y');

    $monedaCost = $tagElaboracion->getCostCurrency();
    $cost = $tagElaboracion->getCostPrice();
    $response['coste'] = $cost . match ($monedaCost) {
        'Euro' => "&euro;",
        'Dirham' => "&#x62F;&#x2E;&#x625;",
        'Yen' => "&yen;",
        'Dolar' => "&dollar;",
        default => "",
    };

    $monedasale = $tagElaboracion->getSaleCurrency();
    $sale = $tagElaboracion->getSalePrice();
    $response['venta'] = $sale . match ($monedasale) {
        'Euro' => "&euro;",
        'Dirham' => "&#x62F;&#x2E;&#x625;",
        'Yen' => "&yen;",
        'Dolar' => "&dollar;",
        default => "",
    };

    $response['imagen'] = $imagen;
    $response['ingredientes'] = [];

    foreach ($ingredientsName as $i => $item) {
        $response['ingredientes'][] = [
            'nombre' => $item['nombre_ingrediente'],
            'cantidad' => $item['cantidad'],
            'unidad' => $item['unidad'],
            'alergeno' => $alergenosNombre[$i] ?? null
        ];
    }

    $response['elaborados'] = RecetaElaboradoDao::getElaboradosByRecetaId($idReceta);
} 
else if (isset($_GET['idIng'])) {
    $ingredientId = $_GET['idIng'];
    $tagIngrediente = AlmacenIngredientesDao::select($ingredientId);

    $idIng = $tagIngrediente->getIngredienteId();

    // Obtener datos del ingrediente desde la tabla ingredients usando PDO
    $stmt = $pdo->prepare("SELECT * FROM ingredients WHERE ID = ?");
    $stmt->execute([$idIng]);
    $rowIngrediente = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($rowIngrediente) {
        $imagen = $rowIngrediente['image'];
        $unidad = $rowIngrediente['unidad'];
    } else {
        $response['error'] = "Ingrediente no encontrado.";
        $response['success'] = false;
        echo json_encode($response);
        exit;
    }

    $response['nombre'] = $tagIngrediente->getFName();
    $packaging = $tagIngrediente->getPackaging();
    $amount = $tagIngrediente->getProductamount();
    $response['empaquetado'] = "$amount($packaging)";
    $response['cantidad'] = $tagIngrediente->getCantidadPaquete() . $unidad;
    $response['almacenaje'] = $tagIngrediente->getWarehouse();

    $fechaElab = new DateTime($tagIngrediente->getFechaElab());
    $response['fechaElab'] = $fechaElab->format('Y-m-d');

    $fechaCad = new DateTime($tagIngrediente->getFechaCad());
    $response['fechaCad'] = $fechaCad->format('Y-m-d');

    // Precio coste con símbolo moneda
    $monedaCost = $tagIngrediente->getCostCurrency();
    $cost = $tagIngrediente->getCostPrice();
    $response['coste'] = $cost . match ($monedaCost) {
        'Euro' => "&euro;",
        'Dirham' => "&#x62F;&#x2E;&#x625;",
        'Yen' => "&yen;",
        'Dolar' => "&dollar;",
        default => "",
    };

    // Precio venta con símbolo moneda
    $monedaSale = $tagIngrediente->getSaleCurrency();
    $sale = $tagIngrediente->getSalePrice();
    $response['venta'] = $sale . match ($monedaSale) {
        'Euro' => "&euro;",
        'Dirham' => "&#x62F;&#x2E;&#x625;",
        'Yen' => "&yen;",
        'Dolar' => "&dollar;",
        default => "",
    };

    $response['imagen'] = $imagen;
}
else {
    $response['error'] = "No se proporcionó un ID válido.";
    $response['success'] = false;
}

header('Content-Type: application/json');
echo json_encode($response);
