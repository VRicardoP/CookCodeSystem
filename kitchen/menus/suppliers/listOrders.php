<?php
    $link = new mysqli('localhost', 'root', '', 'kitchentag');
    if($link->connect_error){
        die("Error de conexión: " . $link->connect_error);
    }

    $request = "SELECT * FROM `pedido_producto`";
    $result = $link->query($request);
    $pedidos = [];

    if($result){
        while($row = $result->fetch_assoc()){
            $pedidos[] = $row;
        }
    }
?>

<div class="card shadow mb-6">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Orders List</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-sm" id=dataTable" width="100%" cellspacing="0">
            <?php    
            if(count($pedidos) > 0){
                echo "<thead>
                <tr>
                    <th>ID</th>
                    <th>Producto ID</th>
                    <th>Proveedor ID</th>
                    <th>Cantidad</th>
                    <th>Tipo Cantidad</th>
                    <th>Tipo Pago</th>
                    <th>Tiempo Pago</th>
                </tr>
            </thead>
            <tbody>";
                
            foreach($pedidos as $pedido){
                echo "<tr>";
                echo "<td>" . $pedido['id'] . "</td>";
                echo "<td>" . $pedido['producto_id'] . "</td>";
                echo "<td>" . $pedido['proveedor_id'] . "</td>";
                echo "<td>" . $pedido['cantidad'] . "</td>";
                echo "<td>" . $pedido['tipo_cantidad'] . "</td>";
                echo "<td>" . $pedido['tipo_pago'] . "</td>";
                echo "<td>" . $pedido['tiempo_pago'] . "</td>";
                echo "</tr>";
            }

            $link->close();
            echo "</tbody>";
            }
            else{
                echo "<tr><td colspan='5'>No hay pedidos registrados</td></tr>";
            }
            ?>
            </table>
        </div>
    </div>
</div>