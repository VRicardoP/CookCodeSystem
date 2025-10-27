<?php

require __DIR__ . '/../../models/elaboraciones.php';
require_once __DIR__ . '/../../models/elaboracionesDao.php';
require __DIR__ . '/../../models/almacenIngredientes.php';
require_once __DIR__ . '/../../models/almacenIngredientesDao.php';

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET["ing_elab"])) {
        $id = $_GET["ing_elab"];
        header("Location: listIngr.php?ing_elab=" . urlencode($id));
        exit();
    } elseif (isset($_GET["edit_elab"])) {
        $id = $_GET["edit_elab"];
        header("Location: formElaborado.php?edit_elab=" . urlencode($id));
        exit();
    } elseif (isset($_GET["edit_ing"])) {
        $id = $_GET["edit_ing"];
        header("Location: formIngrediente.php?edit_ing=" . urlencode($id));
        exit();
    } elseif (isset($_GET["elab"])) {
        $id = $_GET["elab"];
        header("Location: printElaborations.php?elab=" . urlencode($id));
        exit();
    } elseif (isset($_GET["tag_ing"])) {
        $id = $_GET["tag_ing"];
        header("Location: printElaborations.php?tag_ing=" . urlencode($id));
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Cook Code</title>
    <link rel="icon" type="image/png" href="./../../img/logo.png">
    <!-- Custom fonts for this template-->
    <script src="https://kit.fontawesome.com/2e2cc992ca.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="./../../css/sb-admin-2.min.css" rel="stylesheet">
    <link href=" https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css" rel="stylesheet" type="text/css">
    <link href="./../../css/tables.css" rel="stylesheet">
    <link href="./../../css/model.css" rel="stylesheet">
    <link href="./../../css/navs.css" rel="stylesheet">

    <style>
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
    </style>
</head>

<body id="page-top">
    <?php include './../../includes/session.php'; ?>
    <?php include './../../includes/navs.php'; ?>

    <?php insertarTopNav('./create', './../../svg/recipe_Black.svg', 'Create'); ?>

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
        <!-- Main Content -->
        <div id="content">
            <!-- Begin Page Content -->
            <div class="container-fluid">
                <?php

                $link = DBConnection::connectDB();
                // Check connection
                if ($link === null) {
                    die("Connection failed.");
                }
                ?>

                <!-- Page Heading -->

                <!-- CONTROL ELABORATIONS -->

                <div class="card shadow mb-4 ">
                    <div class="card-header">
                        <h6 class="m-0 font-weight-bold text-primary">Control elaborations</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <form name="elaboradosList" action="" method="get">
                                <table class="display" id="dataTableElab">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Image</th>
                                            <th>Name</th>
                                            <th>Packages</th>
                                            <th>Elaboration</th>
                                            <th>Expiration</th>
                                            <th>Place</th>
                                            <th>Cost</th>
                                            <th>Price</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Asumo que $link es un objeto PDO ya conectado con DBConnection::connectDB()

                                        $queryElab = "SELECT * FROM `almacenelaboraciones` WHERE `tipoProd` = 'Elaborado' ORDER BY `ID` DESC;";
                                        $stmtElab = $link->query($queryElab);
                                        $rowsElab = $stmtElab->fetchAll(PDO::FETCH_ASSOC);

                                        if ($rowsElab) {
                                            foreach ($rowsElab as $rowElab) {

                                                // Obtener la receta relacionada
                                                $queryReceta = "SELECT * FROM `recetas` WHERE `id` = :receta_id";
                                                $stmtReceta = $link->prepare($queryReceta);
                                                $stmtReceta->execute(['receta_id' => $rowElab["receta_id"]]);
                                                $rowReceta = $stmtReceta->fetch(PDO::FETCH_ASSOC);

                                                $imgReceta = $rowReceta ? $rowReceta["imagen"] : '';

                                                $fechaElab = new DateTime($rowElab["fechaElab"]);
                                                $fechaCad = new DateTime($rowElab["fechaCad"]);
                                                $fechaElabFormateada = $fechaElab->format('d/m/Y');
                                                $fechaCadFormateada = $fechaCad->format('d/m/Y');

                                                echo '<tr id="row-elab-' . htmlspecialchars($rowElab["ID"]) . '" style="background-color: #92D8B0;">';
                                                echo '<td>' . htmlspecialchars($rowElab["ID"]) . '</td>';
                                                echo '<td class="img-table"><img src="./.' . htmlspecialchars($imgReceta) . '" alt="Rec" title="Recipe"></td>';
                                                echo '<td>' . htmlspecialchars($rowElab["fName"]) . '</td>';

                                                $amount = '';
                                                switch ($rowElab["packaging"]) {
                                                    case 'Bag':
                                                        $amount = $rowElab["productamount"] . "(Bag)";
                                                        break;
                                                    case 'Pack':
                                                        $amount = $rowElab["productamount"] . "(Pack)";
                                                        break;
                                                    case 'Box':
                                                        $amount = $rowElab["productamount"] . "(Box)";
                                                        break;
                                                    case 'Bottle':
                                                        $amount = $rowElab["productamount"] . "(Bottle)";
                                                        break;
                                                    case 'Can':
                                                        $amount = $rowElab["productamount"] . "(Can)";
                                                        break;
                                                }
                                                echo '<td>' . htmlspecialchars($amount) . ' x ' . htmlspecialchars($rowElab["rations_package"]) . 'U</td>';

                                                echo '<td>' . $fechaElabFormateada . '</td>';
                                                echo '<td>' . $fechaCadFormateada . '</td>';
                                                echo '<td>' . htmlspecialchars($rowElab["warehouse"]) . '</td>';

                                                $cost = '';
                                                switch ($rowElab["costCurrency"]) {
                                                    case 'Euro':
                                                        $cost = $rowElab["costPrice"] . "&euro;";
                                                        break;
                                                    case 'Dirham':
                                                        $cost = $rowElab["costPrice"] . "&#x62F;&#x2E;&#x625;";
                                                        break;
                                                    case 'Yen':
                                                        $cost = $rowElab["costPrice"] . "&yen;";
                                                        break;
                                                    case 'Dolar':
                                                        $cost = $rowElab["costPrice"] . "&dollar;";
                                                        break;
                                                }
                                                echo '<td>' . htmlspecialchars($cost) . '</td>';

                                                $sale = '';
                                                switch ($rowElab["saleCurrency"]) {
                                                    case 'Euro':
                                                        $sale = $rowElab["salePrice"] . "&euro;";
                                                        break;
                                                    case 'Dirham':
                                                        $sale = $rowElab["salePrice"] . "&#x62F;&#x2E;&#x625;";
                                                        break;
                                                    case 'Yen':
                                                        $sale = $rowElab["salePrice"] . "&yen;";
                                                        break;
                                                    case 'Dolar':
                                                        $sale = $rowElab["salePrice"] . "&dollar;";
                                                        break;
                                                }
                                                echo '<td>' . htmlspecialchars($sale) . '</td>';

                                                echo "<td class='action_button'>
            <button class='btn-primary rounded' type='button' name='ing_elab' id='ing_elab' value='" . htmlspecialchars($rowElab["ID"]) . "'>
                <img src='./../../svg/ingredients.svg' alt='Ing' title='Ingredients'>
            </button>
            <button class='btn-primary rounded' type='submit' name='elab' id='tag_elab' value='" . htmlspecialchars($rowElab["ID"]) . "'>
                <img src='./../../svg/printing.svg' alt='Print' title='Print'>
            </button>
            <button class='btn-danger rounded' type='button' name='del_elab' id='del_elab' onclick='deleteElab(" . htmlspecialchars($rowElab["ID"]) . ")'>
                <img src='./../../svg/delete_button.svg' alt='Delete' title='Delete'>
            </button>
        </td>";

                                                echo '</tr>';
                                            }
                                        }
                                        ?>
                                    </tbody>

                                </table>
                            </form>
                        </div>
                    </div>
                </div>


                <!-- CONTROL PRE-PREPARED -->


                <div class="card shadow mb-4 ">
                    <div class="card-header">
                        <h6 class="m-0 font-weight-bold text-primary">Control pre-prepared</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <form name="elaboradosList" action="" method="get">
                                <table class="display" id="dataTablePre">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Image</th>
                                            <th>Name</th>
                                            <th>Packages</th>
                                            <th>Elaboration</th>
                                            <th>Expiration</th>
                                            <th>Place</th>
                                            <th>Cost</th>
                                            <th>Price</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $queryElab = "SELECT * FROM `almacenelaboraciones` WHERE `tipoProd` = 'Pre-Elaborado' ORDER BY `ID` DESC;";
                                        $stmtElab = $link->query($queryElab);
                                        $rowsElab = $stmtElab->fetchAll(PDO::FETCH_ASSOC);

                                        if ($rowsElab) {
                                            foreach ($rowsElab as $rowElab) {

                                                // Obtener la receta
                                                $queryReceta = "SELECT * FROM `recetas` WHERE `id` = :receta_id";
                                                $stmtReceta = $link->prepare($queryReceta);
                                                $stmtReceta->execute(['receta_id' => $rowElab["receta_id"]]);
                                                $rowReceta = $stmtReceta->fetch(PDO::FETCH_ASSOC);

                                                $imgReceta = $rowReceta ? $rowReceta["imagen"] : '';

                                                $fechaElab = new DateTime($rowElab["fechaElab"]);
                                                $fechaCad = new DateTime($rowElab["fechaCad"]);
                                                $fechaElabFormateada = $fechaElab->format('d/m/Y');
                                                $fechaCadFormateada = $fechaCad->format('d/m/Y');

                                                echo '<tr id="row-elab-' . htmlspecialchars($rowElab["ID"]) . '" style="background-color: #92D8B0;">';
                                                echo '<td>' . htmlspecialchars($rowElab["ID"]) . '</td>';
                                                echo '<td class="img-table"><img src="./.' . htmlspecialchars($imgReceta) . '" alt="Rec" title="Recipe"></td>';
                                                echo '<td>' . htmlspecialchars($rowElab["fName"]) . '</td>';

                                                $amount = '';
                                                switch ($rowElab["packaging"]) {
                                                    case 'Bag':
                                                        $amount = $rowElab["productamount"] . "(Bag)";
                                                        break;
                                                    case 'Pack':
                                                        $amount = $rowElab["productamount"] . "(Pack)";
                                                        break;
                                                    case 'Box':
                                                        $amount = $rowElab["productamount"] . "(Box)";
                                                        break;
                                                    case 'Bottle':
                                                        $amount = $rowElab["productamount"] . "(Bottle)";
                                                        break;
                                                    case 'Can':
                                                        $amount = $rowElab["productamount"] . "(Can)";
                                                        break;
                                                }
                                                echo '<td>' . htmlspecialchars($amount) . ' x ' . htmlspecialchars($rowElab["rations_package"]) . 'U</td>';

                                                echo '<td>' . $fechaElabFormateada . '</td>';
                                                echo '<td>' . $fechaCadFormateada . '</td>';
                                                echo '<td>' . htmlspecialchars($rowElab["warehouse"]) . '</td>';

                                                $cost = '';
                                                switch ($rowElab["costCurrency"]) {
                                                    case 'Euro':
                                                        $cost = $rowElab["costPrice"] . "&euro;";
                                                        break;
                                                    case 'Dirham':
                                                        $cost = $rowElab["costPrice"] . "&#x62F;&#x2E;&#x625;";
                                                        break;
                                                    case 'Yen':
                                                        $cost = $rowElab["costPrice"] . "&yen;";
                                                        break;
                                                    case 'Dolar':
                                                        $cost = $rowElab["costPrice"] . "&dollar;";
                                                        break;
                                                }
                                                echo '<td>' . htmlspecialchars($cost) . '</td>';

                                                $sale = '';
                                                switch ($rowElab["saleCurrency"]) {
                                                    case 'Euro':
                                                        $sale = $rowElab["salePrice"] . "&euro;";
                                                        break;
                                                    case 'Dirham':
                                                        $sale = $rowElab["salePrice"] . "&#x62F;&#x2E;&#x625;";
                                                        break;
                                                    case 'Yen':
                                                        $sale = $rowElab["salePrice"] . "&yen;";
                                                        break;
                                                    case 'Dolar':
                                                        $sale = $rowElab["salePrice"] . "&dollar;";
                                                        break;
                                                }
                                                echo '<td>' . htmlspecialchars($sale) . '</td>';

                                                echo "<td class='action_button'>
            <button class='btn-primary rounded' type='button' name='ing_elab' id='ing_elab' value='" . htmlspecialchars($rowElab["ID"]) . "'>
                <img src='./../../svg/ingredients.svg' alt='Ing' title='Ingredients'>
            </button>
            <button class='btn-primary rounded' type='submit' name='elab' id='tag_elab' value='" . htmlspecialchars($rowElab["ID"]) . "'>
                <img src='./../../svg/printing.svg' alt='Print' title='Print'>
            </button>
            <button class='btn-danger rounded' type='button' name='del_elab' id='del_elab' onclick='deleteElab(" . htmlspecialchars($rowElab["ID"]) . ")'>
                <img src='./../../svg/delete_button.svg' alt='Delete' title='Delete'>
            </button>
        </td>";

                                                echo '</tr>';
                                            }
                                        }
                                        ?>
                                    </tbody>

                                </table>
                            </form>
                        </div>
                    </div>
                </div>


                <!-- CONTROL INGREDIENTS -->


                <div class="card shadow mb-4 ">
                    <div class="card-header ">
                        <h6 class="m-0 font-weight-bold text-primary">Control ingredients</h6>
                    </div>
                    <div class="card-body ">
                        <div class="table-responsive">
                            <form name="ingredientsList" action="" method="get">
                                <table class="display" id="dataTableElabIng">
                                    <thead>


                                        <tr>
                                            <th>ID</th>
                                            <th>Image</th>
                                            <th>Name</th>
                                            <th>Packages</th>
                                            <th>Elaboration</th>
                                            <th>Expiration</th>
                                            <th>Place</th>
                                            <th>Cost</th>
                                            <th>Price</th>
                                            <th>Actions</th>
                                        </tr>

                                    </thead>

                                    <tbody>
                                        <?php
                                        $queryIng = "SELECT * FROM `almaceningredientes` ORDER BY `ID` DESC;";
                                        $stmtIng = $link->query($queryIng);
                                        $rowsIng = $stmtIng->fetchAll(PDO::FETCH_ASSOC);

                                        if ($rowsIng) {
                                            foreach ($rowsIng as $rowIng) {
                                                // Obtener info del ingrediente
                                                $query = "SELECT * FROM `ingredients` WHERE `id` = :ingrediente_id";
                                                $stmt = $link->prepare($query);
                                                $stmt->execute(['ingrediente_id' => $rowIng["ingrediente_id"]]);
                                                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                                                $unidad = $row ? $row["unidad"] : '';
                                                $imgIng = $row ? $row["image"] : '';

                                                $fechaElab = new DateTime($rowIng["fechaElab"]);
                                                $fechaCad = new DateTime($rowIng["fechaCad"]);
                                                $fechaElabFormateada = $fechaElab->format('d/m/Y');
                                                $fechaCadFormateada = $fechaCad->format('d/m/Y');

                                                echo '<tr id="row-ing-' . htmlspecialchars($rowIng["ID"]) . '" style="background-color: #92D8B0;">';
                                                echo '<td>' . htmlspecialchars($rowIng['ID']) . '</td>';
                                                echo '<td class="img-table"><img src="./.' . htmlspecialchars($imgIng) . '" alt="Ing" title="Ingredient"></td>';
                                                echo '<td>' . htmlspecialchars($rowIng["fName"]) . '</td>';

                                                $amount = '';
                                                switch ($rowIng["packaging"]) {
                                                    case 'Bag':
                                                        $amount = $rowIng["productamount"] . "(Bag)";
                                                        break;
                                                    case 'Pack':
                                                        $amount = $rowIng["productamount"] . "(Pack)";
                                                        break;
                                                    case 'Box':
                                                        $amount = $rowIng["productamount"] . "(Box)";
                                                        break;
                                                    case 'Bottle':
                                                        $amount = $rowIng["productamount"] . "(Bottle)";
                                                        break;
                                                    case 'Can':
                                                        $amount = $rowIng["productamount"] . "(Can)";
                                                        break;
                                                }

                                                if (strpos($unidad, 'Und') === 0) {
                                                    $cantidad = $rowIng["cantidad_paquete"] . " U";
                                                } else {
                                                    switch ($unidad) {
                                                        case 'Kg':
                                                            $cantidad = $rowIng["cantidad_paquete"] . " Kg";
                                                            break;
                                                        case 'L':
                                                            $cantidad = $rowIng["cantidad_paquete"] . " L";
                                                            break;
                                                        default:
                                                            $cantidad = "sin unidad";
                                                            break;
                                                    }
                                                }
                                                echo '<td>' . htmlspecialchars($amount) . ' x ' . htmlspecialchars($cantidad) . '</td>';
                                                echo '<td>' . $fechaElabFormateada . '</td>';
                                                echo '<td>' . $fechaCadFormateada . '</td>';
                                                echo '<td>' . htmlspecialchars($rowIng["warehouse"]) . '</td>';

                                                $cost = '';
                                                switch ($rowIng["costCurrency"]) {
                                                    case 'Euro':
                                                        $cost = $rowIng["costPrice"] . "&euro;";
                                                        break;
                                                    case 'Dirham':
                                                        $cost = $rowIng["costPrice"] . "&#x62F;&#x2E;&#x625;";
                                                        break;
                                                    case 'Yen':
                                                        $cost = $rowIng["costPrice"] . "&yen;";
                                                        break;
                                                    case 'Dolar':
                                                        $cost = $rowIng["costPrice"] . "&dollar;";
                                                        break;
                                                }
                                                echo '<td>' . htmlspecialchars($cost) . '</td>';

                                                $sale = '';
                                                switch ($rowIng["saleCurrency"]) {
                                                    case 'Euro':
                                                        $sale = $rowIng["salePrice"] . "&euro;";
                                                        break;
                                                    case 'Dirham':
                                                        $sale = $rowIng["salePrice"] . "&#x62F;&#x2E;&#x625;";
                                                        break;
                                                    case 'Yen':
                                                        $sale = $rowIng["salePrice"] . "&yen;";
                                                        break;
                                                    case 'Dolar':
                                                        $sale = $rowIng["salePrice"] . "&dollar;";
                                                        break;
                                                }
                                                echo '<td>' . htmlspecialchars($sale) . '</td>';

                                                echo "<td class='action_button'>
            <button class='btn-primary rounded' type='button' name='modal_ing' id='modal_ing' value='" . htmlspecialchars($rowIng["ID"]) . "'>
                <img src='./../../svg/ingredients.svg' alt='Ing' title='Ingredients'>
            </button>
            <button class='btn-primary rounded' type='submit' name='tag_ing' id='tag_ing' value='" . htmlspecialchars($rowIng["ID"]) . "'>
                <img src='./../../svg/printing.svg' alt='Print' title='Print'>
            </button>
            <button class='btn-danger rounded' type='button' name='del_ing' id='del_ing' onclick='deleteIng(" . htmlspecialchars($rowIng["ID"]) . ")'>
                <img src='./../../svg/delete_button.svg' alt='Delete' title='Delete'>
            </button>
        </td>";

                                                echo '</tr>';
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
        <!-- /.container-fluid -->
    </div>
    <!-- End of Main Content -->

    <!-- End of Content Wrapper -->
    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
    <!-- Footer -->
    <footer class="sticky-footer bg-white">

    </footer>
    <!-- End of Footer -->
    <!-- The Background Overlay -->
    <div id="backgroundOverlay"></div>

    <!-- The Modal -->
    <div id="ingredientsModal" class="modal-recipe">
        <!-- Modal content -->
        <div class="modal-content">


            <!-- Header Section -->
            <div class="modal-header">
                <div class="modal-name">
                    <h2 id="nombreTag"></h2>

                </div>
                <div class="modal-image">

                    <img id="imagenTag" class="recipe-image" alt="Imagen del producto">
                </div>
            </div>

            <!-- Body Section -->
            <div class="modal-body">
                <div class="modal-columns">
                    <!-- Left Column -->
                    <div class="modal-column-left recipe-details">
                        <p id="empaquetadoTag"></p>
                        <p id="almacenajeTag"></p>
                        <p id="elabTag"></p>
                        <p id="cadTag"></p>
                        <p id="costeTag"></p>
                        <p id="ventaTag"></p>
                    </div>
                    <!-- Right Column -->
                    <div class="modal-column-right recipe-details">
                        <!-- Ingredients Section -->
                        <h4 id="h3-ing">Ingredients</h4>
                        <div class="modal-ingredients" id="ingredientsList">

                            <!-- Ingredients will be dynamically inserted here -->
                        </div>
                        <h4 style="display: none;" id="h3-elaborados">Elaborated</h4>
                        <div class="modal-ingredients" id="elaboradosList" style="display: none;">
                            <!-- Elaborados will be dynamically inserted here -->
                        </div>
                    </div>





                    <!-- Print Button -->
                    <div>
                        <button id="btnPrint" class="btn-primary" onclick="printModal()">

                            Print <img src='./../../svg/printing.svg' alt='Ing' title='Ingredients' style="width: 25px; height:25px">
                        </button>
                    </div>

                </div>
            </div>



        </div>

    </div>

    <script>
        function printModal() {
            window.print();
        }

        function deleteItem(id, type) {
            console.log("id: " + id);
            console.log("type: " + type);

            let dataToSend = {
                id: id,
                type: type
            };

            $.ajax({
                url: './../../controllers/eliminarItem.php',
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

        function deleteElab(id) {
            if (window.confirm("¿Estás seguro de que quieres eliminarlo?")) {
                deleteItem(id, 'elab');
            }


        }

        function deleteIng(id) {

            if (window.confirm("¿Estás seguro de que quieres eliminarlo?")) {
                deleteItem(id, 'ing');
            }



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


    <script>
        $(document).ready(function() {
            // Get the modal
            var modal = document.getElementById("ingredientsModal");

            // Get the <span> element that closes the modal
            var span = document.getElementsByClassName("close")[0];
            var backgroundOverlay = document.getElementById("backgroundOverlay");

            // Delegate the event to a static parent, such as the table or document
            $(document).on('click', 'button[name="ing_elab"]', function(e) {
                e.preventDefault(); // Prevent form submission
                var elaboradoId = $(this).val();

                // Make an AJAX request to get the ingredients
                $.ajax({
                    url: './../../controllers/getAlmacenElaboracion.php',
                    type: 'GET',
                    data: {
                        id: elaboradoId
                    },
                    dataType: 'json',
                    success: function(data) {
                        console.log("Datos: ", data);
                        var elaboradosList = document.getElementById("elaboradosList");
                        var elaboradosH3 = document.getElementById("h3-elaborados");
                        var ingh3 = document.getElementById("h3-ing");
                        elaboradosList.style.display = "none"; // Ocultar la lista de elaborados
                        elaboradosH3.style.display = "none"; // Ocultar el título de elaborados
                        // Almacenado y receta
                        var almacenElaborado = data['almacenElaborado'];
                        var receta = data['receta'];
                        var ingredientes = data['ingredientes'];

                        // Manejo de raciones
                        var raciones = "";
                        if (receta['num_raciones'] > 1) {
                            raciones = " (" + receta['num_raciones'] + " rations)";
                        } else {
                            raciones = " (" + receta['num_raciones'] + " ration)";
                        }

                        // Nombre y raciones
                        var nombre = document.getElementById('nombreTag');
                        nombre.innerHTML = "<strong>" + receta['receta'] + "</strong> " + raciones;

                        // Imagen
                        var imagen = document.getElementById('imagenTag');
                        imagen.src = './../' + receta['imagen']; // Ajustando la ruta base según el JSON

                        // Ingredients list
                        var ingredientsList = document.getElementById("ingredientsList");
                        ingredientsList.innerHTML = ""; // Limpiamos para evitar duplicados
                        if (Array.isArray(ingredientes) && ingredientes.length > 0) {
                            ingh3.style.display = "block"; // Mostrar el título de ingredientes


                            ingredientes.forEach(ingrediente => {
                                const ingredientItem = document.createElement('p');
                                ingredientItem.innerHTML = `<strong>${ingrediente['nombre']}</strong> ${ingrediente['cantidad']} ${ingrediente['unidad']}`;
                                ingredientsList.appendChild(ingredientItem);
                            });
                        } else {
                            console.log("No hay ingredientes disponibles.");
                        }



                        elaboradosList.innerHTML = ""; // Vaciar contenido previo
                        if (Array.isArray(data.elaborados) && data.elaborados.length > 0) {
                            elaboradosList.style.display = "block"; // Mostrar la lista de elaborados
                            elaboradosH3.style.display = "block"; // Mostrar el título de elaborados

                            data.elaborados.forEach(elaborado => {
                                var p = document.createElement('p');
                                p.innerHTML = `<strong>${elaborado.nombre}</strong> ${elaborado.cantidad} und`;
                                elaboradosList.appendChild(p);
                            });
                        } else {
                            console.log("No hay elaborados disponibles.");
                        }


                        // Packaging (empaquetado)
                        var empaquetado = document.getElementById('empaquetadoTag');
                        empaquetado.innerHTML = "<strong>Packaging: </strong>" + almacenElaborado['empaquetado'];

                        // Storage (almacenaje)
                        var almacenaje = document.getElementById('almacenajeTag');
                        almacenaje.innerHTML = "<strong>Storage: </strong>" + almacenElaborado['almacen'];

                        // Production date


                        var production = new Date(almacenElaborado['fecha']);
                        var productionDate = production.getDate() + "/" + (production.getMonth() + 1) + "/" + production.getFullYear();
                        var elab = document.getElementById('elabTag');
                        elab.innerHTML = "<strong>Production date: </strong>" + productionDate;

                        // Expiration date
                        var expiration = new Date(almacenElaborado['caducidad']);
                        var expirationDate = expiration.getDate() + "/" + (expiration.getMonth() + 1) + "/" + expiration.getFullYear();
                        var cad = document.getElementById('cadTag');
                        cad.innerHTML = "<strong>Expiration date: </strong>" + expirationDate;

                        // Cost
                        var coste = document.getElementById('costeTag');
                        coste.innerHTML = "<strong>Cost: </strong>" + almacenElaborado['costo'] + " " + almacenElaborado['moneda_costo'];

                        // Sale price
                        var venta = document.getElementById('ventaTag');
                        venta.innerHTML = "<strong>Sale: </strong>" + almacenElaborado['precio_venta'] + " " + almacenElaborado['moneda_venta'];

                        // Show modal and background overlay
                        modal.style.display = "block";
                        backgroundOverlay.style.display = "block";
                    }
                });
            });

            $(document).on('click', 'button[name="modal_ing"]', function(e) {
                e.preventDefault(); // Prevent form submission
                var ingredientId = $(this).val();
console.log("Id: "+ingredientId);
                // Make an AJAX request to get the ingredient details
                $.ajax({
                    url: './../../controllers/get_ingredients.php',
                    type: 'GET',
                    data: {
                        idIng: ingredientId
                    },
                    success: function(data) {
                        console.log(data);
                        var nombre = document.getElementById('nombreTag');
                        nombre.innerHTML = "<strong>" + data['nombre'] + "</strong> " + data['cantidad'];
                        var imagen = document.getElementById('imagenTag');
                        imagen.src = './../' + data['imagen'];
                        var ingredientsList = document.getElementById("ingredientsList");
                        var elaboradosList = document.getElementById("elaboradosList");
                        var elaboradosH3 = document.getElementById("h3-elaborados");
                        var ingh3 = document.getElementById("h3-ing");
                        var empaquetado = document.getElementById('empaquetadoTag');
                        empaquetado.innerHTML = "<strong>Packaging: </strong>" + data['empaquetado'];

                        var almacenaje = document.getElementById('almacenajeTag');
                        almacenaje.innerHTML = "<strong>Storage: </strong>" + data['almacenaje'];

                        var fechaElab = new Date(data['fechaElab']);
                        var elab = document.getElementById('elabTag');
                        if (isNaN(fechaElab)) {
                            elab.innerHTML = "<strong>Fecha de elaboración: </strong>Fecha inválida";
                        } else {
                            var fechaElabFormateada = String(fechaElab.getDate()).padStart(2, '0') + "/" +
                                String(fechaElab.getMonth() + 1).padStart(2, '0') + "/" +
                                fechaElab.getFullYear();
                            elab.innerHTML = "<strong>Fecha de elaboración: </strong>" + fechaElabFormateada;
                        }

                        var fechaCad = new Date(data['fechaCad']);
                        var cad = document.getElementById('cadTag');
                        if (isNaN(fechaCad)) {
                            cad.innerHTML = "<strong>Fecha de caducidad: </strong>Fecha inválida";
                        } else {
                            var fechaCadFormateada = String(fechaCad.getDate()).padStart(2, '0') + "/" +
                                String(fechaCad.getMonth() + 1).padStart(2, '0') + "/" +
                                fechaCad.getFullYear();
                            cad.innerHTML = "<strong>Fecha de caducidad: </strong>" + fechaCadFormateada;
                        }

                        var coste = document.getElementById('costeTag');
                        coste.innerHTML = "<strong>Cost: </strong>" + data['coste'];
                        var venta = document.getElementById('ventaTag');
                        venta.innerHTML = "<strong>Sale: </strong>" + data['venta'];

                        ingredientsList.style.display = "none";
                        elaboradosList.style.display = "none";
                        elaboradosH3.style.display = "none";
                        ingh3.style.display = "none";
                        backgroundOverlay.style.display = "block";
                        modal.style.display = "block";
                    }
                });
            });

            // When the user clicks anywhere outside of the modal, close it
            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                    backgroundOverlay.style.display = "none";

                    var ingredientsList = document.getElementById("ingredientsList");
                    var paragraph = ingredientsList.querySelectorAll('p');
                    paragraph.forEach(element => {
                        element.remove();
                    });
                    ingredientsList.style.display = "flex";
                }
            };
        });
    </script>


</body>

</html>