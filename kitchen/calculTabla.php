<?php
    $host = "localhost";
    $username = "root";
    $password = "";
    $database = "kitchentag";
        /* Attempt to connect to MySQL database */
        $link = new mysqli($host, $username, $password, $database);

         // Check connection
        if ($link->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
        }
        /*
        cada vez que hay un uso de un id de producto, sumar un uso
        las recetas tienen que venir por usos de producto(uso de producto=subdivision de un packaging de producto)
        cada vez que hay un uso de producto pasar el  producto por el lector y añadir un uso más
        obtener los productos vendidos en cantidad desde la api rest de woocommerce <?php print_r($woocommerce->get('orders')); ?>
        obtener las recetas de esos productos
        multiplicar las ventas de producto x las recetas
        construir la matriz de ventas de producto
        construir la matriz de consumos de producto 
        restar ambas ventas-consumos. Presentar negativos. 
        */
?>
