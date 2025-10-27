<?php

function listarStock(){
    $query = "SELECT * FROM `stock_elab_kitchen`";
    $link = mysqli_connect('localhost', 'root', '', 'kitchentag');
    $resultStock = $link->query($query);

    $stock = array();

    while ($row = $resultStock->fetch_assoc()){
        $nombre = null;
        $stmt = $link->prepare("SELECT receta FROM recetas WHERE id = ?");
        $stmt->bind_param('i', $row['receta_id']);
        $stmt->execute();
        $stmt->bind_result($nombre);
        $stmt->fetch();
        $stmt->close();

        $stock[] = array(
            'id' => $row['id'],
            'nombre' => $nombre,
            'elaborado_id' => $row['receta_id'],
            'stock' => $row['stock']
        );
    }

    $link->close();
    return $stock;
}

?>