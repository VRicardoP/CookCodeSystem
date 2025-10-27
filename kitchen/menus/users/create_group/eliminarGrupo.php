<?php

require __DIR__ . '/../models/grupos.php';
require_once __DIR__ . '/../models/gruposDao.php';
require __DIR__ . '/../models/gruposPermisos.php';
require_once __DIR__ . '/../models/gruposPermisosDao.php';
require_once __DIR__ . '/../DBConnection.php';

header('Content-Type: application/json');

$id = $_POST['id'] ?? null;

if (!$id) {
    echo json_encode(['success' => false, 'message' => 'ID no proporcionado.']);
    exit;
}

// Eliminar el grupo usando DAO
$group = GruposDao::select($id);
GruposDao::delete($group);

// Eliminar los permisos del grupo con PDO
try {
    $conn = DBConnection::connectDB();

    if (!$conn) {
        throw new Exception("No se pudo establecer conexión con la base de datos.");
    }

    $stmt = $conn->prepare("DELETE FROM grupos_permisos WHERE grupo_id = ?");
    $stmt->execute([$id]);

    echo json_encode(['success' => true, 'message' => 'Grupo y permisos eliminados correctamente.']);

} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error al eliminar permisos del grupo: ' . $e->getMessage()
    ]);
    exit;
}
