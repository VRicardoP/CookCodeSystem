<?php
require_once __DIR__ . '/../../../DBConnection.php';
require __DIR__ . '/../../../models/ingredientes.php';
require_once __DIR__ . '/../../../models/ingredientesDao.php';
require __DIR__ . '/../../../models/alergenos.php';
require_once __DIR__ . '/../../../models/alergenosDao.php';
require __DIR__ . '/../../../models/precioProducto.php';
require_once __DIR__ . '/../../../models/precioProductoDao.php';


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["modfProduct"])) {
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
} else if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["newProduct"])) {
    $obj = new PreciosProducto();
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
    <link rel="icon" type="image/png" href="./../../../img/logo.png">

    <!-- Custom fonts for this template-->
    <script src="https://kit.fontawesome.com/2e2cc992ca.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    
    <!-- Custom styles for this template-->
    <link href="./../../../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="./../../../css/navs.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">
    <link href="./css/style.css" rel="stylesheet">
   
    <script type="module" src="./js/formHandlers.js" defer></script>
</head>

<body id="page-top">
    <?php

    $link = DBConnection::connectDB();


    $imgProfile = "./../../../img/undraw_profile.svg";
    $pathDashboard = "./../../../dashboard";
    $pathLogo = "./../../../img/ccsLogoWhite.png";
    $pathLogout = "./../../../login/logout.php";
    $redirectionNoUser = './../../../login/login.php';


    $menu_options = [
        'dashboard' => ['url' => './../../dashboard', 'icon' => './../../../svg/dashboard.svg', 'text' => 'Dashboard'],
        'users' => ['url' => './../../users', 'icon' => './../../../svg/user.svg', 'text' => 'User'],
        //  'qr' => ['url' => './../../qrcode/generator/tickets.php', 'icon' => './../../../svg/qr_code.svg', 'text' => 'QR'],
        'ecommerce' => ['url' => 'http://localhost:8080/ecommerce', 'icon' => './../../../svg/tpv.svg', 'text' => 'E-commerce'],
        'restaurant' => ['url' => 'http://localhost:8080/restaurant/public/restaurants.html', 'icon' => './../../../svg/restaurant.svg', 'text' => 'Restaurant'],
        'elaborations' => ['url' => './../../elaborations', 'icon' => './../../../svg/recipes.svg', 'text' => 'Elaborations'],
        'Ing/Recipes' => ['url' => './../../ing_recetas', 'icon' => './../../../svg/recipe_white.svg', 'text' => 'Ing/Recipes'],
        'Plating' => ['url' => './../../plating', 'icon' => './../../../svg/plato_blanco.svg', 'text' => 'Plating up'],
        'Mise en Place' => ['url' => './../../miseEnPlace', 'icon' => './../../../svg/miseEnPlace.svg', 'text' => 'Mise en Place'],
        'stock' => ['url' => './../../stock', 'icon' => './../../../svg/stock.svg', 'text' => 'Order tracking'],
        'suppliers' => ['url' => './../../suppliers', 'icon' => './../../../svg/orders.svg', 'text' => 'Suppliers'],
        'economic' => ['url' => '#', 'icon' => './../../../svg/graph.svg', 'text' => 'Economic'],

    ];

    ?>

    <?php include './../../../includes/session.php'; ?>
    <?php include './../../../includes/navs.php'; ?>

    <?php insertarTopNav('./../create-rec', './../../../svg/orders_Black.svg', 'Create pre-prepared'); ?>
    <?php insertarTopNav('./../', './../../../svg/orders_Black.svg', 'List'); ?>

    <?php include 'formIng.php'; ?>

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>


    <!-- Bootstrap core JavaScript-->
    <script src="./../../../vendor/jquery/jquery.min.js"></script>
    <script src="./../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    
    <!-- Core plugin JavaScript-->
    <script src="./../../../vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="./../../../js/sb-admin-2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>



</html>