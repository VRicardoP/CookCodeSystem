<?php


?>

<!-- Main Content -->
<div id="content">

    <div class="container-fluid">
        <div class="card shadow mb-4 ">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">Tracking production and delivery of products</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <form name="elaboradosList" action="" method="get">
                        <table class="display" id="dataTableElab">
                            <thead>
                                <tr>

                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Packages</th>

                                    <th>Elaboration</th>
                                    <th>Expiration</th>
                                    <th>Place</th>
                                    <th>Cost</th>
                                    <th>Price</th>
                                    <th>Action</th>
                                    <th>State</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                try {
                                   
                                    $queryElab = "SELECT * FROM `almacenelaboraciones` ORDER BY `ID` DESC";
                                    $stmtElab = $conn->query($queryElab);

                                    while ($rowElab = $stmtElab->fetch(PDO::FETCH_ASSOC)) {
                                        $fechaActual = new DateTime();
                                        $fechaElab = new DateTime($rowElab["fechaElab"]);
                                        $fechaCad = new DateTime($rowElab["fechaCad"]);
                                        $fechaElabFormateada = $fechaElab->format('d/m/Y');
                                        $fechaCadFormateada = $fechaCad->format('d/m/Y');

                                        $intervalo = $fechaActual->diff($fechaCad);
                                        $diferenciaDias = $intervalo->format("%r%a");

                                        // Determine row class based on days difference
                                        $rowClass = '';
                                        if ($diferenciaDias <= 0) {
                                            $rowClass = 'class="expired"';
                                        } elseif ($diferenciaDias <= 7) {
                                            $rowClass = 'class="expiring-soon"';
                                        }

                                        echo '<tr id="row-elab-' . htmlspecialchars($rowElab["ID"]) . '" ' . $rowClass . '>';

                                        // Output table cells
                                        echo '<td>' . htmlspecialchars($rowElab["ID"]) . '</td>';
                                        echo '<td>' . htmlspecialchars($rowElab["fName"]) . '</td>';

                                        // Packaging amount
                                        $packagingTypes = [
                                            'Bag' => '(Bag)',
                                            'Pack' => '(Pack)',
                                            'Box' => '(Box)',
                                            'Bottle' => '(Bottle)',
                                            'Can' => '(Can)'
                                        ];
                                        $packaging = $packagingTypes[$rowElab["packaging"]] ?? '';
                                        $amount = htmlspecialchars($rowElab["productamount"]) . $packaging;
                                        echo '<td>' . $amount . " x " . htmlspecialchars($rowElab["rations_package"]) . 'und.</td>';

                                        // Dates
                                        echo '<td>' . $fechaElabFormateada . '</td>';
                                        echo '<td>' . $fechaCadFormateada . '</td>';
                                        echo '<td>' . htmlspecialchars($rowElab["warehouse"]) . '</td>';

                                        // Cost price with currency
                                        $costCurrencies = [
                                            'Euro' => '&euro;',
                                            'Dirham' => '&#x62F;&#x2E;&#x625;',
                                            'Yen' => '&yen;',
                                            'Dolar' => '&dollar;'
                                        ];
                                        $costSymbol = $costCurrencies[$rowElab["costCurrency"]] ?? '';
                                        echo '<td>' . htmlspecialchars($rowElab["costPrice"]) . $costSymbol . '</td>';

                                        // Sale price with currency
                                        $saleSymbol = $costCurrencies[$rowElab["saleCurrency"]] ?? '';
                                        echo '<td>' . htmlspecialchars($rowElab["salePrice"]) . $saleSymbol . '</td>';

                                        // Action button
                                        echo '<td class="action_button">
                <button class="btn-primary rounded" type="submit" name="elab" id="tag_elab" value="' . htmlspecialchars($rowElab["ID"]) . '">
                    <img src="./../../svg/printing.svg" alt="Print" title="Print">
                </button>
            </td>';

                                        // Status with CSS class
                                        $statusClasses = [
                                            'Registered' => 'estado-registrado',
                                            'Sent' => 'estado-enviado',
                                            'Received' => 'estado-recibido'
                                        ];
                                        $statusClass = $statusClasses[$rowElab["estado"]] ?? 'estado-desconocido';
                                        echo '<td class="' . $statusClass . '">' . htmlspecialchars($rowElab["estado"]) . '</td>';

                                        echo '</tr>';
                                    }
                                } catch (PDOException $e) {
                                    echo "<tr><td colspan='10'>Error: " . htmlspecialchars($e->getMessage()) . "</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>
        </div>


        <div class="card shadow mb-4 ">
            <div class="card-header ">
                <h6 class="m-0 font-weight-bold text-primary">Tracking production and delivery of ingredients</h6>
            </div>
            <div class="card-body ">
                <div class="table-responsive">
                    <form name="ingredientsList" action="" method="get">
                        <table class="display" id="dataTableElabIng">
                            <thead>


                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>




                                    <th>Packages</th>

                                    <th>Elaboration</th>
                                    <th>Expiration</th>
                                    <th>Place</th>
                                    <th>Cost</th>

                                    <th>Price</th>
                                    <th>Action</th>

                                    <th>State</th>
                                </tr>

                            </thead>

                         <tbody>
    <?php
    try {
        $conn = DBConnection::connectDB();
        
        // Main query for ingredients
        $queryIng = "SELECT * FROM `almaceningredientes` ORDER BY `ID` DESC";
        $stmtIng = $conn->query($queryIng);
        
        // Define mappings for better readability
        $packagingTypes = [
            'Bag' => '(Bag)',
            'Pack' => '(Pack)',
            'Box' => '(Box)',
            'Bottle' => '(Bottle)',
            'Can' => '(Can)'
        ];
        
        $unitTypes = [
            'Kg' => 'kg',
            'und' => 'und.',
            'L' => 'L'
        ];
        
        $currencySymbols = [
            'Euro' => '&euro;',
            'Dirham' => '&#x62F;&#x2E;&#x625;',
            'Yen' => '&yen;',
            'Dolar' => '&dollar;'
        ];
        
        $statusClasses = [
            'Registered' => 'estado-registrado',
            'Sent' => 'estado-enviado',
            'Received' => 'estado-recibido'
        ];
        
        while ($rowIng = $stmtIng->fetch(PDO::FETCH_ASSOC)) {
            // Get unit from ingredients table
            $queryUnit = "SELECT unidad FROM `ingredients` WHERE `id` = :ingrediente_id";
            $stmtUnit = $conn->prepare($queryUnit);
            $stmtUnit->bindParam(':ingrediente_id', $rowIng['ingrediente_id'], PDO::PARAM_INT);
            $stmtUnit->execute();
            $unitRow = $stmtUnit->fetch(PDO::FETCH_ASSOC);
            $unidad = $unitRow ? $unitRow['unidad'] : '';
            
            // Date calculations
            $fechaActual = new DateTime();
            $fechaElab = new DateTime($rowIng["fechaElab"]);
            $fechaCad = new DateTime($rowIng["fechaCad"]);
            $fechaElabFormateada = $fechaElab->format('d/m/Y');
            $fechaCadFormateada = $fechaCad->format('d/m/Y');
            
            $intervalo = $fechaActual->diff($fechaCad);
            $diferenciaDias = $intervalo->format("%r%a");
            
            // Determine row class based on expiration
            $rowClass = '';
            if ($diferenciaDias <= 0) {
                $rowClass = 'expired';
            } elseif ($diferenciaDias <= 7) {
                $rowClass = 'expiring-soon';
            }
            
            // Start table row
            echo '<tr id="row-ing-' . htmlspecialchars($rowIng["ID"]) . '" class="' . $rowClass . '">';
            
            // Basic info
            echo '<td>' . htmlspecialchars($rowIng['ID']) . '</td>';
            echo '<td>' . htmlspecialchars($rowIng['fName']) . '</td>';
            
            // Packaging and quantity
            $packaging = $packagingTypes[$rowIng['packaging']] ?? '';
            $amount = htmlspecialchars($rowIng['productamount']) . $packaging;
            
            $unitSuffix = $unitTypes[$unidad] ?? 'sin unidad';
            $quantity = htmlspecialchars($rowIng['cantidad_paquete']) . $unitSuffix;
            
            echo '<td>' . $amount . ' x ' . $quantity . '</td>';
            
            // Dates
            echo '<td>' . $fechaElabFormateada . '</td>';
            echo '<td>' . $fechaCadFormateada . '</td>';
            echo '<td>' . htmlspecialchars($rowIng['warehouse']) . '</td>';
            
            // Prices with currency
            $costSymbol = $currencySymbols[$rowIng['costCurrency']] ?? '';
            echo '<td>' . htmlspecialchars($rowIng['costPrice']) . $costSymbol . '</td>';
            
            $saleSymbol = $currencySymbols[$rowIng['saleCurrency']] ?? '';
            echo '<td>' . htmlspecialchars($rowIng['salePrice']) . $saleSymbol . '</td>';
            
            // Action button
            echo '<td class="action_button">
                <button class="btn-primary rounded" type="submit" name="tag_ing" id="tag_ing" value="' . htmlspecialchars($rowIng["ID"]) . '">
                    <img src="./../../svg/printing.svg" alt="Print" title="Print">
                </button>
            </td>';
            
            // Status
            $statusClass = $statusClasses[$rowIng['estado']] ?? 'estado-desconocido';
            echo '<td class="' . $statusClass . '">' . htmlspecialchars($rowIng['estado']) . '</td>';
            
            echo '</tr>';
        }
    } catch (PDOException $e) {
        echo '<tr><td colspan="10">Error: ' . htmlspecialchars($e->getMessage()) . '</td></tr>';
    }
    ?>
</tbody>
                        </table>
                    </form>
                </div>
            </div>
        </div>

    </div>


</div>