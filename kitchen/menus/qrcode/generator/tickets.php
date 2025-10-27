<?php

require __DIR__ . '/../../models/tagsIngredientes.php';
require_once __DIR__ . '/../../models/tagsIngredientesDao.php';

require __DIR__ . '/../../models/tagsElaboraciones.php';
require_once __DIR__ . '/../../models/tagsElaboracionesDao.php';

require __DIR__ . '/../../models/tagsPreelaboraciones.php';
require_once __DIR__ . '/../../models/tagsPreelaboracionesDao.php';

require __DIR__ . '/../../models/user.php';
require_once __DIR__ . '/../../models/userDao.php';

require __DIR__ . '/../../models/grupos.php';
require_once __DIR__ . '/../../models/gruposDao.php';

require __DIR__ . '/../../models/permisos.php';
require __DIR__ . '/../../models/permisosDao.php';

require __DIR__ . '/../../models/gruposPermisos.php';
require __DIR__ . '/../../models/gruposPermisosDao.php';


$userses = new User();
$grupo = new Grupo();
session_start();

if (isset($_SESSION['user_id'])) {

    $id = $_SESSION['user_id'];
    $userSession = UserDao::select($_SESSION['user_id']);
    $grupoSession = GruposDao::select($userSession->getGrupo_id());
} else {
    //No authenticated user
    header('Location: ./../../login/login.php');
}

if (
    $_SERVER["REQUEST_METHOD"] == "GET" && (isset($_GET["ing_elab"]))
) {
    $id = $_GET["ing_elab"];

    // Codificar el ID en la URL de redireccionamiento
    $redirect_url = "listIngr.php?ing_elab=" . urlencode($id);

    // Redirigir con el ID en la URL
    header("Location: $redirect_url");
    exit();
} elseif (
    $_SERVER["REQUEST_METHOD"] == "GET" && (isset($_GET["edit_elab"]))
) {

    $id = $_GET["edit_elab"];

    // Codificar el ID en la URL de redireccionamiento
    $redirect_url = "./elab.php?id=" . urlencode($id);

    // Redirigir con el ID en la URL
    header("Location: $redirect_url");
    exit();
} elseif (
    $_SERVER["REQUEST_METHOD"] == "GET" && (isset($_GET["del_elab"]))
) {

    $id = $_GET["del_elab"];
    $listIngredientes = TagsElaboracionesDao::getAll();

    foreach ($listIngredientes as $ingrediente) {

        if ($id == $ingrediente->getIDTag()) {

            TagsElaboracionesDao::delete($ingrediente);

            header("Location: tickets.php");
            exit();
        }
    }
} elseif (
    $_SERVER["REQUEST_METHOD"] == "GET" && (isset($_GET["print_elab"]))
) {

    $id = $_GET["print_elab"];

    $redirect_url = "./printQr.php?print_elab=" . urlencode($id);

    // Redirigir con el ID en la URL
    header("Location: $redirect_url");

    exit();
} elseif (
    $_SERVER["REQUEST_METHOD"] == "GET" && (isset($_GET["edit_ing"]))
) {

    $id = $_GET["edit_ing"];

    // Codificar el ID en la URL de redireccionamiento
    $redirect_url = "./ingredient.php?id=" . urlencode($id);

    // Redirigir con el ID en la URL
    header("Location: $redirect_url");
    exit();
} elseif (
    $_SERVER["REQUEST_METHOD"] == "GET" && (isset($_GET["del_ing"]))
) {

    $id = $_GET["del_ing"];

    $listIngredientes = TagsIngredientesDao::getAll();

    foreach ($listIngredientes as $ingrediente) {

        if ($id == $ingrediente->getIDTag()) {

            TagsIngredientesDao::delete($ingrediente);

            header("Location: tickets.php");
            exit();
        }
    }
} elseif (
    $_SERVER["REQUEST_METHOD"] == "GET" && (isset($_GET["print_ing"]))
) {

    $id = $_GET["print_ing"];

    $redirect_url = "./printQr.php?print_ing=" . urlencode($id);

    // Redirigir con el ID en la URL
    header("Location: $redirect_url");

    exit();
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

    <title>Qr</title>
    <script src="https://kit.fontawesome.com/2e2cc992ca.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="./../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

    <!-- Custom styles for this template-->
    <link href="./../../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="./../../css/navs.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href=" https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css" rel="stylesheet" type="text/css">
    <link href="./../../css/qr/tickets.css" rel="stylesheet" type="text/css">
</head>

<body id="page-top">
    <?php
    $imgProfile = "./../../img/undraw_profile.svg";
    $pathDashboard = "./../../dashboard";
    $pathLogo = "./../../img/ccsLogoWhite.png";
    $pathLogout = "./../../login/logout.php";

    $menu_options = [
        'dashboard' => ['url' => './../../dashboard', 'icon' => './../../svg/dashboard.svg', 'text' => 'Dashboard'],
        'users' => ['url' => './../../users/userList.php', 'icon' => './../../svg/user.svg', 'text' => 'User'],
        'qr' => ['url' => './../../qrcode/generator/tickets.php', 'icon' => './../../svg/qr_code.svg', 'text' => 'QR'],
        'ecommerce' => ['url' => 'http://localhost:8080/ecommerce', 'icon' => './../../svg/tpv.svg', 'text' => 'E-commerce'],
        'restaurant' => ['url' => 'http://localhost:8080/restaurant/public/restaurants.html', 'icon' => './../../svg/restaurant.svg', 'text' => 'Restaurant'],
        'elaborations' => ['url' => './../../elaborations/elabList.php', 'icon' => './../../svg/recipes.svg', 'text' => 'Elaborations'],
        'stock' => ['url' => './../../stock/preelaborationStock.php', 'icon' => './../../svg/stock.svg', 'text' => 'Stock'],
        'suppliers' => ['url' => './../../suppliers/suppliersList.php', 'icon' => './../../svg/orders.svg', 'text' => 'Suppliers'],
        'economic' => ['url' => '#', 'icon' => './../../svg/graph.svg', 'text' => 'Economic'],
    ];

    include './../../templates/navs.php';
    ?>
    <?php insertarTopNav('elab.php', './../../svg/qr.svg', 'Create Qr'); ?>
    <?php insertarTopNav('printQr.php', './../../svg/qr_code_print.svg', 'Print Qr'); ?>


    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
        <!-- Main Content -->
        <div id="content">
            <!-- End of Topbar -->
            <!-- ************************************************************************************************************************ -->

            <!-- Begin Page Content -->
            <div class="container-fluid">
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

                <!-- Page Heading -->
                <h1 class="h3 mb-2 text-gray-800">Tickets</h1>
                <p class="mb-4">Generated tickets</p>

                <!-- DataTales Example -->
                <div class="card shadow mb-4">
                    <div class="card-header">
                        <h6 class="m-0 font-weight-bold text-primary">Ticket control elaboration</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <form name="elaboradosList" action="tickets.php" enctype="multipart/form-data" method="get">
                                <table class="display" id="tableQrElaboration">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Packages</th>
                                            <th>Rations</th>
                                            <th>Elaboration</th>
                                            <th>Expiration</th>
                                            <th>Expires(days)</th>
                                            <th>Place</th>
                                            <th>Cost</th>
                                            <th>Price</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php

                                        $queryElab = "SELECT * FROM `tagselaboraciones` ORDER BY `IDTag` DESC;";
                                        $resultElab = $link->query($queryElab);

                                        if ($resultElab->num_rows > 0) {
                                            while ($rowElab = $resultElab->fetch_assoc()) {

                                                $fechaInicial = new DateTime();
                                                $fechaElab = new DateTime($rowElab["fechaElab"]);
                                                $fechaFinal = new DateTime($rowElab["fechaCad"]);
                                                $fechaFinalCalc = new DateTime($rowElab["fechaCad"]);
                                                $fechaFinalCalc->modify('+1 day');
                                                $intervalo = $fechaInicial->diff($fechaFinalCalc);
                                               
                                                $diferenciaDias = $intervalo->format("%r%a");
                                                $fechaElab = $fechaElab->format('d-m-Y');
                                                $fechaFinal = $fechaFinal->format('d-m-Y');
                                              
                                                if ($diferenciaDias > 7) {
                                                    echo '<tr id="row-elab-' . $rowElab["IDTag"] . '" style="background-color: #92D8B0;">';
                                                } elseif ($diferenciaDias <= 0) {
                                                    echo '<tr id="row-elab-' . $rowElab["IDTag"] . '" style="background-color: #ff00002e;">';
                                                    $diferenciaDias = "Expired";
                                                } else {
                                                    echo '<tr id="row-elab-' . $rowElab["IDTag"] . '" style="background-color: #F7DC6F;">';
                                                }

                                                echo '<td>' . $rowElab["IDTag"] . '</td>';
                                                echo '<td>' . $rowElab["fName"] . '</td>';

                                                switch ($rowElab["packaging"]) {
                                                    case 'Bag':
                                                        $amount =  $rowElab["productamount"] . "(Bag)";
                                                        break;
                                                    case 'Pack':
                                                        $amount =  $rowElab["productamount"] . "(Pack)";
                                                        break;
                                                    case 'Box':
                                                        $amount =  $rowElab["productamount"] . "(Box)";
                                                        break;
                                                    case 'Bottle':
                                                        $amount =  $rowElab["productamount"] . "(Bottle)";
                                                        break;
                                                    case 'Can':
                                                        $amount =  $rowElab["productamount"] . "(Can)";
                                                        break;
                                                    default:
                                                        # code...
                                                        break;
                                                }
                                                echo '<td>' . $amount . '</td>';
                                                echo '<td>' . $rowElab["rations_package"] . '</td>';
                                                echo '<td>' . $fechaElab . '</td>';
                                                echo '<td>' .  $fechaFinal . '</td>';
                                                echo '<td>' . $diferenciaDias . '</td>';
                                                echo '<td>' . $rowElab["warehouse"] . '</td>';
                                                switch ($rowElab["costCurrency"]) {
                                                    case 'Euro':
                                                        $cost =  $rowElab["costPrice"] . "&euro;";
                                                        break;
                                                    case 'Dirham':
                                                        $cost =  $rowElab["costPrice"] . "&#x62F;&#x2E;&#x625;";
                                                        break;
                                                    case 'Yen':
                                                        $cost =  $rowElab["costPrice"] . "&yen;";
                                                        break;
                                                    case 'Dolar':
                                                        $cost =  $rowElab["costPrice"] . "&dollar;";
                                                        break;

                                                    default:
                                                        # code...
                                                        break;
                                                }
                                                echo '<td>' . $cost . '</td>';
                                                switch ($rowElab["saleCurrency"]) {
                                                    case 'Euro':
                                                        $sale =  $rowElab["salePrice"] . "&euro;";
                                                        break;
                                                    case 'Dirham':
                                                        $sale =  $rowElab["salePrice"] . "&#x62F;&#x2E;&#x625;";
                                                        break;
                                                    case 'Yen':
                                                        $sale =  $rowElab["salePrice"] . "&yen;";
                                                        break;
                                                    case 'Dolar':
                                                        $sale =  $rowElab["salePrice"] . "&dollar;";
                                                        break;

                                                    default:
                                                        # code...
                                                        break;
                                                }
                                                echo '<td>' . $sale . '</td>';

                                                echo "<td class='action_button'>

                                                    <button class='btn-primary rounded' type='button' name='ing_elab' id='ing_elab' value='" .  $rowElab["IDTag"] . "'>
                                                    <img src='./../../svg/ingredients.svg' alt='Ingredients' title='Ingredients'>
                                                    </button>
                                                   
                                                    <button class='btn-primary rounded' type='submit' name='print_elab' id='print_elab' value='" .  $rowElab["IDTag"] . "'>
                                                    <img src='./../../svg/printing.svg' alt='Print' title='Tag'>     
                                                    </button>          
                                                    
                                                    <button class='btn-primary rounded' type='submit' name='edit_elab' id='edit_elab' value='" .  $rowElab["IDTag"] . "'>
                                                    <img src='./../../svg/edit.svg' alt='Edit' title='Edit'>
                                                    </button>
                                                                                                       
                                                    <button class='btn-danger rounded' type='button'  name='del_elab' id='del_elab' onclick='deleteElab(" . $rowElab["IDTag"] . ")'>
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

                <div class="card shadow mb-4">
                    <div class="card-header">
                        <h6 class="m-0 font-weight-bold text-primary">Ticket control ingredients</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <form name="ingredientsList" action="tickets.php" enctype="multipart/form-data" method="get">
                                <table class="display" id="tableQrIngredient">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Packages</th>
                                            <th>Amount</th>
                                            <th>Elaboration</th>
                                            <th>Expiration</th>
                                            <th>Expires(days)</th>
                                            <th>Place</th>
                                            <th>Cost</th>
                                            <th>Price</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php

                                        $queryIng = "SELECT * FROM `tagsingredientes` ORDER BY `IDTag` DESC;";
                                        $resultIng = $link->query($queryIng);


                                        if ($resultIng->num_rows > 0) {
                                            while ($rowIng = $resultIng->fetch_assoc()) {


                                                $query = "SELECT * FROM `ingredients` WHERE `id` = " . $rowIng["ingrediente_id"];
                                                $result = $link->query($query);
                                                if ($result->num_rows > 0) {
                                                    while ($row = $result->fetch_assoc()) {

                                                        $unidad = $row["unidad"];
                                                    }
                                                }



                                                $fechaInicial = new DateTime();
                                                $fechaElab = new DateTime($rowIng["fechaElab"]);
                                                $fechaFinal = new DateTime($rowIng["fechaCad"]);
                                                $fechaFinalCalc = new DateTime($rowIng["fechaCad"]);
                                                $fechaFinalCalc->modify('+1 day');
                                                // Restar la fecha final a la fecha inicial
                                                $intervalo = $fechaInicial->diff($fechaFinalCalc);
                                              
                                                // Obtener la diferencia en días
                                                $diferenciaDias = $intervalo->format("%r%a");
                                                $fechaElab = $fechaElab->format('d-m-Y');
                                                $fechaFinal = $fechaFinal->format('d-m-Y');


                                                if ($diferenciaDias > 7) {
                                                    echo '<tr id="row-ing-' . $rowIng["IDTag"] . '" style="background-color: #92D8B0;">';
                                                } elseif ($diferenciaDias <= 0) {
                                                    echo '<tr id="row-ing-' . $rowIng["IDTag"] . '" style="background-color: #ff00002e;">';
                                                    $diferenciaDias = "Expired";
                                                } else {
                                                    echo '<tr id="row-ing-' . $rowIng["IDTag"] . '" style="background-color: #F7DC6F;">';
                                                }

                                                echo '<td>' . $rowIng["IDTag"] . '</td>';
                                                echo '<td>' . $rowIng["fName"] . '</td>';

                                                switch ($rowIng["packaging"]) {
                                                    case 'Bag':
                                                        $amount =  $rowIng["productamount"] . "(Bag)";
                                                        break;
                                                    case 'Pack':
                                                        $amount =  $rowIng["productamount"] . "(Pack)";
                                                        break;
                                                    case 'Box':
                                                        $amount =  $rowIng["productamount"] . "(Box)";
                                                        break;
                                                    case 'Bottle':
                                                        $amount =  $rowIng["productamount"] . "(Bottle)";
                                                        break;
                                                    case 'Can':
                                                        $amount =  $rowIng["productamount"] . "(Can)";
                                                        break;
                                                    default:
                                                        # code...
                                                        break;
                                                }
                                                echo '<td>' . $amount . '</td>';

                                                switch ($unidad) {
                                                    case 'kg':
                                                        $cantidad =  $rowIng["cantidad_paquete"] . "kg";
                                                        break;
                                                    case 'und':
                                                        $cantidad =  $rowIng["cantidad_paquete"] . "und.";
                                                        break;
                                                    case 'L':
                                                        $cantidad =  $rowIng["cantidad_paquete"] . "L";
                                                        break;


                                                    default:
                                                        # code...
                                                        break;
                                                }

                                                echo '<td> ' . $cantidad . '</td>';
                                                echo '<td>' . $fechaElab . '</td>';
                                                echo '<td>' . $fechaFinal . '</td>';
                                                echo '<td>' . $diferenciaDias . '</td>';
                                                echo '<td>' . $rowIng["warehouse"] . '</td>';
                                                switch ($rowIng["costCurrency"]) {
                                                    case 'Euro':
                                                        $cost =  $rowIng["costPrice"] . "&euro;";
                                                        break;
                                                    case 'Dirham':
                                                        $cost =  $rowIng["costPrice"] . "&#x62F;&#x2E;&#x625;";
                                                        break;
                                                    case 'Yen':
                                                        $cost =  $rowIng["costPrice"] . "&yen;";
                                                        break;
                                                    case 'Dolar':
                                                        $cost =  $rowIng["costPrice"] . "&dollar;";
                                                        break;

                                                    default:
                                                        # code...
                                                        break;
                                                }
                                                echo '<td>' . $cost . '</td>';
                                                switch ($rowIng["saleCurrency"]) {
                                                    case 'Euro':
                                                        $sale =  $rowIng["salePrice"] . "&euro;";
                                                        break;
                                                    case 'Dirham':
                                                        $sale =  $rowIng["salePrice"] . "&#x62F;&#x2E;&#x625;";
                                                        break;
                                                    case 'Yen':
                                                        $sale =  $rowIng["salePrice"] . "&yen;";
                                                        break;
                                                    case 'Dolar':
                                                        $sale =  $rowIng["salePrice"] . "&dollar;";
                                                        break;

                                                    default:
                                                        # code...
                                                        break;
                                                }
                                                echo '<td>' . $sale . '</td>';

                                                echo "<td class='action_button'>
                                                    <button class='btn-primary rounded' type='button' name='modal_ing' id='modal_ing' value='" .  $rowIng["IDTag"] . "'>
                                                    <img src='./../../svg/ingredients.svg' alt='Ingredients' title='Ingredients'>
                                                    </button>
                                                   
                                                    <button class='btn-primary rounded' type='submit' name='print_ing' id='print_elab' value='" .  $rowIng["IDTag"] . "'>
                                                    <img src='./../../svg/printing.svg' alt='Print' title='Tag'>
                                                    </button>
                                                   
                                                    <button class='btn-primary rounded' type='submit' name='edit_ing' id='edit_ing' value='" .  $rowIng["IDTag"] . "'>
                                                    <img src='./../../svg/edit.svg' alt='Edit' title='Edit'>
                                                    </button>
                                                       
                                                    <button class='btn-danger rounded' type='button'  name='del_ing' id='del_ing' onclick='deleteIng(" . $rowIng["IDTag"] . ")'>
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

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>



    <!-- The Modal -->
    <div id="ingredientsModal" class="modal">
        <!-- Modal content -->
        <div class="modal-content">
            <div class="marco"></div>
          
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
            <div class="modal-ingredients" id="ingredientsList">
                <h3>Ingredients</h3>
              
                <!-- Ingredients will be dynamically inserted here -->
            </div>
        </div>
    </div>


    <script>
        function deleteItem(id, type) {
            console.log("id: " + id);
            console.log("type: " + type);

            let dataToSend = {
                id: id,
                type: type
            };

            $.ajax({
                url: 'eliminarItem.php',
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

            // When the user clicks the button, open the modal 
            $('button[name="ing_elab"]').click(function(e) {
                e.preventDefault(); // Prevent form submission
                var elaboradoId = $(this).val();

                // Make an AJAX request to get the ingredients
                $.ajax({
                    url: 'get_ingredients.php',
                    type: 'GET',
                    data: {
                        id: elaboradoId
                    },
                    success: function(data) {
                        var raciones = "";
                        if (data['raciones'] > 1) {
                            raciones = " (" + data['raciones'] + " raciones)";
                        } else {
                            raciones = " (" + data['raciones'] + " racion)";
                        }
                        var nombre = document.getElementById('nombreQr');
                        nombre.innerHTML = data['nombre'] + raciones;
                        var imagen = document.getElementById('imagenQr');
                        imagen.src = './../' + data['imagen'];
                        var ingredientsList = document.getElementById("ingredientsList");

                        var empaquetado = document.getElementById('empaquetadoQr');
                        empaquetado.innerHTML = "Empaquetado: " + data['empaquetado'];
                        
                        var almacenaje = document.getElementById('almacenajeQr');
                        almacenaje.innerHTML = "Almacenaje: " + data['almacenaje'];
                        var elab = document.getElementById('elabQr');
                        elab.innerHTML = "Fecha de elaboracion: " + data['fechaElab'];
                        var cad = document.getElementById('cadQr');
                        cad.innerHTML = "Fecha de caducidad: " + data['fechaCad'];
                        var coste = document.getElementById('costeQr');
                        coste.innerHTML = "Coste: " + data['coste'];
                        var venta = document.getElementById('ventaQr');
                        venta.innerHTML = "Venta: " + data['venta'];







                        data['ingredientes'].forEach(ingrediente => {
                            const ingredientItem = document.createElement('p');
                            ingredientItem.textContent = ingrediente['nombre'] + " " + ingrediente['cantidad'] + " " + ingrediente['unidad'];
                            ingredientsList.appendChild(ingredientItem);
                        });



                        modal.style.display = "block";
                    }
                });
            });

            // When the user clicks the button, open the modal 
            $('button[name="modal_ing"]').click(function(e) {
                e.preventDefault(); // Prevent form submission
                var ingredientId = $(this).val();

                // Make an AJAX request to get the ingredients
                $.ajax({
                    url: 'get_ingredients.php',
                    type: 'GET',
                    data: {
                        idIng: ingredientId
                    },
                    success: function(data) {
                       
                        var nombre = document.getElementById('nombreQr');
                        nombre.innerHTML = data['nombre'] +" "+ data['cantidad'];
                        var imagen = document.getElementById('imagenQr');
                        imagen.src = './../' + data['imagen'];
                        var ingredientsList = document.getElementById("ingredientsList");

                        var empaquetado = document.getElementById('empaquetadoQr');
                        empaquetado.innerHTML = "Empaquetado: " + data['empaquetado'];
                        
                        var almacenaje = document.getElementById('almacenajeQr');
                        almacenaje.innerHTML = "Almacenaje: " + data['almacenaje'];
                        var elab = document.getElementById('elabQr');
                        elab.innerHTML = "Fecha de elaboracion: " + data['fechaElab'];
                        var cad = document.getElementById('cadQr');
                        cad.innerHTML = "Fecha de caducidad: " + data['fechaCad'];
                        var coste = document.getElementById('costeQr');
                        coste.innerHTML = "Coste: " + data['coste'];
                        var venta = document.getElementById('ventaQr');
                        venta.innerHTML = "Venta: " + data['venta'];







                     ingredientsList.style.display = "none";



                        modal.style.display = "block";
                    }
                });
            });

            // When the user clicks anywhere outside of the modal, close it
            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";


                    var ingredientsList = document.getElementById("ingredientsList");


                    var paragraph = ingredientsList.querySelectorAll('p');


                    paragraph.forEach(element => {
                        element.remove();

                    });
                    ingredientsList.style.display = "flex";
                }
            }
        });
    </script>
</body>

</html>