<?php

function listarProductos(){
        $query = "SELECT * FROM `ingredients`";
        $link = mysqli_connect('localhost', 'root', '', 'kitchentag');
        $resultProductos = $link->query($query);

        $productos = array();

        // Agrega cada ingrediente al array $productos
        while ($row = $resultProductos->fetch_assoc()){
                $productos[] = array(
                'id' => $row['ID'],
                'nombre' => $row['fName'],
                'merma' => $row['merma'], //sobrante del ingrediente
                'empaquetado' => $row['packaging'],
                // 'cantidad' => $row['cantidad'],
                'unidad' => $row['unidad'],
                // 'fechaElab' => $row['fechaElab'],
                // 'fechaCad' => $row['fechaCad'],
                'localizacion' => $row['warehouse'],
                // 'costCurrency' => $row['costCurrency'],
                'precio' => $row['costPrice'],
                'moneda' => $row['saleCurrency'],
                // 'precioVenta' => $row['salePrice'],
                // 'codeContents' => $row['codeContents'],
                // 'imagen' => $row['image'],
                'caducidad_dias' => $row['expira_dias'],
                // 'peso' => $row['peso'],
                // 'atr_name_tienda' => $row['atr_name_tienda'],
                'cantidad_empaquetados' => $row['atr_valores_tienda'],
                );
        }

        return $productos;

}

function crearPedido($data) {
        $query = "INSERT INTO `ingredients` (fName, merma, packaging, unidad, warehouse, costPrice, saleCurrency, expira_dias, atr_valores_tienda) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $link = mysqli_connect('localhost', 'root', '', 'kitchentag');
        $stmt = $link->prepare($query);
        $stmt->bind_param(
                'sdsisisis', 
                $data['nombre'],
                $data['merma'],
                $data['empaquetado'],
                $data['unidad'],
                $data['localizacion'],
                $data['precio'],
                $data['moneda'],
                $data['caducidad_dias'],
                $data['cantidad_empaquetados']
        );

        $resultado = $stmt->execute();
        $stmt->close();
        $link->close();

        return $resultado;
}

function obtenerProductoPorId($id) {
        $link = mysqli_connect('localhost', 'root', '', 'kitchentag');
        $stmt = $link->prepare("SELECT * FROM `ingredients` WHERE ID = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $producto = null;
        if ($row = $result->fetch_assoc()) {
            $producto = array(
                'id' => $row['ID'],
                'nombre' => $row['fName'],
                'merma' => $row['merma'],
                'empaquetado' => $row['packaging'],
                'unidad' => $row['unidad'],
                'localizacion' => $row['warehouse'],
                'precio' => $row['costPrice'],
                'moneda' => $row['saleCurrency'],
                'caducidad_dias' => $row['expira_dias'],
                'cantidad_empaquetados' => $row['atr_valores_tienda'],
            );
        }
    
        $stmt->close();
        $link->close();
    
        return $producto;
}
    
?>