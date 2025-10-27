<?php
$f = "visit.php";
if (!file_exists($f)) {
    touch($f);
    $handle =  fopen($f, "w");
    fwrite($handle, 0);
    fclose($handle);
}

include('libs/phpqrcode/qrlib.php');
require __DIR__ . '/../../models/tagsElaboraciones.php';
require_once __DIR__ . '/../../models/tagsElaboracionesDao.php';
require __DIR__ . '/../../models/tagsIngredientes.php';
require_once __DIR__ . '/../../models/tagsIngredientesDao.php';
require __DIR__ . '/../../models/user.php';
require_once __DIR__ . '/../../models/userDao.php';

require __DIR__ . '/../../models/grupos.php';
require_once __DIR__ . '/../../models/gruposDao.php';

require __DIR__ . '/../../models/permisos.php';
require_once __DIR__ . '/../../models/permisosDao.php';
require __DIR__ . '/../../models/gruposPermisos.php';
require_once __DIR__ . '/../../models/gruposPermisosDao.php';

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





if (isset($_GET['print_elab'])) {

    $id = $_GET['print_elab'];
    $tag =  TagsElaboracionesDao::select($id);
    $codeContents = $tag->getCodeContents();
    $tempDir = $tag->getTempDir();
    $fileName = $tag->getFilename();
    QRcode::png($tag->getCodeContents(), $tag->getTempDir() . '' . $tag->getFilename() . '.png', QR_ECLEVEL_M, 3, 4);
} else if (isset($_GET['print_ing'])) {

    $id = $_GET['print_ing'];
    $tag =  TagsIngredientesDao::select($id);
    $codeContents = $tag->getCodeContents();
    $tempDir = $tag->getTempDir();
    $fileName = $tag->getFilename();
    QRcode::png($tag->getCodeContents(), $tag->getTempDir() . '' . $tag->getFilename() . '.png', QR_ECLEVEL_M, 3, 4);
}


?>

<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Register</title>

    <!-- Custom fonts for this template-->
    <!-- <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">-->
    <script src="https://kit.fontawesome.com/2e2cc992ca.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="./../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <!-- Custom styles for this template-->
  
    <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../../menu.css" rel="stylesheet">
    <link href="../../css/qr/printQr.css" rel="stylesheet">
    <link href="./../../css/navs.css" rel="stylesheet">
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
  

    <div id="wrapper">
      


        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

             

                <div class="container-fluid text-center d-flex flex-column align-items-center justify-content-center">

                    <?php

                    if (!isset($filename)) {
                        $filename = "author";
                    }
                    ?>




                    <div class="content-top">
                        <h1 style="text-align:center; color: black">QR Code</h1>
                        <button class="btn btn-primary rounded" style="width:45px; margin:5px 0;" onclick="imprimir()">
                            <img src="./../../svg/print.svg" alt="Print">
                        </button>

                        <a class="btn btn-primary rounded" style="width:45px; margin:5px 0;" href="download.php?file=<?php echo isset($tag) ?  $tag->getFilename() : ""; ?>.png ">
                            <img src="./../../svg/download.svg" alt="Download">
                        </a>
                        <hr>
                    </div>




                    <div class="qrframe " id="qr" style="border:2px solid black; width:305px; height:305px;">
                        <?php echo '<img src="temp/' . (isset($tag) ? $tag->getFilename() : "") . '.png" style="width:300px; height:300px;">'; ?>
                    </div>






                </div>
            </div>
        </div>
    </div>

    <script>
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