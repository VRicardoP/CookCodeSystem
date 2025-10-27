<?php

require __DIR__ . '/../../../models/recetas.php';
require_once __DIR__ . '/../../../models/recetasDao.php';

require __DIR__ . '/../../../models/ingredientes.php';
require_once __DIR__ . '/../../../models/ingredientesDao.php';



$allRecetas = RecetasDao::getAll();

$allIngredientes = IngredientesDao::getAll();

include_once './../../../controllers/save_dish.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cook code</title>
    <link rel="icon" type="image/png" href="./../../../img/logo.png">
    <!-- Custom fonts for this template-->
    <script src="https://kit.fontawesome.com/2e2cc992ca.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="./../../../css/sb-admin-2.min.css" rel="stylesheet">

    <link href="./../../../css/qr/tickets.css" rel="stylesheet">
    <link href="./../../../css/navs.css" rel="stylesheet">

    <link rel="stylesheet" href="./css/dishForm.css">
    <style>
        th {
            background-color: #007934;
            font-weight: bold;
            color: black;
        }
    </style>
</head>

<body>
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
        'stock' => ['url' => './../../stock', 'icon' => './../../../svg/stock.svg', 'text' => 'Stock'],
        'suppliers' => ['url' => './../../suppliers', 'icon' => './../../../svg/orders.svg', 'text' => 'Suppliers'],
        'economic' => ['url' => '#', 'icon' => './../../../svg/graph.svg', 'text' => 'Economic'],




    ];

    
    



    include './../../../includes/session.php';
    include './../../../includes/navs.php'; ?>
    <?php insertarTopNav('./../', './../../../svg/orders_Black.svg', 'Plating list'); ?>

    <main>

    <?php include 'platingForm.php'; ?>
       
    </main>
</body>

</html>

<script>
    function updateUnit(input) {
        // Obtener el valor del ingrediente seleccionado
        const selectedIngredient = input.value;

        // Obtener la lista de opciones del datalist
        const dataList = document.getElementById('list_ing').options;

        // Buscar la unidad del ingrediente seleccionado
        for (let option of dataList) {
            if (option.value === selectedIngredient) {
                const unidad = option.getAttribute('data-und');
                const unidadInput = input.closest('tr').querySelector('.unidad');
                unidadInput.value = unidad; // Actualiza el campo de unidad con la unidad correspondiente
                unidadInput.readOnly = true; // Establece el campo como solo lectura
                break; // Salir del bucle una vez encontrado
            }
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

    // Función para mostrar vista previa de la imagen
    document.getElementById('imagen').addEventListener('change', function(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('preview');

        if (file) {
            const reader = new FileReader();

            reader.onload = function(e) {
                preview.src = e.target.result; // Establecer la fuente de la imagen a la vista previa
                preview.style.display = 'block'; // Mostrar la imagen
            }

            reader.readAsDataURL(file); // Leer la imagen como Data URL
        } else {
            preview.src = ''; // Reiniciar la vista previa
            preview.style.display = 'none'; // Ocultar la imagen si no hay archivo
        }
    });

    // Función para añadir automáticamente nueva fila de ingredientes cuando se llena una fila
    function agregarIngredienteAutomaticamente() {
        var tabla = document.getElementById("ingredientesTabla");
        var fila = tabla.insertRow(-1);
        var celda1 = fila.insertCell(0);
        var celda2 = fila.insertCell(1);
        var celda3 = fila.insertCell(2);

        celda1.innerHTML = '<input type="text" name="ingredientes[nombre][]" class="ingrediente" list="list_ing" placeholder="Ingrediente" onchange="updateUnit(this)">';
        celda2.innerHTML = '<input type="number" name="ingredientes[cantidad][]" class="cantidad" placeholder="Cantidad" step="0.01">';
        celda3.innerHTML = '<input type="text" name="ingredientes[unidad][]" class="unidad" placeholder="Unidad" readonly>';
    }

    // Función para añadir automáticamente nueva fila de platos elaborados cuando se llena una fila
    function agregarPlatoElaboradoAutomaticamente() {
        var tabla = document.getElementById("platosElaboradosTabla");
        var fila = tabla.insertRow(-1);
        var celda1 = fila.insertCell(0);
        var celda2 = fila.insertCell(1);

        celda1.innerHTML = '<input type="text" name="platos_elaborados[nombre][]" class="platoElaborado" list="list_recipes" placeholder="Nombre del plato preelaborado">';
        celda2.innerHTML = '<input type="number" name="platos_elaborados[cantidad][]" class="cantidadPlatoElaborado" placeholder="Cantidad">';
    }

    // Detectar cambios en los ingredientes y añadir fila automáticamente
    document.addEventListener('input', function(event) {
        if (event.target.classList.contains('ingrediente') || event.target.classList.contains('cantidad') || event.target.classList.contains('unidad')) {
            var ultimaFila = document.querySelectorAll('#ingredientesTabla tr:last-child input');
            if ([...ultimaFila].every(input => input.value !== "")) {
                agregarIngredienteAutomaticamente();
            }
        }

        // Detectar cambios en los platos elaborados y añadir fila automáticamente
        if (event.target.classList.contains('platoElaborado') || event.target.classList.contains('cantidadPlatoElaborado')) {
            var ultimaFilaElaborado = document.querySelectorAll('#platosElaboradosTabla tr:last-child input');
            if ([...ultimaFilaElaborado].every(input => input.value !== "")) {
                agregarPlatoElaboradoAutomaticamente();
            }
        }
    });
</script>