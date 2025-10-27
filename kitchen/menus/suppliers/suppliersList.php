<?php
    $link = new mysqli('localhost', 'root', '', 'kitchentag');
    if($link->connect_error){
        die("Error de conexión: " . $link->connect_error);
    }

    $request = "SELECT * FROM `proveedores`";
    $result = $link->query($request);
    $proveedores = [];

    if($result){
        while($row = $result->fetch_assoc()){
            $proveedores[] = $row;
        }
    }
?>


<div class="card shadow mb-6">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Suppliers List</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-sm" id=dataTable" width="100%" cellspacing="0">
            <?php    
            if(count($proveedores) > 0){
                echo "<thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Numero de Telefono</th>
                    <th>Correo Electrónico</th>
                    <th>Dirección</th>
                </tr>
            </thead>
            <tbody>";
                
            foreach($proveedores as $proveedor){
                echo "<tr>";
                echo "<td>" . $proveedor['id'] . "</td>";
                echo "<td>" . $proveedor['nombre'] . "</td>";
                echo "<td>" . $proveedor['telefono'] . "</td>";
                echo "<td>" . $proveedor['correo'] . "</td>";
                echo "<td>" . $proveedor['direccion'] . "</td>";
                echo "</tr>";
            }

            $link->close();
            echo "</tbody>";
            }
            else{
                echo "<tr><td colspan='5'>No hay proveedores registrados</td></tr>";
            }
            ?>
            </table>
        </div>
    </div>
</div>

