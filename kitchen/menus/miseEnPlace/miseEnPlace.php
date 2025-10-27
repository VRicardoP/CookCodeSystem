<?php
require_once __DIR__ . '/../../DBConnection.php';
?>

<div id="content">
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <?php
        try {
            $conn = DBConnection::connectDB();
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
        ?>

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Stock kitchen</h1>

        <div class="card shadow mb-4">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">Ingredients kitchen</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <form name="ingredientsList" action="elabList.php" method="get">
                        <table class="display" id="dataTableElabIng">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Unit price</th>
                                    <th>stock</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $queryStock = "SELECT * FROM `stock_ing_kitchen` ORDER BY `id` DESC";
                                $stmtStock = $conn->query($queryStock);
                                
                                while ($rowStock = $stmtStock->fetch(PDO::FETCH_ASSOC)) {
                                    $queryIng = "SELECT * FROM `ingredients` WHERE `id` = :ingredient_id";
                                    $stmtIng = $conn->prepare($queryIng);
                                    $stmtIng->bindParam(':ingredient_id', $rowStock['ingredient_id'], PDO::PARAM_INT);
                                    $stmtIng->execute();
                                    
                                    if ($rowIng = $stmtIng->fetch(PDO::FETCH_ASSOC)) {
                                        $nameIng = $rowIng['fName'];
                                        $priceUnit = $rowIng['costPrice'];
                                        $Unit = $rowIng['unidad'];
                                        $saleCurrency = $rowIng['saleCurrency'];
                                        
                                        $currencySymbol = ($saleCurrency == 'Euro') ? '€' : '';
                                        
                                        echo "<tr data-id='" . $rowStock['id'] . "'>";
                                        echo "<td>" . $rowStock['id'] . "</td>";
                                        echo "<td>" . htmlspecialchars($nameIng) . "</td>";
                                        echo "<td>" . htmlspecialchars($priceUnit) . htmlspecialchars($currencySymbol) . "</td>";
                                        echo "<td class='stockValue'>" . htmlspecialchars($rowStock['stock']) . " " . htmlspecialchars($Unit) . "</td>";
                                        echo "</tr>";
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">Elaborations kitchen</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <form name="ingredientsList" action="elabList.php" method="get">
                        <table class="display" id="dataTableElabRec">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>stock</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $queryStock = "SELECT * FROM `stock_elab_kitchen` ORDER BY `id` DESC";
                                $stmtStock = $conn->query($queryStock);
                                
                                while ($rowStock = $stmtStock->fetch(PDO::FETCH_ASSOC)) {
                                    $queryIng = "SELECT * FROM `recetas` WHERE `id` = :receta_id";
                                    $stmtIng = $conn->prepare($queryIng);
                                    $stmtIng->bindParam(':receta_id', $rowStock['receta_id'], PDO::PARAM_INT);
                                    $stmtIng->execute();
                                    
                                    if ($rowIng = $stmtIng->fetch(PDO::FETCH_ASSOC)) {
                                        $nameIng = $rowIng['receta'];
                                        
                                        echo "<tr data-id='" . $rowStock['id'] . "'>";
                                        echo "<td>" . $rowStock['id'] . "</td>";
                                        echo "<td>" . htmlspecialchars($nameIng) . "</td>";
                                        echo "<td class='stockValue'>" . htmlspecialchars($rowStock['stock']) . "</td>";
                                        echo "</tr>";
                                    }
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