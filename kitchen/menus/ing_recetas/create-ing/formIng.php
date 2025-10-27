<php>

    <div class="container-fluid text-center align-center">


        <h2>Add ingredient</h2>


        <!-- Tarjeta del formulario -->
        <div class="card">
            <form id="ingredientForm" method="post" enctype="multipart/form-data">

                <!-- Sección 1: Información básica e imagen -->

                <div class="row mb-4">

                    <!-- Nombre y unidad de medida -->
                    <div class="form-group col-md-8 row align-items-center">

                        <!-- Nombre -->
                        <div class="col-md-6 mb-3">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <label for="nameIngredientNewIng" class="form-label">Name:</label>
                                </div>
                                <div class="col">
                                    <div style="max-width: 300px;">
                                        <input type="text" placeholder="Ingredient name" required
                                            name="nameIngredientNewIng" id="nameIngredientNewIng"
                                            class="form-control w-100" />

                                        <!-- Mensaje de feedback centrado -->
                                        <div class="text-center mt-1">
                                            <span id="nameIngredientFeedback" class="small"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Unidad de medida -->
                        <div class="col-md-6 d-flex align-items-center mb-3">
                            <label for="unitNewIng" class="form-label mb-0 me-2" style="min-width: 120px;">Measurement unit:</label>
                            <select class="form-select rounded" id="unitNewIng" name="unitNewIng" style="max-width: 200px;">
                                <option value="Kg">Kg</option>
                                <option value="L">L</option>
                                <option value="Und">Und</option>
                            </select>
                        </div>
                    </div>

                    <!-- Imagen -->
                    <div class="form-group col-md-4" id="contentImg">
                        <label for="imagenIng" id="label-image" class="w-100 text-center" style="cursor: pointer;">
                            <div id="contenedorimagenCh"
                                class="d-flex flex-column align-items-center justify-content-center w-100">

                                <img src="./../../../svg/image.svg"
                                    id="imagenChIng"
                                    alt="Insert Image"
                                    style="width: 180px; height: 150px; object-fit:cover; border:none" />

                                <p id="pCh" class="mt-2 mb-0">Upload image</p>
                                <input type="file" id="imagenIng" name="imagenNewIng" accept="image/*" hidden>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Sección 2: Costos y medidas -->

                <fieldset class="row mb-4 justify-content-center mb-5">
                    <legend>📊 Cost and Measures</legend>

                    <!-- Cost -->
                    <div class="col-md-2 d-flex flex-column">
                        <label for="costPriceNewIng" class="form-label" id="label-cost">Purchase Cost </label>
                        <span id="span-cost">(€/Kg)</span>
                        <input type="number" class="form-control" id="costPriceNewIng" name="costPriceNewIng" placeholder="0" step="0.01">
                    </div>

                    <!-- Sale -->
                    <div class="col-md-2 d-flex flex-column">
                        <label for="salePriceNewIng" class="form-label" id="label-sale">Selling Price</label>
                        <span id="span-sale">(€/Kg)</span>
                        <input type="number" class="form-control" id="salePriceNewIng" name="salePriceNewIng" placeholder="0" step="0.01">
                    </div>

                    <!-- Decrease -->
                    <div class=" col-md-2 d-flex flex-column">
                        <label for="mermaNewIng" class="form-label">Waste </label>
                        <span>(%)</span>
                        <input type="number" class="form-control" id="mermaNewIng" name="mermaNewIng" placeholder="0" step="0.01" required>
                    </div>

                    <!-- Expire -->
                    <div class="col-md-2 d-flex flex-column">
                        <label for="expireNewIng" class="form-label">Shelf Life</label>
                        <span> (days)</span>
                        <input type="number" class="form-control" id="expireNewIng" name="expireNewIng" placeholder="0">
                    </div>

                    <!-- Weight -->
                    <div class="col-md-2 d-flex flex-column">
                        <label for="unitWeight" class="form-label" id="label-weight" text-nowrap> Weight </label>
                        <span id="span-weight">(for Kg)</span>
                        <input type="number" class="form-control" id="unitWeight" name="unitWeight" placeholder="Weight (kg)" step="0.01" value="1">
                    </div>

                </fieldset>

                <!-- Sección 3: Embalaje y almacenamiento -->

                <fieldset class="row mb-4 justify-content-center mb-5">
                    <legend>📦 Packaging and Storage</legend>

                    <!-- Packaging -->
                    <div class="col-md-2">
                        <label for="packagingNewIng" class="form-label">Packaging</label>
                        <select class="form-select rounded" id="packagingNewIng" name="packagingNewIng">
                            <option value="Bag">Bag</option>
                            <option value="Pack">Pack</option>
                            <option value="Box">Box</option>
                            <option value="Bottle">Bottle</option>
                            <option value="Can">Can</option>
                        </select>
                    </div>

                    <!-- Warehouse -->
                    <div class="col-md-3">
                        <label for="warehouseNewIng" class="form-label">Storage type</label>
                        <select class="form-select rounded" id="warehouseNewIng" name="warehouseNewIng">
                            <option value="Freezer">Freezer</option>
                            <option value="Warehouse">Warehouse</option>
                            <option value="Final product area">Final product area</option>
                            <option value="Dry">Dry</option>
                        </select>
                    </div>

                    <!-- Food Classification -->
                    <div class="col-md-3">
                        <label for="foodClassificationIng" class="form-label">Category</label>
                        <select class="form-select rounded" id="foodClassificationIng" name="foodClassificationIng">
                            <option value="">-- Select --</option>
                            <option value="meat">Meat</option>
                            <option value="vegan">Vegan</option>
                            <option value="gluten-free">Gluten free</option>
                            <option value="condiment">Condiment</option>
                            <option value="pasta-and-rice">Pasta and rice</option>
                            <option value="fish-and-seafood">Fish and seafood</option>
                            <option value="sauce">Sauce</option>
                        </select>
                    </div>

                    <!-- Allergen -->
                    <div class="col-md-3">
                        <label for="alergenoNewIng" class="form-label">Allergen</label>
                        <select class="form-select rounded" id="alergenoNewIng" name="alergenoNewIng">
                            <?php foreach ($listaAlergenos as $alergeno) {
                                $nombreAlergeno = htmlspecialchars($alergeno->getNombre());
                                $idAlergeno = $alergeno->getId();
                                echo "<option value='$nombreAlergeno' data-id='$idAlergeno'>$nombreAlergeno</option>";
                            } ?>
                        </select>
                    </div>
                </fieldset>

                <!-- Sección 4: Descripción -->

                <fieldset class="row mb-4 justify-content-center mb-5">
                    <legend>📝 Short Description</legend>


                    <div class="col-md-8">

                        <textarea class="form-control" id="descripcionCortaIng" name="descripcionCortaIng" rows="2" placeholder="Short description"></textarea>
                    </div>
                </fieldset>

                <!-- Sección E-commerce  -->
                <fieldset class="mb-4 border-0 p-0">
                    <legend class="w-100 p-0" style="margin-bottom: 0;">
                        <button type="button"
                            class="legend-button"
                            id="toggleAttributesIng">
                            🛒 E-commerce Sales Quantities
                            <i class="bi bi-chevron-down fs-5" id="arrowIconIng"></i>
                        </button>
                    </legend>

                    <div id="formContentIngDesp" class="collapse">
                        <div class="row pt-3">
                            <div class="form-group col-md-12">
                                <div id="attributesContainer" class="m-4">
                                    <div class="attribute">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <input type="text" id="attributeName" name="attributeName"
                                                    class="form-control" placeholder="e.g., Weight"
                                                    style="max-width: 200px;display:none;"
                                                    value="Quantity in kg" readonly>
                                            </div>
                                            <div class="col-md-12">
                                                <div id="valuesContainer" class="d-flex align-items-start">
                                                    <div class="ml-5">
                                                        <div class="d-flex align-items-center">
                                                            <label for="attributeValues" id="idLabelAttribute">Quantities:</label>
                                                            <input type="number" id="attributeValue" class="form-control"
                                                                placeholder="e.g., 1" style="max-width: 100px;"
                                                                min="0.5" step="0.5">
                                                        </div>
                                                        <div class="d-flex align-items mt-5">
                                                        <button  type="button" id="clearValues" class="clear-btn">
                                                                Clear
                                                            </button>
                                                            <button id="addValue" type="button" class="btn btn-primary ">Add</button>
                                                           
                                                        </div>
                                                    </div>
                                                    <div class="w-100 h-100 border border-2 border-dark rounded p-1 ml-5"
                                                        id="valuesList"
                                                        style="max-width: 450px;min-height: 100px;"></div>



                                                    <div id="contentAllValues" style="margin-top: 10px;">
                                                        <input type="text" id="allValues" class="form-control"
                                                            style="max-width: 300px;display:none;" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </fieldset>
                <hr>

                <!-- Botones de acción -->
                <div class="form-group col-md-12 button-container">
                    <button id="cancelIngredient" type="button" class="btn btn-danger">Cancel</button>
                    <button id="saveIngredient" name="saveIngredient" type="submit" class="btn btn-primary">Add Ingredient</button>
                </div>

            </form>
        </div>

    </div>