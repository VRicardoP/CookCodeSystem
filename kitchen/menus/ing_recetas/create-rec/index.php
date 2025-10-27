<?php

require __DIR__ . '/../../../models/ingredientes.php';
require_once __DIR__ . '/../../../models/ingredientesDao.php';
require __DIR__ . '/../../../models/alergenos.php';
require_once __DIR__ . '/../../../models/alergenosDao.php';
require __DIR__ . '/../../../models/recetas.php';
require_once __DIR__ . '/../../../models/recetasDao.php';
require __DIR__ . '/../../../models/precioProducto.php';
require_once __DIR__ . '/../../../models/precioProductoDao.php';

require_once __DIR__ . '/../../../DBConnection.php';

// Establecer conexión con la base de datos (PDO)
$pdo = DBConnection::connectDB();
if (!$pdo) {
    die("No se pudo establecer la conexión con la base de datos.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["modfProduct"])) {
    $list = $_POST["listIdProduct"];
    $arrayIds = explode(",", $list);

    foreach ($arrayIds as $id) {
        $obj = new PreciosProducto();
        $obj->setId($id);
        $obj->setProducto($_POST["name" . $id]);
        $obj->setUnidad($_POST["unit" . $id]);
        $obj->setPrecio($_POST["sale" . $id]);

        PreciosProductoDAO::update($obj);
    }
} else if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["newProduct"])) {
    $obj = new PreciosProducto();
    $obj->setProducto($_POST["nameProduct"]);
    $obj->setUnidad($_POST["unitProduct"]);
    $obj->setPrecio($_POST["saleProduct"]);

    PreciosProductoDAO::insert($obj);
}

// Obtener lista de alérgenos
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
    <link rel="icon" type="image/png" href="./../../../img/logo.png">

    <!-- Custom fonts for this template-->
    <script src="https://kit.fontawesome.com/2e2cc992ca.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href=" https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css" rel="stylesheet" type="text/css">
    <!-- Custom styles for this template-->
    <link href="./../../../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="./../../../css/navs.css" rel="stylesheet">
    <link href="./../css/recipes.css" rel="stylesheet">
    <script type="module" src="./js/formRecipe.js" defer></script>
    <style>
        .row input[type="number"] {
            text-align: right;

        }

        .row label {
            text-align: right;
            margin-right: 5px;
        }

        .row select {
            text-align: center;

        }

        #costo-total-receta {
            font-size: 1.0em;
            font-weight: bold;
        }

        #coste-por-racion {
            font-size: 1.0em;
            font-weight: bold;
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

 



    $imgProfile = "./../../../img/undraw_profile.svg";
    $pathDashboard = "./../../../dashboard";
    $pathLogo = "./../../../img/ccsLogoWhite.png";
    $pathLogout = "./../../../login/logout.php";


    $menu_options = [
        'dashboard' => ['url' => './../../dashboard', 'icon' => './../../../svg/dashboard.svg', 'text' => 'Dashboard'],
        'users' => ['url' => './../../users', 'icon' => './../../../svg/user.svg', 'text' => 'User'],
       // 'qr' => ['url' => './../../qrcode/generator/tickets.php', 'icon' => './../../../svg/qr_code.svg', 'text' => 'QR'],
        'ecommerce' => ['url' => 'http://localhost:8080/ecommerce', 'icon' => './../../../svg/tpv.svg', 'text' => 'E-commerce'],
        'restaurant' => ['url' => 'http://localhost:8080/restaurant/public/restaurants.html', 'icon' => './../../../svg/restaurant.svg', 'text' => 'Restaurant'],
        'elaborations' => ['url' => './../../elaborations', 'icon' => './../../../svg/recipes.svg', 'text' => 'Elaborations'],
        'Ing/Recipes' => ['url' => './../../ing_recetas', 'icon' => './../../../svg/recipe_white.svg', 'text' => 'Ing/Recipes'],
        'Plating' => ['url' => './../../plating', 'icon' => './../../../svg/plato_blanco.svg', 'text' => 'Dish composition'],
        'Mise en Place' => ['url' => './../../miseEnPlace', 'icon' => './../../../svg/miseEnPlace.svg', 'text' => 'Mise en Place'],
        'stock' => ['url' => './../../stock', 'icon' => './../../../svg/stock.svg', 'text' => 'Order tracking'],
        'suppliers' => ['url' => './../../suppliers', 'icon' => './../../../svg/orders.svg', 'text' => 'Suppliers'],
        'economic' => ['url' => '#', 'icon' => './../../../svg/graph.svg', 'text' => 'Economic'],




    ];


    ?>

    <?php include './../../../includes/session.php'; ?>
    <?php include './../../../includes/navs.php'; ?>

    <?php insertarTopNav('./../create-ing', './../../../svg/orders_Black.svg', 'Create ingredient'); ?>


    <?php insertarTopNav('./../', './../../../svg/orders_Black.svg', 'List'); ?>
    <!-- Page Wrapper -->

    
    <div class="container-fluid text-center align-center">

        <!-- End of Topbar -->
        <!-- ************************************************************************************************************************ -->
        <!-- Begin Page Content -->

        <div class="wrap-toggle">
            <h3>Elaborations</h3>
            <input type="checkbox" id="toggle" class="offscreen" />
            <label for="toggle" class="switch"></label>
        </div>

        <!-- Contenido de la sección de nueva receta -->
        <h2 id="titulo">New pre-prepared</h2>

        <div class="card">
            <form id="recipeForm" method="post" style="display: block;" enctype="multipart/form-data">
                <div class="row">
                    <!--Tipo de receta (Elaborado/Preelaborado) Oculto, se activa con el switch-->
                    <input type="text" id="tipo_receta" name="tipo" value="Pre-Elaborado" hidden>


                    <div class="form-group col-md-8 d-flex align-items-center">
                        <label for="nameRecipe">Recipe name: </label>

                        <input type="text" required name="nameRecipe" placeholder="Recipe name" id="nameRecipe" class="form-control" style="max-width: 200px;" />

                    </div>



                    <div class="form-group col-md-4 d-flex align-items-right">
                        <label for="imagen" style="cursor: pointer;">
                            <div id="contenedorimagenCh">
                                <img src="./../../../svg/image.svg" id="imagenCh" alt="Insert Image" />
                            </div>


                            <p id="pCh">Click on the photo to Insert Image</p>
                            <input type="file" id="imagen" name="imagen" accept="image/*" onchange="previewImage(event)">
                        </label>
                    </div>
                </div>

                <div class="row">

                    <div class="form-group col-md-4 d-flex align-items-end">
                        <label>Rations:</label>
                        <input type="number" required name="rationsRecipe" id="rationsRecipe" placeholder="0" class="form-control" style="max-width: 100px;" />

                    </div>


                    <div class="form-group col-md-4 d-flex align-items-end">
                        <label>Expire days:</label>

                        <input type="number" id="caducidad" name="caducidad" value="" placeholder="0" class="form-control text-right-input" style="max-width: 100px;" required/>

                    </div>

                    <div class="form-group col-md-4 d-flex align-items-end">
                        <label>Packaging:</label>

                        <select name="packaging" id="packaging" class="form-control" style="max-width: 200px;">
                            <option value="Bag">Bag</option>
                            <option value="Pack">Pack</option>
                            <option value="Box">Box</option>
                            <option value="Bottle">Bottle</option>
                            <option value="Can">Can</option>
                        </select>
                    </div>
                </div>



                <div class="row">
                    <div class="form-group col-md-5 d-flex align-items-end">
                        <label>Localization:</label>

                        <select name="warehouse" id="warehouse" class="form-control" style="max-width: 200px;">
                            <option value="Freezer">Freezer</option>
                            <option value="Warehouse">Warehouse</option>
                            <option value="Final product area">Final product area</option>
                            <option value="Dry">Dry</option>
                        </select>
                    </div>

                    <div class="form-group col-md-7 d-flex align-items-end">
                        <label for="descripcionCorta">Short description:</label>

                        <textarea id="descripcionCorta" class="form-control" placeholder="Short description" rows="1" style="max-width: 250px;"></textarea>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-5 d-flex align-items-center">
                        <label for="category">Category:</label>
                        <select name="category" id="category" class="form-control" style="width: 160px;" >
                            <option value="carnes">Meat</option>
                            <option value="condimentos">Condiments</option>
                            <option value="pastas_y_arroces">Pasta & rice</option>
                            <option value="pescado_y_mariscos">Fish & seafood</option>
                            <option value="salsas">Sauces</option>
                            <option value="sin_gluten">Gluten free</option>
                            <option value="vegano">Vegan</option>
                        </select>
                    </div>
                </div>


                <!-- PREELABORADOS -->

                <div id="pre-prepared-container">
                    <hr>

                    <div class="tabla-container">
                        <h3>Pre-prepared</h3>
                        <table id="elaboradosTable" class="styled tabla">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Rations</th>
                                    <th>Amount</th>
                                    <th>Cost</th>
                                </tr>
                            </thead>
                            <tbody id="newRecipeTable-elab-tbody"></tbody>
                        </table>
                    </div>

                    <div id="coste-total-elaborados">Total Cost of Elaborated: 0.00€</div>

                </div>
                <hr>
                
                <div class="tabla-container">
                <h3>Ingredients</h3>
                    <table id="newRecipeTable" class=" tabla">
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
                </div>
                <div id="coste-total-ingredientes">Total Cost of Ingredients: 0.00€</div>

                <hr>

                <div class="row">
                    <div class="form-group col-md-5  align-items-end">

                        <div id="resultadosNewRecipe">
                            <div id="coste-total">
                                <div class="button-container">
                                    <button type="button" id="btnCosteRecipe" class="btn btn-primary submitBtn">Calculate Cost</button>

                                </div>



                                <span id="coste-por-racion"></span><br>
                                <span id="costo-total-receta"></span>
                            </div>
                        </div>

                    </div>
                    <div class="form-group col-md-7  align-items-end">
                        <div id="newRecipe-instructions">
                            <label for="instrucciones">Instructions:</label>
                            <textarea name="instrucciones" id="newRecipe-instructions-textarea" rows="2" class="form-control"></textarea>
                        </div>
                    </div>

                </div>
                <hr>
                <div class="button-container">

                    <button id="saveRecipe" name="saveRecipe" type="submit" class="btn btn-primary submitBtn">Save</button>
                </div>
            </form>


        </div>



    </div>




    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <script>
        var title = document.getElementById("titulo");
        var prePreparedContainer = document.getElementById("pre-prepared-container");
        var tipo_receta = document.getElementById("tipo_receta");

        document.getElementById("toggle").addEventListener("change", function() {
            if (this.checked) {
                title.textContent = "New elaboration";
                prePreparedContainer.style.display = "block";
                tipo_receta.value = "Elaborado";
            }
            else{
                title.textContent = "New pre-prepared";
                prePreparedContainer.style.display = "none";
                tipo_receta.value = "Pre-Elaborado";
            }
        });

        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById('imagenCh');
                output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>



    <!-- Bootstrap core JavaScript-->
    <script src="./../../../vendor/jquery/jquery.min.js"></script>
    <script src="./../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="./../../../vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="./../../../js/sb-admin-2.min.js"></script>


</body>



</html>