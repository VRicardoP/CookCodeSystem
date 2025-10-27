<?php

function listarEtiquetas(){
    $query = "SELECT * FROM `almacenelaboraciones`  WHERE `tipoProd` = 'Elaborado'";
    $link = mysqli_connect('localhost', 'root', '', 'kitchentag');
    $resultEtiquetas = $link->query($query);

    $etiquetas = array();

    // Agrega cada etiqueta al array $etiquetas
    while ($row = $resultEtiquetas->fetch_assoc()){
        
        $etiquetas[] = array(
            'id' => $row['ID'],
            'tipo' => $row['tipoProd'],
            'elaborado' => $row['fName'],
            'empaquetado' => $row['packaging'],
            'cantidad' => $row['productamount'],
            'fechaElab' => $row['fechaElab'],
            'fechaCad' => $row['fechaCad'],
            'localizacion' => $row['warehouse'],
            'costCurrency' => $row['costCurrency'],
            'costPrice' => $row['costPrice'],
            'saleCurrency' => $row['saleCurrency'],
            'salePrice' => $row['salePrice'],
            'codeContents' => $row['codeContents'],
            'receta_id' => $row['receta_id'],
            'estado' => $row['estado'],
        );
    }

    return $etiquetas;
}

?>