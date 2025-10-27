<?php
require __DIR__ . '/../../../models/elaboraciones.php';
require_once __DIR__ . '/../../../models/elaboracionesDao.php';
require __DIR__ . '/../../../models/ElaboracionIngredient.php';
require __DIR__ . '/../../../models/almacenElaboraciones.php';
require_once __DIR__ . '/../../../models/almacenElaboracionesDao.php';
require __DIR__ . '/../../../models/recetas.php';
require_once __DIR__ . '/../../../models/recetasDao.php';
require_once __DIR__ . '/../../../models/ElaboracionIngredientDao.php';
require __DIR__ . '/../../../models/unit.php';
require_once __DIR__ . '/../../../models/unitDao.php';
require __DIR__ . '/../../../models/alergenos.php';
require_once __DIR__ . '/../../../models/alergenosDao.php';
require __DIR__ . '/../../../models/ingredientes.php';
require_once __DIR__ . '/../../../models/ingredientesDao.php';
require __DIR__ . '/../../../models/almacenIngredientes.php';
require_once __DIR__ . '/../../../models/almacenIngredientesDao.php';

?>

<!DOCTYPE html>
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
    <link href="./css/form.css" rel="stylesheet">
    
    <script>
    // Función global para mostrar formularios
       function showForm(formId) {
            const forms = ['formElaborado', 'formIng', 'formPreelaborado'];
            forms.forEach(id => {
                const formElement = document.getElementById(id);
                if (formElement) formElement.style.display = 'none';
            });

            const selectedForm = document.getElementById(formId);
            if (selectedForm) selectedForm.style.display = 'block';
        }

   
    </script>
</head>

<body id="page-top">
    <?php include './../../../includes/session.php';

    $imgProfile = "./../../../img/undraw_profile.svg";
    $pathDashboard = "./../../../dashboard";
    $pathLogo = "./../../../img/ccsLogoWhite.png";
    $pathLogout = "./../../../login/logout.php";

    $menu_options = [
        'dashboard' => ['url' => './../../dashboard', 'icon' => './../../../svg/dashboard.svg', 'text' => 'Dashboard'],
        'users' => ['url' => './../../users', 'icon' => './../../../svg/user.svg', 'text' => 'User'],
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

    include './../../../includes/navs.php';
    insertarTopNav('./../', './../../../svg/orders_Black.svg', 'Tags list');
    ?>

    <div class="container-fluid text-center align-center">
        <!-- Botones con IDs específicos -->
       <div id="buttons">
        <button class="btn btn-primary" onclick="showForm('formElaborado')">Elaboration</button>
        <button class="btn btn-primary" onclick="showForm('formPreelaborado')">Pre-prepared</button>
        <button class="btn btn-primary" onclick="showForm('formIng')">Ingredient</button>
    </div>

        <!-- Formularios (inicialmente ocultos) -->
        <div>
            <div id="formElaborado" style="display:block;">
                <?php include 'formElaborado.php'; ?>
            </div>
            <div id="formPreelaborado" style="display:block;">
                <?php include 'formPreelaborado.php'; ?>
            </div>
            <div id="formIng" style="display:block;">
                <?php include 'formIngrediente.php'; ?>
            </div>
        </div>
    </div>

    <footer>
        <center><br></center>
    </footer>
  
    <!-- Bootstrap core JavaScript-->
    <script src="./../../../vendor/jquery/jquery.min.js"></script>
    <script src="./../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="./../../../vendor/jquery-easing/jquery.easing.min.js"></script>
    
    <!-- Custom scripts for all pages-->
    <script src="./../../../js/sb-admin-2.min.js"></script>
</body>
</html>