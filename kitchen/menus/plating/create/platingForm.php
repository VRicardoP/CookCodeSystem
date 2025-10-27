<section>
    <form id="elaboracionForm" action="" method="POST" enctype="multipart/form-data">
        <card>


            <div class="row">
                <!-- Campo para el nombre del plato -->
                <div class="form-group col-md-7">
                    <label>Name of the dish:</label>
                    <input type="text" id="nombre" name="nombre" placeholder="Enter the name of the dish" required>
                </div>

                <!-- Campo para la imagen -->
                <div class="form-group col-md-3 d-flex align-items-right">
                    <label for="imagen" style="cursor: pointer;">
                        <div id="contenedorimagenCh">
                            <img src="./../../../svg/image.svg" id="imagenCh" alt="Insert Image" width="150" height="150" />
                        </div>
                        <p id="pCh" class="">Click on the photo to Insert Image</p>
                        <input type="file" id="imagen" name="imagen" accept="image/*" onchange="previewImage(event)" style="display: none;">

                    </label>

                </div>
            </div>




            <!-- Tabla para los platos elaborados -->
            <h3>Pre-prepared dishes:</h3>
            <table id="platosElaboradosTabla">
                <tr>
                    <th>Name of the Dish</th>
                    <th>Amount</th>
                </tr>
                <tr>
                    <td><input type="text" name="platos_elaborados[nombre][]" class="platoElaborado" list="list_recipes" placeholder="Name of the pre-made dish">
                        <datalist id="list_recipes">
                            <?php
                            foreach ($allRecetas as $receta) {
                                echo "<option value='" . $receta->getReceta() . "' data-id='" . $receta->getId() . "'>" . $receta->getReceta() . "</option>";
                            }
                            ?>

                        </datalist>

                    </td>
                    <td><input type="number" name="platos_elaborados[cantidad][]" class="cantidadPlatoElaborado" placeholder="Amount"></td>
                </tr>
            </table>

            <!-- Tabla para los ingredientes -->
            <h3>Ingredients:</h3>
            <table id="ingredientesTabla">
                <tr>
                    <th>Ingredient</th>
                    <th>Amount</th>
                    <th>Unit</th>
                </tr>
                <tr>
                    <td><input type="text" name="ingredientes[nombre][]" list="list_ing" class="ingrediente" placeholder="Ingredient"
                            onchange="updateUnit(this)">
                        <datalist id="list_ing">
                            <?php
                            foreach ($allIngredientes as $ingrediente) {
                                echo "<option value=" . $ingrediente->getFName() . " data-id=" . $ingrediente->getId() . "  data-und=" . $ingrediente->getUnidad() . " > </option>";
                            }
                            ?>

                        </datalist>

                    </td>
                    <td><input type="number" name="ingredientes[cantidad][]" class="cantidad" placeholder="Amount" step="0.01"></td>

                    <td>
                        <input type="text" name="ingredientes[unidad][]" class="unidad" placeholder="Unit" readonly>
                    </td>

                </tr>
            </table>

            <!-- Campo para las instrucciones -->
            <label>Instructions:</label>
            <textarea id="instrucciones" name="instrucciones" rows="4" cols="50" placeholder="Enter the instructions for preparation" required></textarea>


            <button type="submit">Save Plate</button>
        </card>
    </form>
</section>