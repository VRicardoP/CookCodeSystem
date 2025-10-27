<?php


function listarElaborados(){
    $query = "SELECT * FROM `recetas` WHERE `tipo` = 'Elaborado'";
    $link = mysqli_connect('localhost', 'root', '', 'kitchentag');
    $resultElaborados = $link->query($query);

    $elaborados = array();

    // Agrega cada pedido al array $elaborados
    while ($row = $resultElaborados->fetch_assoc()){
        
        $elaborados[] = array(
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

    return $elaborados;
}

function crearElaborados($data) {
    $query = "INSERT INTO `recetas` (tipo, receta, instrucciones, peso, num_raciones, expira_dias, localizacion, empaquetado, descripcion_corta)
    VALUES ('Elaborado', ?, ?, ?, ?, ?, ?, ?, ?)";

    $link = mysqli_connect('localhost', 'root', '', 'kitchentag');
    $stmt = $link->prepare($query);
    $stmt->bind_param(
        'ssdiisss', 
        $data['nombre'],
        $data['instrucciones'],
        $data['peso'],
        $data['numero de raciones'],
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

function obtenerElaboradoPorId($id) {
        $link = mysqli_connect('localhost', 'root', '', 'kitchentag');
        $stmt = $link->prepare("SELECT * FROM `recetas` WHERE ID = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $elaborado = null;
        if ($row = $result->fetch_assoc()) {
            $elaborado = array(
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
    
        $stmt->close();
        $link->close();
    
        return $elaborado;
}

function obtenerIngredientesReceta($id) {
    $link = mysqli_connect('localhost', 'root', '', 'kitchentag');

    $query = "SELECT * FROM `receta_ingrediente` WHERE receta = ?";
    $stmt = $link->prepare($query);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    $ingredientes = [];
    while ($row = $result->fetch_assoc()) {
        $ingredientes[] = [
            'cantidad' => $row['cantidad'],
            'ingrediente_id' => $row['ingrediente']
        ];
    }
    $stmt->close();

    $productos = [];
    $queryProd = "SELECT * FROM `ingredients`";
    $resultProd = $link->query($queryProd);
    while ($row = $resultProd->fetch_assoc()) {
        $productos[$row['ID']] = [
            'id'     => $row['ID'],
            'nombre' => $row['fName'],
            'unidad' => $row['unidad'],
            'precio' => $row['costPrice'],
            'moneda' => $row['saleCurrency'],
        ];
    }

    $resultado = [];
    foreach ($ingredientes as $ing) {
        $ingredienteInfo = isset($productos[$ing['ingrediente_id']]) ? $productos[$ing['ingrediente_id']] : null;
        $resultado[] = [
            'cantidad' => $ing['cantidad'],
            'ingrediente' => $ingredienteInfo
        ];
    }

    $link->close();
    return $resultado;
}

?>