<?php

function listarReceta_preelaborado(){
    $query = "SELECT * FROM `receta_elaborado`";
    $link = mysqli_connect('localhost', 'root', '', 'kitchentag');
    $resultReceta_elaborado = $link->query($query);

    $receta_elaborado = array();

    while ($row = $resultReceta_elaborado->fetch_assoc()){
        $receta_elaborado[] = array(
            'id' => $row['id'],
            'receta' => $row['receta'],
            'pre-elaborado' => $row['elaborado'],
            'cantidad' => $row['cantidad'],
        );
    }

    return $receta_elaborado;
}

function obtenerRecetaPorId($id) {
    $link = mysqli_connect('localhost', 'root', '', 'kitchentag');
    $stmt = $link->prepare("SELECT * FROM `receta_elaborado` WHERE `receta` = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    $recetas = array();
    while ($row = $result->fetch_assoc()) {
        $recetas[] = array(
            'id' => $row['id'],
            'receta' => $row['receta'],
            'pre-elaborado' => $row['elaborado'],
            'cantidad' => $row['cantidad'],
        );
    }

    $stmt->close();
    $link->close();

    return $recetas;
}

?>