<?php

require __DIR__ . '/../../models/ingredientes.php';
require_once __DIR__ . '/../../models/ingredientesDao.php';
require __DIR__ . '/../../models/alergenos.php';
require_once __DIR__ . '/../../models/alergenosDao.php';
require __DIR__ . '/../../models/recetas.php';
require_once __DIR__ . '/../../models/recetasDao.php';
require __DIR__ . '/../../models/precioProducto.php';
require_once __DIR__ . '/../../models/precioProductoDao.php';





if ($_SERVER["REQUEST_METHOD"] == "POST" && isset(($_POST["modfProduct"]))) {
    $list = $_POST["listIdProduct"];
    var_dump($list);
    $arrayIds = explode(",", $list);

    foreach ($arrayIds as $id) {

        $obj = new PreciosProducto();
        $obj->setId($id);
        $obj->setProducto($_POST["name" . $id]);
        $obj->setUnidad($_POST["unit" . $id]);
        $obj->setPrecio($_POST["sale" . $id]);

        PreciosProductoDAO::update($obj);
    }
} else if ($_SERVER["REQUEST_METHOD"] == "POST" && isset(($_POST["newProduct"]))) {
    $obj = new PreciosProducto();
    // $obj->setId($id);
    $obj->setProducto($_POST["nameProduct"]);
    $obj->setUnidad($_POST["unitProduct"]);
    $obj->setPrecio($_POST["saleProduct"]);

    PreciosProductoDAO::insert($obj);
}



$listaAlergenos = AlergenoDao::getAll();
?>


<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Cook code</title>


    <!-- Custom fonts for this template-->
    <script src="https://kit.fontawesome.com/2e2cc992ca.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href=" https://cdn.datatables.net/2.0.8/css/dataTables.dataTables3" rel="stylesheet" type="text/css">
    <!-- Custom styles for this template-->
    <link href="./../../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="./../../css/navs.css" rel="stylesheet">
    <link rel="stylesheet" href="./../../css/elaborations/recipesC.css">
    <link href="./../../css/qr/tickets.css" rel="stylesheet">
    <link href="./css/recipes.css" rel="stylesheet">
    <script src="./js/formRecipe.js" defer></script>
    <style>
        #btnPrint {
            float: right;
        }

        .marco {
            background-color: white;
        }

        /* Fondo oscuro que cubre la página */
        #backgroundOverlay {
            display: none;
            /* Se oculta por defecto */
            position: fixed;
            /* Fijo para que cubra toda la pantalla */
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: transparent;
            /* Hacemos el fondo transparente */
            backdrop-filter: blur(2px);
            /* Aplica el desenfoque al fondo */
            z-index: 3;
            /* Se coloca detrás del modal */
        }

        /* Asegúrate de que el modal esté por encima del fondo oscuro */
        .modal {
            z-index: 4;
            /* El modal estará por encima del fondo oscuro */
        }

        @media print {
            @page {}



            nav {
                display: none;
            }

            button {
                display: none;
            }

            #sidebar {
                display: none;
            }

            #divContent {
                display: none;
            }


        }
    </style>
</head>

<body id="page-top">
    <?php
    $host = "localhost";
    $username = "root";
    $password = "";
    $database = "kitchentag";
    /* Attempt to connect to MySQL database */
    $link = new mysqli($host, $username, $password, $database);

    // Check connection
    if ($link->connect_error) {
        die("Connection failed: " . $link->connect_error);
    }
    ?>

    <?php include './../../includes/session.php'; ?>
    <?php include './../../includes/navs.php'; ?>

    <?php insertarTopNav('createIng.php', './../../svg/orders_Black.svg', 'Create ingredient'); ?>


    <?php insertarTopNav('index.php', './../../svg/orders_Black.svg', 'List'); ?>
    <!-- Page Wrapper -->
    <div id="wrapper" class="d-flex flex-column">

        <!-- End of Topbar -->
        <!-- ************************************************************************************************************************ -->
        <!-- Begin Page Content -->
        <div class="container-fluid">

            <!-- Page Heading -->
   
            <!-- Content Row -->
            <div class="row">

                <div id="divContent" class="col-md-12">



                    <div id="nuevaReceta" class="tabcontent">
                        <!-- Contenido de la sección de nueva receta -->
                        <h2>New pre-prepared</h2>
                        <p>Here you can create the pre-prepareds.</p>

                        <div class="col-md-12">

                            <div style="overflow-x:auto;">
                                <form id="recipeForm" method="post" style="display: block;" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="form-group col-md-2">
                                            <label for="imagen" style="cursor: pointer;">
                                                <div id="contenedorimagenCh">
                                                    <img src="./../../svg/image.svg" id="imagenCh" alt="Insert Image" width="150" height="150" />
                                                </div>
                                            </label>
                                        </div>
                                        <div class="form-group col-md-10">
                                            <p id="pCh">Click on the photo to Insert Image</p>
                                            <input type="file" id="imagen" name="imagen" accept="image/*" onchange="previewImage(event)">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-md-2">
                                            <label>Recipe name:</label>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <input type="text" required name="nameRecipe" id="nameRecipe" class="form-control" />
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label>Rations:</label>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <input type="number" required name="rationsRecipe" id="rationsRecipe" class="form-control" />
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-md-2">
                                            <label>Expiration</label>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <input type="number" id="caducidad" name="caducidad" value="" placeholder="0" class="form-control text-right-input" />

                                        </div>
                                        <div class="form-group col-md-2">
                                            <label>Packaging</label>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <select name="packaging" id="packaging" class="form-control">
                                                <option value="Bag">Bag</option>
                                                <option value="Pack">Pack</option>
                                                <option value="Box">Box</option>
                                                <option value="Bottle">Bottle</option>
                                                <option value="Can">Can</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-md-2">
                                            <label>Select localization</label>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <select name="warehouse" id="warehouse" class="form-control">
                                                <option value="Freezer">Freezer</option>
                                                <option value="Warehouse">Warehouse</option>
                                                <option value="Final product area">Final product area</option>
                                                <option value="Dry">Dry</option>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-2">
                                            <label for="descripcionCorta">Descripción corta:</label>

                                        </div>
                                        <div class="form-group col-md-4">

                                            <textarea id="descripcionCorta" class="form-control" rows="1"></textarea>
                                        </div>


                                    </div>
                                    <hr>
                                    <div id="elaboradosSection">
                                        <h3>Elaborados</h3>
                                        <table id="elaboradosTable">
                                            <thead>
                                                <tr>
                                                    <th>Nombre</th>
                                                    <th>Cantidad</th>
                                                    <th>Coste</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>


                                    <hr>
                                    <table id="newRecipeTable" class="styled">
                                        <thead>
                                            <tr>
                                                <th>Ingredient</th>
                                                <th>Amount</th>

                                                <th>% Decrease</th>
                                                <th>Total Loss</th>
                                                <th>Total Gross</th>
                                                <th>Final Cost (€)</th>
                                            </tr>
                                        </thead>
                                        <tbody id="newRecipeTable-tbody">
                                            <!-- Las filas se crearán dinámicamente aquí -->
                                        </tbody>
                                    </table>

                                    <div id="newRecipe-instructions">
                                        <label for="instrucciones">Instructions:</label>
                                        <textarea name="instrucciones" id="newRecipe-instructions-textarea" class="form-control"></textarea>
                                    </div>

                                    <div class="button-container">
                                        <button type="button" id="btnCosteRecipe" class="btn btn-primary submitBtn">Calculate Cost</button>
                                        <button id="saveRecipe" name="saveRecipe" type="submit" class="btn btn-primary submitBtn">Save</button>
                                    </div>
                                </form>

                                <div id="resultadosNewRecipe">
                                    <span id="costo-total-receta"></span>
                                    <span id="coste-por-racion"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->


            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- The Modal recetas-->
    <div id="recipeModal" class="modal">
        <!-- Modal content -->
        <div class="modal-content">
            <div class="marco">

                <button class='btn-primary rounded' id='btnPrint' type='button' name='view_recipe' id='view_recipe' onclick="imprimir()">
                    <img src='./../../svg/printing.svg' alt='Ing' title='Ingredients' style="width: 25px; height:25px">
                </button>
            </div>

            <div class="modal-header">
                <div class="">
                    <h2 id="nombreQr"></h2>
                </div>
                <div class="">
                    <img id="imagenQr">


                </div>
            </div>
            <div class="modal-body">
                <p id="empaquetadoQr"></p>
                <p id="almacenajeQr"></p>
                <p id="elabQr"></p>
                <p id="cadQr"></p>
                <p id="costeQr"></p>
                <p id="ventaQr"></p>
            </div>
            <h3>Ingredients</h3>

            <div class="modal-ingredients" id="ingredientsList">

                <!-- Ingredients will be dynamically inserted here -->
            </div>
            <h3>Instrucciones</h3>
            <div class="modal-ingredients" id="divInstrucciones">

                <!-- Ingredients will be dynamically inserted here -->
            </div>
        </div>
    </div>




    <!-- The Modal -->
    <div id="ingredientsModal" class="modal">
        <!-- Modal content -->
        <div class="modal-content">
            <div class="marco"></div>
            <hr>
            <div class="modal-header">
                <div class="">
                    <h2 id="nombreIng"></h2>
                </div>

            </div>
            <div class="modal-body">
                <form method="get">
                    <label for="coste">Cost:</label>
                    <input id="coste">
                    <button type="submit" id="btnCostIng">Save</button>
                </form>
            </div>

        </div>
    </div>
    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- The Background Overlay -->
    <div id="backgroundOverlay"></div>
    <script>
        function showWeightField() {
            const unitSelect = document.getElementById('unitNewIng');
            const weightField = document.getElementById('weightField');

            // Si se selecciona "Und", mostrar el campo de peso
            if (unitSelect.value === 'Und' || unitSelect.value === 'L') {
                weightField.style.display = 'block';
            } else {
                weightField.style.display = 'none';
            }
        }



        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById('imagenCh');
                output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }

        function previewImageIng(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById('imagenChIng');
                output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }

        function deleteIng(id) {

            if (window.confirm("¿Estás seguro de que quieres eliminarlo?")) {
                deleteItem(id, 'ing');
            }



        }

        function deleteItem(id, type) {
            console.log("id: " + id);
            console.log("type: " + type);

            let dataToSend = {
                id: id,
                type: type
            };

            $.ajax({
                url: './../../controllers/eliminarIngredienteRecipes.php',
                type: 'POST',
                data: dataToSend,
                success: function(response) {
                    console.log(response);
                    // Elimina la fila de la tabla correspondiente
                    $('#row-' + type + '-' + id).remove();
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }



        function editIng(id) {
            var modal = document.getElementById("ingredientsModal");
            modal.style.display = "block";
            backgroundOverlay.style.display = "block";
            $(document).ready(function() {
                // No es necesario volver a seleccionar el modal aquí
                // var modal = document.getElementById("ingredientsModal");

                $('button[id="btnCostIng"]').click(function(e) {
                    e.preventDefault(); // Prevent form submission
                    var cost = document.getElementById('coste').value;

                    console.log('Cost:', cost); // Depuración

                    // Make an AJAX request to save the ingredient cost
                    $.ajax({
                        url: './../../controllers/get_ingredients.php',
                        type: 'GET',
                        data: {
                            editIng: id,
                            cost: cost
                        },
                        success: function(data) {
                            console.log('Response:', data); // Depuración
                            modal.style.display = "none";
                        },
                        error: function(xhr, status, error) {
                            console.error('Error:', error); // Manejo de errores
                        }
                    });
                });

                window.onclick = function(event) {
                    if (event.target == modal) {
                        modal.style.display = "none";
                        backgroundOverlay.style.display = "none";
                        var ingredientsList = document.getElementById("ingredientsList");
                        ingredientsList.style.display = "flex";
                    }
                }








            });
        }
    </script>

    <script>
        function modalRecipe(id) {
            console.log(id);
            $(document).ready(function() {
                // Get the modal


                // Get the <span> element that closes the modal
                var span = document.getElementsByClassName("close")[0];



                // Get the button ID or data
                var recipeId = id; // Use the id passed to the function

                // Make an AJAX request to get the ingredients
                $.ajax({
                    url: './../../controllers/get_ingredients.php',
                    type: 'GET',
                    data: {
                        recipeId: recipeId
                    },
                    success: function(data) {
                        // Clear the ingredients list before adding new data
                        var ingredientsList = document.getElementById("ingredientsList");


                        // Handle the recipe data
                        var raciones = data['raciones'] > 1 ?
                            " (" + data['raciones'] + " raciones)" :
                            " (" + data['raciones'] + " racion)";

                        var nombre = document.getElementById('nombreQr');
                        nombre.innerHTML = data['nombre'] + raciones;

                        var imagen = document.getElementById('imagenQr');
                        imagen.src = './../' + data['imagen'];

                        var caducidad = document.getElementById('empaquetadoQr');
                        caducidad.innerHTML = "Expire in " + data['caducidad'] + " days";

                        var peso = document.getElementById('almacenajeQr');
                        peso.innerHTML = "Weight: " + data['peso'] + "kg";

                        var localizacion = document.getElementById('elabQr');
                        localizacion.innerHTML = "Localization: " + data['localizacion'];


                        var empaquetado = document.getElementById('cadQr');
                        empaquetado.innerHTML = "Package: " + data['empaquetado'];







                        // Populate ingredients list
                        if (Array.isArray(data['ingredientes'])) {
                            data['ingredientes'].forEach(ingredient => {
                                var p = document.createElement('p');
                                p.textContent = ingredient['nombre'] + " " + ingredient['cantidad'] + " " + ingredient['unidad'];
                                ingredientsList.appendChild(p);
                            });
                        } else {
                            console.error("La respuesta no contiene un array de ingredientes.");
                        }

                        var divInstrucciones = document.getElementById('divInstrucciones');
                        var p = document.createElement('p');

                        p.textContent = data['instrucciones'];
                        divInstrucciones.appendChild(p);
                        var modal = document.getElementById("recipeModal");
                        // Show the modal
                        modal.style.display = "block";
                        backgroundOverlay.style.display = "block";
                    },
                    error: function(xhr, status, error) {
                        console.error("Error al cargar los ingredientes:", status, error);
                    }
                });


                // Close the modal when the user clicks anywhere outside of the modal
                window.onclick = function(event) {
                    var modal = document.getElementById("recipeModal");
                    if (event.target == modal) {
                        modal.style.display = "none";
                        var divInstrucciones = document.getElementById('divInstrucciones');
                        divInstrucciones.innerHTML = "";
                        var ingredientsList = document.getElementById("ingredientsList");
                        ingredientsList.innerHTML = "";

                        backgroundOverlay.style.display = "none";
                    }
                };

                // Close the modal when the close button is clicked
                var span = document.getElementsByClassName("close")[0];
                span.onclick = function() {
                    var modal = document.getElementById("recipeModal");
                    modal.style.display = "none";
                };
            });
        }



        function imprimir() {
            window.print();
        }
    </script>

    <!-- Bootstrap core JavaScript-->
    <script src="./../../vendor/jquery/jquery.min.js"></script>
    <script src="./../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="./../../vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="./../../js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="./../../vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="./../../vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="./../../js/demo/datatables-demo.js"></script>
</body>



</html>