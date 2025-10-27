<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Cook Code</title>
    <link rel="icon" type="image/png" href="./../../../img/logo.png">
    <!-- Custom fonts for this template-->
    <script src="https://kit.fontawesome.com/2e2cc992ca.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="./../../../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="./../../../css/navs.css" rel="stylesheet">
    <link href="./../../../css/tables.css" rel="stylesheet">
    <link href="./css/index.css" rel="stylesheet">
</head>

<body id="page-top">

    <?php
     require_once __DIR__ . '/../../../DBConnection.php';

    try {
        $conn = DBConnection::connectDB();
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }


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




    // Insert session and navigation includes
    include './../../../includes/session.php';
    include './../../../includes/navs.php';
    insertarTopNav('./../', './../../../svg/orders_Black.svg', 'Stock');
    insertarTopNav('./../stockLotes', './../../../svg/orders_Black.svg', 'Stock lotes');
    insertarTopNav('./../addStock', './../../../svg/orders_Black.svg', 'Add ing');
    ?>

    <div id="content-wrapper" class="d-flex flex-column">
        <div class="container-fluid">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <!--   <h1 class="h3 mb-0 text-gray-800">Mise en Place - Gestión de Stock</h1> -->
            </div>

            <div class="row">
                <div id="products-container">
                    <!-- Aquí se mostrarán los productos y sus variaciones -->
                    <div class="loader"></div> <!-- Cargando -->
                </div>
            </div>

            <div class="mt-2">
                <button id="addStockButton">Add stock</button>
            </div>

        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="module">
        import { BASE_URL } from './../../../config.js';

        document.addEventListener('DOMContentLoaded', function() {
            loadProducts();
        });

        // Función para cargar todos los productos
        function loadProducts() {
            // Mostrar el spinner de carga con SweetAlert2
            Swal.fire({
                title: 'Loading...',
                text: 'Please wait while we update the stock.',
                allowOutsideClick: false, // Evitar que el usuario cierre el modal
                didOpen: () => {
                    Swal.showLoading(); // Muestra el spinner de carga
                }
            });

            $.ajax({
                url: `${BASE_URL}/ecommerce/apiwoo/obtenerProductos.php`,
                type: 'POST',
                success: function(response) {
                    // Cerrar el spinner de carga
                    Swal.close();

                    if (response.status === 'success') {
                        let elaborados = filtrarPorElab(response.data);
                        displayProducts(elaborados);
                    } else {
                        showError('Error al cargar productos');
                    }
                },
                error: function() {
                    // Cerrar el spinner de carga en caso de error
                    Swal.close();
                    showError('Error en la solicitud');
                },
            });
        }


        // Función para filtrar productos por SKU que empiece con "ELAB"
        function filtrarPorElab(products) {
            // Usamos el método filter para filtrar los productos
            return products.filter(product => product.sku.startsWith('ELAB'));
        }




        // Función para mostrar los productos
        function displayProducts(products) {
            let productsHtml = '';
            products.forEach(product => {
                const isMainProduct = product.sku.match(/^ELAB-\d+$/);

                if (isMainProduct) {
                    let imageUrl = product.image_url || 'https://via.placeholder.com/300x300';
                    productsHtml += `<div class="product-card" data-product-id="${product.id}">`;
                    productsHtml += `<img src="${imageUrl}" alt="${product.name}" class="product-image">`;
                    productsHtml += `<h3 class="name-p">${product.name}</h3>`;
                    productsHtml += `<p><strong>ID:</strong> ${product.id}</p>`;
                    productsHtml += `<p class="sku-p" style="display: none;">${product.sku}</p>`;
                    productsHtml += `<p style="display: none;"><strong>Precio:</strong> ${product.price}</p>`;
                    productsHtml += `<p style="display: none;"><strong>Coste:</strong> ${product.cost_price}</p>`;


                    productsHtml += `<p><strong>stock_quantity:</strong> ${product.stock_quantity}</p>`;


                    productsHtml += `
                    <div class="quantity-control">
                        <button class="btn-decrement" data-product-id="${product.id}" data-stock="${product.stock_quantity}">-</button>
                        <input type="number" class="quantity-input" data-product-id="${product.id}" data-stock="${product.stock_quantity}" value="0" min="0">
                        <button class="btn-increment" data-product-id="${product.id}" data-stock="${product.stock_quantity}">+</button>
                    </div>
                `;
                    productsHtml += `</div>`;
                }
            });
            $('#products-container').html(productsHtml);
            setupEventListeners();
        }

        // Función para manejar los eventos de botones de cantidad
        function setupEventListeners() {


            $('.btn-increment').click(function() {
                const productId = $(this).data('product-id');
                const stock = $(this).data('stock');
                const inputField = $(`.quantity-input[data-product-id="${productId}"]`);
                let currentValue = parseInt(inputField.val(), 10);

                //   inputField.val(parseInt(inputField.val(), 10) + 1);
                if (currentValue < stock) inputField.val(currentValue + 1);
            });

            $('.btn-decrement').click(function() {
                const productId = $(this).data('product-id');
                const inputField = $(`.quantity-input[data-product-id="${productId}"]`);
                let currentValue = parseInt(inputField.val(), 10);
                if (currentValue > 0) inputField.val(currentValue - 1);
            });
        }

        // Función para mostrar errores
        function showError(message) {
            $('#products-container').html(`<div class="error-message">${message}</div>`);
        }

        // Función para manejar la actualización del stock
        $('#addStockButton').click(function() {
            let selectedProducts = [];

            // Iterar sobre las tarjetas de producto
            $('.product-card').each(function() {
                let quantity = parseInt($(this).find('.quantity-input').val(), 10); // Asegurar que sea un número entero

                let sku = $(this).find('.sku-p').text().trim(); // Asegurar que no tenga espacios innecesarios
                let name = $(this).find('.name-p').text().trim(); // Asegurar que no tenga espacios innecesarios

                // Obtener el <option> seleccionado y su atributo data-name


                // Verificar que se cumplan las condiciones para agregar el producto
                if (quantity > 0) {
                    let productId = $(this).data('product-id');
                    selectedProducts.push({
                        product_id: productId,
                        quantity: quantity,
                        sku: sku,
                        name: name
                    });

                    console.log("productId:", productId);
                    console.log("quantity:", quantity);
                    console.log("sku:", sku);

                }
            });
            console.log("selectedProducts:", JSON.stringify({
                selectedProducts
            }));
            // Verificar si se seleccionaron productos
            if (selectedProducts.length > 0) {
                let product = selectedProducts[0];
                // Mostrar ventana de cargando con SweetAlert2
                Swal.fire({
                    title: 'Loading...',
                    text: 'Please wait while we update the stock.',
                    allowOutsideClick: false, // Evitar que el usuario cierre el modal
                    didOpen: () => {
                        Swal.showLoading(); // Muestra el spinner de carga
                    }
                });

                // Realizar la solicitud AJAX
                $.ajax({
                    url: `${BASE_URL}/ecommerce/apiwoo/updateStockElab.php`,
                    type: 'POST',
                    data: JSON.stringify(product),
                    contentType: 'application/json',

                    success: function(response) {
                        console.log(response); // Ver la respuesta completa en la consola

                        // Verificar si la respuesta es una cadena y convertirla a objeto
                        if (typeof response === 'string') {
                            response = JSON.parse(response);
                        }

                        // Procesar la respuesta del servidor
                        if (response.status === 'success') {
                            Swal.fire('Stock actualizado correctamente');
                            loadProducts(); // Recargar los productos después de actualizar el stock

                            // Verificar si hay lotes actualizados

                            // Enviar los datos de los lotes a un archivo PHP para guardar en la base de datos
                            console.log("Datos que se envian: " + JSON.stringify(response.data));


                            $.ajax({
                                url: 'guardarLotesElab.php', // Archivo PHP que guardará los lotes en la base de datos
                                type: 'POST',
                                data: {
                                    lotes: JSON.stringify(response.data)
                                }, // Enviar los lotes como JSON
                                success: function(saveResponse) {
                                    console.log("Lote guardado: " + saveResponse); // Ver la respuesta de la operación de guardado
                                },
                                error: function(error) {
                                    console.error('Error al guardar los lotes:', error);
                                }
                            });

                        } else {
                            Swal.fire('Error al actualizar el stock');
                        }
                    },
                    error: function(error) {
                        console.error('Error en la solicitud:', error);
                        Swal.fire('Hubo un error al intentar actualizar el stock.');
                    }
                });
            } else {
                Swal.fire('Por favor, selecciona al menos una variación y una cantidad mayor que 0.');
            }
        });
    </script>





</body>

</html>