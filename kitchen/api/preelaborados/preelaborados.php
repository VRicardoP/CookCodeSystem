<?php

function listarPreelaborados(){
    $query = "SELECT * FROM `recetas` WHERE `tipo` = 'Pre-Elaborado'";
    $link = mysqli_connect('localhost', 'root', '', 'kitchentag');
    $resultpreelaborados = $link->query($query);

    $preelaborados = array();

    // Agrega cada pedido al array $preelaborados
    while ($row = $resultpreelaborados->fetch_assoc()){
        
        $preelaborados[] = array(
            'id' => $row['id'],
            'nombre' => $row['receta'],
            'instrucciones' => $row['instrucciones'],
            'peso' => $row['peso'],
            'numero de raciones' => $row['num_raciones'],
            'caducidad dias' => $row['expira_dias'],
            'localizacion' => $row['localizacion'],
            'empaquetado' => $row['empaquetado'],
            'descripcion' => $row['descripcion_corta'],
        );
    }

    return $preelaborados;
}

function crearPreelaborados($data) {
    $query = "INSERT INTO `recetas` (tipo, receta, instrucciones, peso, num_raciones, expira_dias, localizacion, empaquetado, descripcion_corta)
    VALUES ('Pre-Elaborado', ?, ?, ?, ?, ?, ?, ?, ?)";

    $link = mysqli_connect('localhost', 'root', '', 'kitchentag');
    $stmt = $link->prepare($query);
    $stmt->bind_param(
        'ssdiisss', 
        $data['nombre'],
        $data['instrucciones'],
        $data['peso'],
        $data['nunmero de raciones'],
        $data['caducidad dias'],
        $data['localizacion'],
        $data['empaquetado'],
        $data['descripcion'] 
    );

    $resultado = $stmt->execute();
    $stmt->close();
    $link->close();

    return $resultado;
}

?>