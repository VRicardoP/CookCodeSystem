<div id="ingredientes" class="tabcontent">

    <!-- Contenido de la sección de ingredientes -->

    <p>Here you can see the list of ingredients.</p>
    <div class="card shadow mb-4 ">
        <div class="card-header">
            <h6 class="m-0 font-weight-bold text-primary">Ingredients</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <form name="ingredientList" action="" method="get">
                    <table class="" id="tableIngredients">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Product</th>

                                <th>Cost price</th>
                                <th>Sale price</th>

                                <th>Decrease</th>
                                <th>Location</th>
                                <th>Packaging</th>

                                <th>Expire days</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                      

                            $queryIng = "SELECT * FROM `ingredients`";
                            $stmtIng = $link->query($queryIng);
                            $ingredientes = $stmtIng->fetchAll(PDO::FETCH_ASSOC);

                            foreach ($ingredientes as $rowIng) {
                                echo '<tr><td class="img-table"><img src="./.' . $rowIng["image"] . '" alt="Rec" title="Ingredient"></td>';
                                echo '<td>' . htmlspecialchars($rowIng["fName"]) . '</td>';

                                // Convertir moneda
                                $saleCurrency = $rowIng["saleCurrency"];
                                switch ($saleCurrency) {
                                    case 'Euro':
                                        $saleCurrency = '€';
                                        break;
                                    default:
                                        // Puedes añadir otros símbolos si necesitas
                                        break;
                                }

                                echo '<td>' . htmlspecialchars($rowIng["costPrice"]) . $saleCurrency . ' for ' . htmlspecialchars($rowIng["unidad"]) . '</td>';
                                echo '<td>' . htmlspecialchars($rowIng["salePrice"]) . $saleCurrency . ' for ' . htmlspecialchars($rowIng["unidad"]) . '</td>';

                                $merma = $rowIng["merma"] * 100;
                                $merma = number_format($merma, 2) . "%";
                                echo '<td>' . $merma . '</td>';
                                echo '<td>' . htmlspecialchars($rowIng["warehouse"]) . '</td>';
                                echo '<td>' . htmlspecialchars($rowIng["packaging"]) . '</td>';
                                echo '<td>' . htmlspecialchars($rowIng["expira_dias"]) . '</td>';

                                echo "<td class='action_button'>
            <button class='btn-primary rounded' type='button' name='view_ing' id='view_ing' onclick='modalIng(" . $rowIng["ID"] . ")'>
                <img src='./../../svg/ingredients.svg' alt='Ing' title='Ingredients'>
            </button>

            <!-- 
            <button class='btn-primary rounded' type='button' name='edit_ing' id='edit_ing' onclick='editIng(" . $rowIng["ID"] . ")'>
                <img src='./../../svg/edit.svg' alt='Edit' title='Edit'>
            </button>
            -->

            <button class='btn-danger rounded' type='button' name='del_ing' id='del_ing' onclick='deleteIng(" . $rowIng["ID"] . ")'>
                <img src='./../../svg/delete_button.svg' alt='Delete' title='Delete'>
            </button>
          </td>";
                                echo '</tr>';
                            }
                            ?>
                        </tbody>

                    </table>
                </form>
            </div>
        </div>
    </div>
</div>