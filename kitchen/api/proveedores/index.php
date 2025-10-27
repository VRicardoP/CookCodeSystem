<?php 

require 'proveedores.php';

header('Content-Type: application/json; charset=utf-8'); // JSON
header("Access-Control-Allow-Origin: *");

$method = $_SERVER['REQUEST_METHOD'];
$proveedores = listarProveedor();

if($method === 'GET'){
    if (isset($_GET['id'])) {
        $id = intval($_GET['id']);
        $proveedor = obtenerProveedorPorId($id);

        if ($proveedor) {
            echo json_encode($proveedor, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        } else {
            echo json_encode(['error' => 'Proveedor no encontrado']);
        }
    } else {
        echo json_encode($proveedores, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
}
else if($method === 'POST'){
    $input = file_get_contents('php://input');
    $data = json_decode($input, true); // Pasa a Array asociativo

    if (isset($data['nombre'], $data['telefono'], $data['direccion'], $data['correo'])) {
        $resultado = crearProveedor($data);

        if ($resultado) {
            echo json_encode(['success' => 'Proveedor insertado correctamente']);
        } else {
            echo json_encode(['error' => 'No se pudo insertar el proveedor']);
        }
    } else {
        echo json_encode(['error' => 'Datos incompletos']);
    }
}
else{
    echo json_encode(['error' => 'Método no permitido']);
}
?>