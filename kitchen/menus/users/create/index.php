<?php

require __DIR__ . '/../../../models/user.php';
require_once __DIR__ . '/../../../models/userDao.php';

require __DIR__ . '/../../../models/grupos.php';
require_once __DIR__ . '/../../../models/gruposDao.php';

require __DIR__ . '/../../../models/permisos.php';
require_once __DIR__ . '/../../../models/permisosDao.php';
require __DIR__ . '/../../../models/gruposPermisos.php';
require_once __DIR__ . '/../../../models/gruposPermisosDao.php';

$userses = new User();
$grupo = new Grupo();
session_start();

$permisoRoot = false;
$currentGroupId = 0;


if (isset($_SESSION['user_id'])) {

    $id = $_SESSION['user_id'];
    $userSession = UserDao::select($_SESSION['user_id']);
    $grupoSession = GruposDao::select($userSession->getGrupo_id());
    $currentGroupId = $userSession->getGrupo_id();
} else {
    //No authenticated user
    header('Location: ./../../../login/login.php');
}

$idPermisos = array();

$idPermisos = GrupoPermisoDao::getPermisosByGroupId($currentGroupId);

$nombresPermiso = array();

foreach ($idPermisos as $idPermiso) {

    $nombresPermiso[] = PermisoDao::getPermisoNombreById($idPermiso);
}


$redirect_url =  "/kitchen/dashboard";
foreach ($nombresPermiso as $nombrePermiso) {
    if ($nombrePermiso == "root") {
        $permisoRoot = true;
    }
}

if (!$permisoRoot) {
    header("Location: $redirect_url");
}

const BASE_URL = "http://localhost:8080/";
$grupos = GruposDao::getAll();

$id = 0;
$title = "Create User";
$btnText = "Register";

if (isset($_GET['id'])) {
    // Obtener el ID de la URL
    $id = $_GET['id'];
    $user = UserDao::select($id);
    $title = "Editar Usuario";
    $btnText = "Edit";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Cook code</title>
    <link rel="icon" type="image/png" href="./../../../img/logo.png">
    <script src="https://kit.fontawesome.com/2e2cc992ca.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="./../../../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="./../../../css/register.css" rel="stylesheet">
    <link href="./../../../css/navs.css" rel="stylesheet">
    <style>
        .text-right-input {
            text-align: right;
        }
    </style>
</head>

<body id="page-top">
    <?php 
       $imgProfile = "./../../../img/undraw_profile.svg";
       $pathDashboard = "./../../../dashboard";
       $pathLogo = "./../../../img/ccsLogoWhite.png";
       $pathLogout = "./../../../login/logout.php";
   
   
     
    $menu_options = [
        'dashboard' => ['url' => './../../dashboard', 'icon' => './../../../svg/dashboard.svg', 'text' => 'Dashboard'],
        'users' => ['url' => './../../users', 'icon' => './../../../svg/user.svg', 'text' => 'User'],
      //  'qr' => ['url' => './../../qrcode/generator/tickets.php', 'icon' => './../../../svg/qr_code.svg', 'text' => 'QR'],
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

    
    
    
    include './../../../includes/navs.php'; ?>

    <div id="content-wrapper" class="d-flex flex-column">
        <?php include 'register.php'; ?>
    </div>

    <script src="./../../../vendor/jquery/jquery.min.js"></script>
    <script src="./../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="./../../../vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="./../../../js/sb-admin-2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="./js/register.js"></script>
</body>

</html>