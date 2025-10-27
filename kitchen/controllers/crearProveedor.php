<?php

require_once __DIR__ . '/../DBConnection.php';

header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

if ($data) {
    $link = DBConnection::connectDB();

    if (!$link) {
        echo json_encode(["success" => false, "message" => "Error de conexión a la base de datos"]);
        exit;
    }

    $stmt = $link->prepare("INSERT INTO proveedores (nombre, telefono, correo, direccion) VALUES (?, ?, ?, ?)");

    try {
        $stmt->execute([
            $data['nombre'],
            $data['numero'],
            $data['correo'],
            $data['direccion']
        ]);
        echo json_encode(["success" => true, "message" => "Proveedor creado correctamente"]);
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => "Error al crear el proveedor: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "message" => "No se recibieron datos"]);
}
