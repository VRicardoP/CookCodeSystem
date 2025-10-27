<?php


function listarReceta_ingrediente(){
    $query = "SELECT * FROM `receta_ingrediente`";
    $link = mysqli_connect('localhost', 'root', '', 'kitchentag');
    $resultReceta_ingrediente = $link->query($query);

    $receta_ingrediente = array();

    while ($row = $resultReceta_ingrediente->fetch_assoc()){
        $receta_ingrediente[] = array(
            'id' => $row['id'],
            'receta' => $row['receta'],
            'ingrediente' => $row['ingrediente'],
            'cantidad' => $row['cantidad'],
        );
    }

    return $receta_ingrediente;
}

function crearreceta_ingrediente($data) {
    $query = "INSERT INTO `receta_ingrediente` (receta, ingrediente, cantidad)
    VALUES (?, ?, ?)";

    $link = mysqli_connect('localhost', 'root', '', 'kitchentag');
    $stmt = $link->prepare($query);
    $stmt->bind_param(
        'iif', 
        $data['receta'],
        $data['ingrediente'],
        $data['cantidad'],
    );

    $resultado = $stmt->execute();
    $stmt->close();
    $link->close();

    return $resultado;
}

function obtenerRecetaPorId($id) {
    $link = mysqli_connect('localhost', 'root', '', 'kitchentag');
    $stmt = $link->prepare("SELECT * FROM `receta_ingrediente` WHERE `receta` = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    $recetas = array();
    while ($row = $result->fetch_assoc()) {
        $recetas[] = array(
            'id' => $row['id'],
            'receta' => $row['receta'],
            'ingrediente' => $row['ingrediente'],
            'cantidad' => $row['cantidad'],
        );
    }

    $stmt->close();
    $link->close();

    return $recetas;
}

?>