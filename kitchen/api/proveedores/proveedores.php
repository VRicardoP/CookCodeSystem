<?php


function listarProveedor(){
    $query = "SELECT * FROM `proveedores`";
    $link = mysqli_connect('localhost', 'root', '', 'kitchentag');
    $resultProveedores = $link->query($query);

    $proveedores = array();

    // Agrega cada pedido al array $proveedores
    while ($row = $resultProveedores->fetch_assoc()){
        
        $proveedores[] = array(
            'id' => $row['id'],
            'nombre' => $row['nombre'],
            'telefono' => $row['telefono'],
            'direccion' => $row['direccion'],
            'correo' => $row['correo'],
        );
    }

    return $proveedores;
}

function crearProveedor($data) {
    $query = "INSERT INTO `proveedores` (nombre, telefono, direccion, correo)
    VALUES (?, ?, ?, ?)";

    $link = mysqli_connect('localhost', 'root', '', 'kitchentag');
    $stmt = $link->prepare($query);
    $stmt->bind_param(
        'siss', 
        $data['nombre'], 
        $data['telefono'], 
        $data['direccion'], 
        $data['correo']
    );

    $resultado = $stmt->execute();
    $stmt->close();
    $link->close();

    return $resultado;
}

function obtenerProveedorPorId($id) {
    $link = mysqli_connect('localhost', 'root', '', 'kitchentag');
    $stmt = $link->prepare("SELECT * FROM `proveedores` WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    $proveedor = null;
    if ($row = $result->fetch_assoc()) {
        $proveedor = array(
            'id' => $row['id'],
            'nombre' => $row['nombre'],
            'telefono' => $row['telefono'],
            'direccion' => $row['direccion'],
            'correo' => $row['correo'],
        );
    }

    $stmt->close();
    $link->close();

    return $proveedor;
}

?>