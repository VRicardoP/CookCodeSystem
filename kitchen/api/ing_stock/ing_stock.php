<?php

function listarStock(){
    $query = "SELECT * FROM `stock_ing_kitchen`";
    $link = mysqli_connect('localhost', 'root', '', 'kitchentag');
    $resultStock = $link->query($query);

    $stock = array();

    while ($row = $resultStock->fetch_assoc()){
        $nombre = null;
        $stmt = $link->prepare("SELECT fName FROM ingredients WHERE ID = ?");
        $stmt->bind_param('i', $row['ingredient_id']);
        $stmt->execute();
        $stmt->bind_result($nombre);
        $stmt->fetch();
        $stmt->close();

        $stock[] = array(
            'id' => $row['id'],
            'nombre' => $nombre,
            'ingrediente_id' => $row['ingredient_id'],
            'stock' => $row['stock']
        );
    }

    $link->close();
    return $stock;
}


?>