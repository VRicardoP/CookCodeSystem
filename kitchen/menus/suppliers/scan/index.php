<?php



$imgProfile = "./../../../img/undraw_profile.svg";
$pathDashboard = "./../../../dashboard";
$pathLogo = "./../../../img/ccsLogoWhite.png";
$pathLogout = "./../../../login/logout.php";


$menu_options = [
    'dashboard' => ['url' => './../../dashboard', 'icon' => './../../../svg/dashboard.svg', 'text' => 'Dashboard'],
    'users' => ['url' => './../../users', 'icon' => './../../../svg/user.svg', 'text' => 'User'],
    'qr' => ['url' => './../../qrcode/generator/tickets.php', 'icon' => './../../../svg/qr_code.svg', 'text' => 'QR'],
    'ecommerce' => ['url' => 'http://localhost:8080/ecommerce', 'icon' => './../../../svg/tpv.svg', 'text' => 'E-commerce'],
    'restaurant' => ['url' => 'http://localhost:8080/restaurant/public/restaurants.html', 'icon' => './../../../svg/restaurant.svg', 'text' => 'Restaurant'],
    'elaborations' => ['url' => './../../elaborations', 'icon' => './../../../svg/recipes.svg', 'text' => 'Elaborations'],
    'stock' => ['url' => './../../stock/preelaborationStock.php', 'icon' => './../../../svg/stock.svg', 'text' => 'Stock'],
    'suppliers' => ['url' => './../../suppliers/suppliersList.php', 'icon' => './../../../svg/orders.svg', 'text' => 'Suppliers'],
    'economic' => ['url' => '#', 'icon' => './../../svg/graph.svg', 'text' => 'Economic'],
];





include './../../../includes/session.php'; 
include './../../../includes/navs.php'; 
insertarTopNav("../scan/", "./../../../svg/suppliers_Black.svg", "Scan Delivery Note");
insertarTopNav("../suppliers/formSupplier.php", "./../../../svg/suppliers_Black.svg", "Create");
insertarTopNav("../suppliers/suppliersList.php", "./../../../svg/suppliers_Black.svg", "List");
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
    <!-- <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">-->
    <script src="https://kit.fontawesome.com/2e2cc992ca.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="./../../../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="./../../../css/navs.css" rel="stylesheet">
</head>
<div class="container-fluid">
    <h2>Scan Delivery Note</h2>
    <form id="imageForm" method="POST" enctype="multipart/form-data" style="display: block;">
        <label for="image">
            <img id="preview" src="./../../../svg/delivery.svg" alt="Insert image" style="max-width: 400px;">
        </label>
        <input type="file" accept="image/*" id="image" name="image" style="margin-left: 30px; display: none;">
        <br>
        <button type="submit" style="margin-top: 20px; width: 100%; max-width: 340px; max-height: 404px; margin: auto; text-align: center;">Scan Image</button>
    </form>
    <pre id="result" style="margin-top: 20px; width: 100%; height: auto; text-align: left;"></pre>
</div>

<style>
    form>* {
        width: 100%;
    }

    .container-fluid * {
        text-align: center;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        function previewImage(event) {
            const preview = document.getElementById('preview');
            if (!preview) {
                console.error("Element with id 'preview' not found.");
                return;
            }

            const file = event.target.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function() {
                    preview.src = reader.result;
                }
                reader.readAsDataURL(file);
            } else {
                preview.src = '../svg/delivery.svg';
            }
        }

        const imageInput = document.getElementById('image');
        if (imageInput) {
            imageInput.addEventListener('change', previewImage);
        } else {
            console.error("Element with id 'image' not found.");
        }

        const form = document.getElementById('imageForm');
        form.addEventListener('submit', function(event) {
            event.preventDefault();

            if (!imageInput.files || imageInput.files.length === 0) {
                preview.click();
                return;
            }

            const formData = new FormData(form);
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'upload.php', true);

            xhr.onload = function() {
                if (xhr.status === 200) {
                    const resultTextarea = document.getElementById('result');
                    resultTextarea.textContent = xhr.responseText;
                    resultTextarea.style.border = "1px solid black"

                    var respuesta = xhr.responseText;

                    // Texto de la respuesta
                    var respuesta = `PEICION: 06 /-/ 25-335

\.E.S, LLOPIS MARI
baran n®; 20442 PTDA. SENIADE S/N
= Ver01/2023 00000 = CULLERA
N° Cliente.: 0011 NIF Cliente 096557946

Cédigo LOTE 11 Articulo Cantidad Precio —_Importes
“0999 0599000018012023 VARIOS. au VE 1,00 23,00 23,000
0164109615012023 TOPINAMBI 5,00 4,00 20,000
0116108215012023 CELERI 1,00 3,30 3,300
0066 0066110415012023 BONIATO 2,30 193 4,439
0028 0028110117012023 ALCACHOFAS TERRENO 4,00 2,95 11,800
0021 0021110017012023 PIMIENTOS VERDES 260 220 5,720
0022 0022108217012023 PIMIENTOS AMARILLOS 1,90 2,95 5,605
0020 0020110017012023 PIMIENTOS ROJGS 250 2,20 5,500
0019 0019108218012023 TOMILLO FRESCO 1,00 2,50 2,500
0161 0161108218012023 CILANTRO FRESCO. 1,00 2,50 2,500
0017 0017108218012023 ALBAHACA FRESCA.

1,00 2,50 2,500`;

                    // Expresión regular mejorada para encontrar los datos de los ingredientes, código, lote e importe
                    var regex = /(\d+\s+[A-ZÁÉÍÓÚÑ\s]+\s+[A-Z]+\.\s+\d+\,\d+\s+\d+\,\d{2}\s+\d+\,\d{3})/g;

                    // Array para almacenar los datos de los ingredientes
                    var ingredientes = [];

                    // Bucle para encontrar todas las coincidencias
                    var match;
                    while ((match = regex.exec(respuesta)) !== null) {
                        // Separar la línea en sus componentes (cantidad, nombre, precio, código, lote, importe)
                        var partes = match[0].split(/\s+/);
                        var cantidad = partes[0];
                        var nombre = partes.slice(1, -5).join(" ");
                        var precio = partes[partes.length - 3];
                        var codigo = partes[partes.length - 5];
                        var lote = partes[partes.length - 4];
                        var importe = partes[partes.length - 1];

                        // Añadir los datos a la lista de ingredientes
                        ingredientes.push({
                            cantidad: cantidad,
                            nombre: nombre,
                            precio: precio,
                            codigo: codigo,
                            lote: lote,
                            importe: importe
                        });
                    }

                    // Mostrar los datos de los ingredientes
                    ingredientes.forEach(function(ingrediente) {
                        console.log("Nombre: " + ingrediente.nombre + ", Cantidad: " + ingrediente.cantidad + ", Precio: " + ingrediente.precio + ", Código: " + ingrediente.codigo + ", Lote: " + ingrediente.lote + ", Importe: " + ingrediente.importe);
                    });

                } else {
                    console.error('Error uploading image');
                }
            };

            xhr.send(formData);
        });
    });
</script>
<!-- Bootstrap core JavaScript-->
<script src="./../../../vendor/jquery/jquery.min.js"></script>
<script src="./../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Core plugin JavaScript-->
<script src="./../../../vendor/jquery-easing/jquery.easing.min.js"></script>
<!-- Custom scripts for all pages-->
<script src="./../../../js/sb-admin-2.min.js"></script>