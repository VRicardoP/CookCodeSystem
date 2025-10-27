<?php
require_once __DIR__ . '/../models/ingredientesDao.php';

header('Content-Type: application/json');

try {
    //  Get JSON input
    $input = json_decode(file_get_contents('php://input'), true);

    //  Validate input

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Invalid JSON input');
    }

    $name = $input['name'] ?? '';
    $type = $input['type'] ?? '';

    if (empty($name)) {
        throw new Exception('Name parameter is required');
    }

    if (empty($type)) {
        throw new Exception('Type parameter is required');
    }

    $exists = false;

    switch ($type) {
        case 'ingrediente':
            $exists = IngredientesDao::nameExists($name);
            break;

        case 'elaborado':
            // Código para otro tipo de verificación
            // $exists = ElaboradosDao::nameExists($name);
            break;

        case 'pre-elaborado':
            // Código para otro tipo de verificación
            // $exists = PreElaboradosDao::nameExists($name);
            break;

        default:
            throw new Exception('Invalid type specified');
    }

    echo json_encode([
        'success' => true,
        'exists' => $exists
    ]);
    exit;
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
    exit;
}
