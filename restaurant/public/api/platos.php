<?php

session_start();
header('Content-Type: application/json');

// Comprobar sesión

/** 
if (empty($_SESSION['isLogged']) || !$_SESSION['isLogged']) {
    http_response_code(401);
    echo json_encode(['error'=>'User not logged']);
    exit;
}
*/
// Conexión MySQL
$conn = new mysqli('localhost','root','','restaurant');
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['error'=>'DB error: '.$conn->connect_error]);
    exit;
}

// GET
if ($_SERVER['REQUEST_METHOD']==='GET') {
    if (!isset($_SESSION['restaurante_id'])) {
        http_response_code(400);
        echo json_encode(['error'=>'No se ha definido restaurante_id en la sesión']);
        exit;
    }
    $restauranteId = (int) $_SESSION['restaurante_id'];
    $stmt = $conn->prepare("SELECT * FROM platos WHERE id IN (SELECT id_plato FROM plato_restaurante WHERE id_restaurante = ?)");
    $stmt->bind_param('i',$restauranteId);
    $stmt->execute();
    echo json_encode($stmt->get_result()->fetch_all(MYSQLI_ASSOC));
    exit;
}

// POST
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $json = file_get_contents('php://input');
    file_put_contents(__DIR__.'/debug_payload.log', $json . "\n", FILE_APPEND);  // <--- log payload
    $data = json_decode($json, true);

    // Si recibimos un único objeto en vez de un array de ellos, lo normalizamos
    if (isset($data['items']) && !isset($data['items'][0]) && is_array($data['items'])) {
        $data['items'] = [ $data['items'] ];
    }

    if (!isset($data['items'], $data['restaurantes'])
    || !is_array($data['items'])
    || !is_array($data['restaurantes'])
    ) {
        http_response_code(400);
        echo json_encode(['error'=>'Payload inválido. Debe tener arrays items y restaurantes.']);
        exit;
    }

    $restArr = array_map('intval', $data['restaurantes']);

    // Preparar consultas para platos y plato_restaurante
    $sqlPlato = "INSERT INTO platos (id, nombre, imagen) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE nombre=VALUES(nombre), imagen=VALUES(imagen)";
    $stmtPlato = $conn->prepare($sqlPlato);
    if (!$stmtPlato) {
        http_response_code(500);
        echo json_encode(['error'=>'Prepare error (platos): '.$conn->error]);
        exit;
    }

    $sqlPlatoRest = "INSERT INTO plato_restaurante (id_plato, id_restaurante) VALUES (?, ?) ON DUPLICATE KEY UPDATE id_plato=VALUES(id_plato), id_restaurante=VALUES(id_restaurante)";
    $stmtPlatoRest = $conn->prepare($sqlPlatoRest);
    if (!$stmtPlatoRest) {
        http_response_code(500);
        echo json_encode(['error'=>'Prepare error (plato_restaurante): '.$conn->error]);
        exit;
    }

    $conn->begin_transaction();
    try {
        foreach ($data['items'] as $item) {
            // ...obtención de $ingredienteId, $nombre, $imagen...
            if (isset($item['ID'],$item['fName'])) {
                $ingredienteId = (int)$item['ID'];
                $nombre        = $conn->real_escape_string($item['fName']);
                $imagen        = $conn->real_escape_string($item['image']);
            } else if (isset($item['id'],$item['receta'])) {
                $ingredienteId = (int)$item['id'];
                $nombre        = $conn->real_escape_string($item['receta']);
                $imagen        = $conn->real_escape_string($item['imagen']);
            } else {
                throw new Exception('Formato de item desconocido: '.print_r($item,true));
            }

            // Insertar en platos
            $stmtPlato->bind_param('iss', $ingredienteId, $nombre, $imagen);
            if (!$stmtPlato->execute()) {
                throw new Exception("Error insert plato: ".$stmtPlato->error);
            }

            // Insertar en plato_restaurante para cada restaurante
            foreach ($restArr as $rid) {
                $stmtPlatoRest->bind_param('ii', $ingredienteId, $rid);
                if (!$stmtPlatoRest->execute()) {
                    throw new Exception("Error insert plato_restaurante (rid={$rid}): ".$stmtPlatoRest->error);
                }
            }
        }
        $conn->commit();
        http_response_code(201);
        echo json_encode(['success'=>true]);
    } catch (Exception $e) {
        $conn->rollback();
        // Log y devolver mensaje
        file_put_contents(__DIR__.'/debug_error.log', $e->getMessage()."\n", FILE_APPEND);
        http_response_code(500);
        echo json_encode(['error'=>$e->getMessage()]);
    }
    exit;
}

// 405
http_response_code(405);
echo json_encode(['error'=>'Use GET or POST']);
exit;
