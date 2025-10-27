<?php

require __DIR__ . '/../../models/elaboraciones.php';
require_once __DIR__ . '/../../models/elaboracionesDao.php';
require __DIR__ . '/../../models/almacenIngredientes.php';
require_once __DIR__ . '/../../models/almacenIngredientesDao.php';
require __DIR__ . '/../../models/stockIngKitchen.php';
require_once __DIR__ . '/../../models/stockIngKitchenDao.php';


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
    <link href="./css/miseEnPlace.css" rel="stylesheet">
    <style>
        .row {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .centered-row {
            display: flex;
            justify-content: center;
            /* Centra horizontalmente */
            align-items: center;
            /* Alinea verticalmente los elementos */
            text-align: center;
        }

        /* Estilo opcional para ajustar el espaciado entre el label y el input */
        .centered-row label,
        .centered-row input {
            margin-right: 10px;
        }

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

    <?php insertarTopNav('./addStock', './../../svg/orders_Black.svg', 'Add ing'); ?>
    <?php insertarTopNav('./stockLotes', './../../svg/orders_Black.svg', 'stock lotes'); ?>
    <?php insertarTopNav('./addStockElab', './../../svg/orders_Black.svg', 'Add elab'); ?>

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
        <!-- Main Content -->

        <?php include 'miseEnPlace.php'; ?>
        <!-- /.container-fluid -->
    </div>
  
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
    <div id="ingredientsModal" class="modal modal-ing">
        <!-- Modal content -->
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <!-- Name Section -->
                <div class="modal-name">
                    <h2 id="nombreQr"></h2>
                </div>
                <!-- Image Section -->
                <div class="modal-image">
                    <img id="imagenQr" class="ingredient-image" alt="Ingredient Image">
                </div>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <div class="modal-columns">
                    <!-- Left Column -->
                    <div class="modal-column-left">
                        <label for="stockEcommerce">Stock Ecommerce:</label>
                        <input id="stockEcommerce" type="number" readonly>

                        <label for="stockKitchen">Stock Kitchen:</label>
                        <input id="stockKitchen" type="number" readonly>
                    </div>
                    <!-- Right Column -->
                    <div class="modal-column-right centered-row">
                        <label for="inputStock">Add:</label>
                        <input id="inputStock" type="number">
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="button-container">
                <button id="btnAddStock" name="btnAddStock" class="btn btn-primary submitBtn" disabled>
                    Buy
                </button>
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


    <script type="module">
        import { BASE_URL } from './../../../config.js';

        async function CambiaStock(sku, nuevoStock, descuentoStock) {
            console.log("nuevoStock" + nuevoStock)
            // Verificar si nuevoStock es 0 o menor
            if (nuevoStock < 0) {
                // Mostrar mensaje de error por falta de stock
                await Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Stock must be greater than 0.',
                    timer: 2500, // 2500 milisegundos = 2.5 segundos
                    showConfirmButton: false // No mostrar el botón de confirmación
                });
                setTimeout(function() {
                    location.reload(); // Recargar la página después de 2.5 segundos
                }, 500);
                return; // Salir de la función si el stock es inválido
            } else {


                var url = `${BASE_URL}/ecommerce/apiwoo/cambiarStock.php`;
                var data = new URLSearchParams();
                data.append('sku', sku);
                data.append('nuevoStock', nuevoStock);
                data.append('descuentoStock', descuentoStock);

                // Mostrar ventana de carga
                Swal.fire({
                    title: 'Updating stock...',
                    html: 'Please wait.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                try {
                    const response = await fetch(url, {
                        method: 'POST',
                        body: data
                    });

                    const result = await response.json();

                    if (result.success) {
                        // Ocultar ventana de carga
                        Swal.close();

                        // Mostrar ventana de confirmación que se cierra automáticamente después de 2.5 segundos
                        await Swal.fire({
                            icon: 'success',
                            title: 'Stock updated successfully',
                            text: 'The stock has been updated successfully.',
                            timer: 2500, // 2500 milisegundos = 2.5 segundos
                            showConfirmButton: false // No mostrar el botón de confirmación
                        });
                        setTimeout(function() {
                            location.reload(); // Recargar la página después de 2.5 segundos
                        }, 500);
                        console.log(result.message);
                    } else {
                        console.error(result.message);
                        // Ocultar ventana de carga
                        Swal.close();

                        // Mostrar mensaje de error que se cierra automáticamente después de 2 segundos
                        await Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: result.message || 'Hubo un problema al actualizar el stock.',
                            timer: 2500, // 2500 milisegundos = 2.5 segundos
                            showConfirmButton: false // No mostrar el botón de confirmación
                        });
                    }
                } catch (error) {
                    console.error('Error:', error);
                    // Ocultar ventana de carga
                    Swal.close();

                    // Mostrar mensaje de error que se cierra automáticamente después de 2 segundos
                    await Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Hubo un problema al conectar con el servidor.',
                        timer: 2500, // 2500 milisegundos = 2.5 segundos
                        showConfirmButton: false // No mostrar el botón de confirmación
                    });
                }

            }

        }


        $(document).ready(function() {
            // Get the modal
            var modal = document.getElementById("ingredientsModal");

            // Get the <span> element that closes the modal
            var span = document.getElementsByClassName("close")[0];
            var backgroundOverlay = document.getElementById("backgroundOverlay");

            // Use event delegation for buttons that open the modal
            $(document).on('click', 'button[name="modal_ing"]', function(e) {
                e.preventDefault(); // Prevent form submission
                var ingredientId = $(this).val();

                // Make an AJAX request to get the ingredients
                $.ajax({
                    url: 'get_ingredients_stock.php',
                    type: 'GET',
                    data: {
                        idIng: ingredientId
                    },
                    success: function(data) {
                        var nombre = document.getElementById('nombreQr');
                        nombre.innerHTML = data['name'];
                        var stockEcommerce = document.getElementById('stockEcommerce');
                        stockEcommerce.value = data['stockEcommerce'];
                        var stock = document.getElementById('stockKitchen');
                        stock.value = data['stock'];
                        var imagen = document.getElementById('imagenQr');
                        imagen.src = './../' + data['imagen'];

                        var btn = document.getElementById('btnAddStock');
                        btn.setAttribute('data-id', data['id']); // Use data attribute to store ID

                        modal.style.display = "block";
                        backgroundOverlay.style.display = "block";
                    }
                });
            });

            var submitButton = document.getElementById('btnAddStock');

            // Function to enable or disable the button
            function toggleButton() {
                // Get the input value
                var stockValue = parseInt(inputStock.value, 10);

                // Enable the button if the value is greater than 0
                if (stockValue > 0) {
                    submitButton.disabled = false;
                } else {
                    submitButton.disabled = true;
                }
            }

            var inputStock = document.getElementById('inputStock');

            // Add input event listener
            inputStock.addEventListener('input', toggleButton);

            // Call toggleButton initially to set the correct button state
            toggleButton();

            // Use event delegation for the button that adds stock
            $(document).on('click', '#btnAddStock', function(e) {
                e.preventDefault(); // Prevent form submission

                var stockTienda = document.getElementById('stockEcommerce');
                var inputStock = document.getElementById('inputStock');

                if (parseFloat(stockTienda.value) < parseFloat(inputStock.value)) {
                    alert('Error: Stock ecommerce is less than the amount to add.');
                } else {
                    var ingredientId = $(this).data('id'); // Get the ID from the data attribute
                    var addStock = $('#inputStock').val(); // Get the value from the input

                    console.log('Ingredient ID: ' + ingredientId);
                    console.log('Stock to add: ' + addStock);

                    // Perform AJAX request to add stock
                    $.ajax({
                        url: 'addStock.php',
                        type: 'GET',
                        data: {
                            idIng: ingredientId,
                            addStock: addStock
                        },
                        success: async function(data) {
                            var sku = data.sku;
                            var stockEcommerce = parseFloat(data.stock_ecommerce);
                            var currentStock = parseFloat(data.addStock);

                            console.log(stockEcommerce);
                            console.log(currentStock);

                            var nuevoStock = stockEcommerce - currentStock;

                            console.log("SKU: " + sku);

                            await CambiaStock(sku, nuevoStock, currentStock);

                            // Clear the input field
                            $('#inputStock').val('');
                        },
                        error: function() {
                            // Show error if the request fails
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'There was a problem updating the stock.',
                            });
                        }
                    });
                }
            });


            // When the user clicks anywhere outside of the modal, close it
            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";

                    backgroundOverlay.style.display = "none";

                }
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>