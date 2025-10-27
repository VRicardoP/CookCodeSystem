<div id="receta" class="tabcontent">
    <!-- Contenido de la sección de la receta -->

    <p>Here you can see the pre-prepareds.</p>
    <div class="card shadow mb-4 ">
        <div class="card-header">
            <h6 class="m-0 font-weight-bold text-primary">Pre-prepareds</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">

                <table class="display" id="tableRecipes">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Cost</th>
                            <th>Nº Rations</th>

                            <th>Location</th>
                            <th>Packaging</th>
                            <th>Expire days</th>
                            <th>Actions</th>

                        </tr>
                    </thead>
                    <tbody id="receta-tbody">
                        <?php
                     

                        $queryReceta = "SELECT * FROM `recetas` WHERE `tipo` = 'Pre-Elaborado'";
                        $stmtReceta = $link->query($queryReceta);
                        $recetas = $stmtReceta->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($recetas as $rowReceta) {
                            echo '<tr>';

                            echo '<td class="img-table"><img src="./.' . $rowReceta["imagen"] . '" alt="Rec" title="Recipe"></td>';
                            echo '<td>' . htmlspecialchars($rowReceta["receta"]) . '</td>';

                            // Ingredientes por receta
                            $queryRecetaIng = "SELECT * FROM `receta_ingrediente` WHERE `receta` = :receta_id";
                            $stmtRecetaIng = $link->prepare($queryRecetaIng);
                            $stmtRecetaIng->execute([':receta_id' => $rowReceta['id']]);
                            $ingredientes = $stmtRecetaIng->fetchAll(PDO::FETCH_ASSOC);

                            $costeTotal = 0;

                            foreach ($ingredientes as $rowRecetaIng) {
                                $idIngrediente = $rowRecetaIng['ingrediente'];
                                $cantidad = $rowRecetaIng['cantidad'];

                                $queryIng = "SELECT * FROM `ingredients` WHERE `ID` = :id";
                                $stmtIng = $link->prepare($queryIng);
                                $stmtIng->execute([':id' => $idIngrediente]);
                                $rowIng = $stmtIng->fetch(PDO::FETCH_ASSOC);

                                if ($rowIng) {
                                    $costeIngrediente = $rowIng['costPrice'];
                                    $costeCantidadIng = $cantidad * $costeIngrediente;
                                    $costeTotal += $costeCantidadIng;
                                }
                            }

                            echo '<td>' . number_format($costeTotal, 2) . '€</td>';
                            echo '<td>' . htmlspecialchars($rowReceta["num_raciones"]) . '</td>';
                            echo '<td>' . htmlspecialchars($rowReceta["localizacion"]) . '</td>';
                            echo '<td>' . htmlspecialchars($rowReceta["empaquetado"]) . '</td>';
                            echo '<td>' . htmlspecialchars($rowReceta["expira_dias"]) . '</td>';

                            echo "<td class='action_button'>
            <button class='btn-primary rounded' type='button' name='view_recipe' id='view_recipe' onclick='modalRecipe(" . $rowReceta["id"] . ")'>
                <img src='./../../svg/ingredients.svg' alt='Ing' title='Ingredients'>
            </button>
          </td>";

                            echo '</tr>';
                        }
                        ?>
                    </tbody>

                </table>


                <div id="resultados"></div>

            </div>
        </div>
    </div>
</div>