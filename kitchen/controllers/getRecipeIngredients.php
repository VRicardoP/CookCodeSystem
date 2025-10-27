<?php

require_once __DIR__ . "/../DBConnection.php";

// Verificar que la solicitud sea GET
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405); 
    exit("Solo se permiten solicitudes GET.");
}

$receta_id = $_GET['receta_id'] ?? null;

header('Content-Type: application/json');

if ($receta_id) {
    $conn = DBConnection::connectDB();
    if ($conn) {
        try {
            $sql = "
                SELECT ri.*, i.fName, i.merma, i.costPrice
                FROM receta_ingrediente ri
                JOIN ingredients i ON ri.ingrediente = i.ID
                WHERE ri.receta = :receta_id
            ";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':receta_id', $receta_id, PDO::PARAM_INT);
            $stmt->execute();
            
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($result);
        } catch (PDOException $e) {
            echo json_encode(["error" => "Query failed: " . $e->getMessage()]);
        }
    }
} else {
    echo json_encode(["error" => "No receta_id provided"]);
}