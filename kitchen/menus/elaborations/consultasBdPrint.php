<?php
require_once __DIR__ . '/../../DBConnection.php';

// Conexión con PDO
$conn = DBConnection::connectDB();
if (!$conn) {
    die("Error de conexión a la base de datos.");
}

// Validación inicial
if (!isset($tipo) || !isset($id)) {
    die("Parámetros faltantes: tipo o id.");
}

if ($tipo == "elab") {
    // Consulta almacen elaborado
    $stmt = $conn->prepare("SELECT * FROM almacenelaboraciones WHERE ID = ?");
    $stmt->execute([$id]);
    $rowElaborado = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($rowElaborado) {
        extract($rowElaborado); // $ID, $fName, $warehouse, etc.
        $idElaborado = $ID;
        $nameElaborado = $fName;
        $warehouseElaborado = $warehouse;
        $fechaElabElaborado = $fechaElab;
        $caducidadElaborado = $fechaCad;
        $amountElaborado = $productamount;
        $receta = $receta_id;

        // Receta
        $stmt = $conn->prepare("SELECT * FROM recetas WHERE id = ?");
        $stmt->execute([$receta]);
        $rowReceta = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($rowReceta) {
            extract($rowReceta);
            $pesoUnicaRacion = $peso / $num_raciones;
            $pesoNeto = $num_raciones * $pesoUnicaRacion;
            $pesoNeto .= " Kg";
        }

        // Ingredientes de la receta
        $stmt = $conn->prepare("SELECT ingrediente FROM receta_ingrediente WHERE receta = ?");
        $stmt->execute([$id]);
        $idsReceta = $stmt->fetchAll(PDO::FETCH_COLUMN);

        $ingredientes = $ingredientesId = $ingredientesIngles = $ingredientesFrances = $ingredientesGer = [];

        foreach ($idsReceta as $idIngrediente) {
            $stmt = $conn->prepare("SELECT * FROM ingredients WHERE ID = ?");
            $stmt->execute([$idIngrediente]);
            $rowIngrediente = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($rowIngrediente) {
                $ingredientes[] = $rowIngrediente["fName"];
                $ingredientesId[] = $rowIngrediente["ID"];
            }
        }

        // Traducciones
        foreach ($ingredientes as $ing) {
            $stmt = $conn->prepare("SELECT * FROM traduccionesingredientes WHERE nombre_espanol = ?");
            $stmt->execute([$ing]);
            $rowTrad = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($rowTrad) {
                $ingredientesIngles[] = $rowTrad["nombre_ingles"];
                $ingredientesFrances[] = $rowTrad["nombre_frances"];
                $ingredientesGer[] = $rowTrad["nombre_aleman"];
            }
        }

        // Alérgenos
        $alergenosId = [];

        foreach ($ingredientesId as $ingId) {
            $stmt = $conn->prepare("SELECT id_alergeno FROM ingredientesalergenos WHERE id_ingrediente = ?");
            $stmt->execute([$ingId]);
            $alergenosId = array_merge($alergenosId, $stmt->fetchAll(PDO::FETCH_COLUMN));
        }

        $alergenosName = $alergenosNameIngles = $alergenosNameFrances = $alergenosNameAleman = [];

        foreach ($alergenosId as $alergId) {
            $stmt = $conn->prepare("SELECT * FROM alergenos WHERE id = ?");
            $stmt->execute([$alergId]);
            $rowAlergeno = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($rowAlergeno) {
                $alergenosName[] = $rowAlergeno["nombre"];
                $alergenosNameIngles[] = $rowAlergeno["nombre_ingles"];
                $alergenosNameFrances[] = $rowAlergeno["nombre_frances"];
                $alergenosNameAleman[] = $rowAlergeno["nombre_aleman"];
            }
        }
    }

} else if ($tipo == "tag_ing") {
    $stmt = $conn->prepare("SELECT * FROM almaceningredientes WHERE ID = ?");
    $stmt->execute([$id]);
    $rowIng = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($rowIng) {
        extract($rowIng);
        $idAlmacenIngrediente = $ID;
        $nameIngrediente = $fName;
        $fechaCadIngrediente = $fechaCad;
        $warehouseIngrediente = $warehouse;
        $productamount = $productamount;
        $idIngrediente = $ingrediente_id;
        $cantidadPaquete = $cantidad_paquete;

        $stmt = $conn->prepare("SELECT * FROM ingredients WHERE ID = ?");
        $stmt->execute([$idIngrediente]);
        $rowIngrediente = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($rowIngrediente) {
            extract($rowIngrediente);
            $pesoUnidadLitro = $peso;
        }

        $productAmount = floatval($productamount);

        if ($unidad == "Und" || $unidad == "L") {
            $pesoNeto = $productAmount * floatval($pesoUnidadLitro) * floatval($cantidadPaquete);
            $unidad = ' Kg';
        } else {
            $pesoNeto = $productAmount * floatval($cantidadPaquete);
        }

        $stmt = $conn->prepare("SELECT * FROM traduccionesingredientes WHERE nombre_espanol = ?");
        $stmt->execute([$nameIngrediente]);
        $rowTrad = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($rowTrad) {
            $ingredientesIngles[] = $rowTrad["nombre_ingles"];
            $ingredientesFrances[] = $rowTrad["nombre_frances"];
            $ingredientesGer[] = $rowTrad["nombre_aleman"];
        }

        $stmt = $conn->prepare("SELECT id_alergeno FROM ingredientesalergenos WHERE id_ingrediente = ?");
        $stmt->execute([$idIngrediente]);
        $alergId = $stmt->fetchColumn();

        $stmt = $conn->prepare("SELECT * FROM alergenos WHERE id = ?");
        $stmt->execute([$alergId]);
        $rowAlergeno = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($rowAlergeno) {
            $alergenosName[] = $rowAlergeno["nombre"];
            $alergenosNameIngles[] = $rowAlergeno["nombre_ingles"];
            $alergenosNameFrances[] = $rowAlergeno["nombre_frances"];
            $alergenosNameAleman[] = $rowAlergeno["nombre_aleman"];
        }

        $nameElaborado = $nameIngrediente;
        $warehouseElaborado = $warehouseIngrediente;
        $ingredientes[] = $nameIngrediente;
        $caducidadElaborado = $fechaCadIngrediente;
        $idElaborado =  $idAlmacenIngrediente;
    }
}

$totalIngredientes = isset($ingredientes) ? count($ingredientes) : 0;

// Traducciones de ubicaciones
$traducciones = [
    "Final product area" => [
        "espanol" => "Área de producto final",
        "frances" => "Zone de produit final",
        "aleman" => "Endproduktbereich"
    ],
    "Freezer" => [
        "espanol" => "Congelador",
        "frances" => "Congélateur",
        "aleman" => "Gefrierschrank"
    ],
    "Warehouse" => [
        "espanol" => "Almacén",
        "frances" => "Entrepôt",
        "aleman" => "Lagerhaus"
    ]
];

// Función para obtener traducciones
function obtenerTraducciones($area)
{
    global $traducciones;

    return $traducciones[$area] ?? "No se encontraron traducciones para el área proporcionada.";
}

if (isset($warehouseElaborado)) {
    $traducciones = obtenerTraducciones($warehouseElaborado);
}
?>
