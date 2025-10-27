<?php
require_once __DIR__ . '/../../DBConnection.php';
require __DIR__ . '/../../models/ingredientes.php';
require_once __DIR__ . '/../../models/ingredientesDao.php';
require __DIR__ . '/../../models/alergenos.php';
require_once __DIR__ . '/../../models/alergenosDao.php';
require __DIR__ . '/../../models/recetas.php';
require_once __DIR__ . '/../../models/recetasDao.php';
require __DIR__ . '/../../models/precioProducto.php';
require_once __DIR__ . '/../../models/precioProductoDao.php';


include './../../includes/session.php';


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset(($_POST["modfProduct"]))) {
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
} else if ($_SERVER["REQUEST_METHOD"] == "POST" && isset(($_POST["newProduct"]))) {
    $obj = new PreciosProducto();
    // $obj->setId($id);
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

    <link rel="icon" type="image/png" href="./../../img/logo.png">
    <!-- Custom fonts for this template-->
    <script src="https://kit.fontawesome.com/2e2cc992ca.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href=" https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css" rel="stylesheet" type="text/css">
    <!-- Custom styles for this template-->
    <link href="./../../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="./../../css/navs.css" rel="stylesheet">
    <link rel="stylesheet" href="./../../css/elaborations/recipesC.css">
    <link href="./../../css/tables.css" rel="stylesheet">
    <link href="./../../css/model.css" rel="stylesheet">
    <link href="./css/recipes.css" rel="stylesheet">
    <style>
        /* Estilos generales para el modal */

        .form-group label {
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
            outline: none;
            transition: all 0.3s;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: #007934;
            box-shadow: 0px 0px 5px rgba(0, 121, 52, 0.5);
        }

        
    </style>
</head>

<body id="page-top">
    <?php
   
$link = DBConnection::connectDB();
    // Check connection
   if ($link === null) {
    die("Connection failed.");
}
    ?>


    <?php include './../../includes/navs.php'; ?>

    <?php insertarTopNav('./create-ing', './../../svg/orders_Black.svg', 'Create ingredient'); ?>

    <?php insertarTopNav('./create-rec', './../../svg/orders_Black.svg', 'Create pre-prepared'); ?>

    <!-- Page Wrapper -->
    <div id="wrapper" class="d-flex flex-column">

        <!-- End of Topbar -->
        <!-- ************************************************************************************************************************ -->
        <!-- Begin Page Content -->
        <div class="container-fluid">

            <!-- Page Heading -->

            <!-- Content Row -->
            <div class="row">

                <div id="divContent" class="col-md-12">

                    <?php include './listElaboration.php'; ?>

                    <?php include './recipeList.php'; ?>

                    <?php include './listIngr.php'; ?>

                    <!-- /.container-fluid -->

                </div>
                <!-- End of Main Content -->


            </div>
            <!-- End of Content Wrapper -->
        </div>
        <!-- End of Page Wrapper -->

        <!-- Scroll to Top Button-->
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>

        <!-- The Modal recetas -->
        <div id="recipeModal" class="modal-recipe">
            <!-- Modal content -->
            <div class="modal-content">
                <div class="">

                </div>

                <!-- Header Section: Name and Image -->
                <div class="modal-header d-flex">
                    <div class="modal-name ">
                        <h3 id="nombreReceta"></h3>
                    </div>
                    <div class="modal-image">
                        <img id="imagenReceta" class="recipe-image">
                    </div>
                </div>
                <div class="recipe-details">

                </div>
                <!-- Body Section: Two Columns -->
                <div class="modal-body">



                    <div class="modal-columns">


                        <!-- Left Column: Instructions -->
                        <div class="modal-column-left">
                            <div>
                                <p id="caducidadReceta"></p>
                                <p id="pesoReceta"></p>
                            </div>

                            <h4>Instructions</h4>
                            <div id="divInstrucciones" class="modal-instructions">
                                <!-- Instructions will be dynamically inserted here -->
                            </div>
                        </div>

                        <!-- Right Column: Other Details -->
                        <div class="modal-column-right">
                            <div>
                                <p id="localizacionReceta"></p>
                                <p id="empaquetadoReceta"></p>
                            </div>

                            <!-- Ingredients Section -->
                            <h4>Ingredients</h4>
                            <div class="modal-ingredients" id="ingredientsList">
                                <!-- Ingredients will be dynamically inserted here -->
                            </div>

                            <!-- Elaborados Section -->
                            <h4 style="display: none;" id="h3-elaborados">Elaboration</h4>
                            <div class="modal-ingredients" id="elaboradosList" style="display: none;">
                                <!-- Elaborados will be dynamically inserted here -->
                            </div>


                        </div>

                        <button class='btn-primary rounded' id='btnPrint' type='button' onclick="imprimir()">
                            Print <img src='./../../svg/printing.svg' alt='Ing' title='Ingredients' style="width: 25px; height:25px">
                        </button>
                    </div>
                </div>


            </div>
        </div>
    </div>




    <!-- The Modal -->
    <div id="ingredientsModal" class="modal">
        <!-- Modal content -->
        <div class="modal-content">


            <div class="modal-header">
                <h3 id="nombreIng">Modify Ingredient</h3>


                <!-- Campo para el imagen -->
                <div class="form-group">
                    <img src="./../../img/ingredients/sin-imagen.jpg" alt="Ingredient Image" id="imagen-ing" class="ingredient-image" style="width: 60px; height: 60px;  margin-left: 10px;">

                </div>

            </div>

            <div class="modal-body">
                <form method="get" id="ingredientForm">
                    <div class="modal-columns">

                        <div class="modal-column-left">



                            <!-- Campo para el nombre -->
                            <div class="form-group">
                                <label for="nombre">Name:</label>
                                <input type="text" id="nombre" name="nombre" step="0.01" min="0" placeholder="Enter name" required>
                            </div>



                            <!-- Campo para el localizacion -->
                            <div class="form-group">
                                <label for="coste">Cost(€):</label>
                                <input type="number" id="coste" name="coste" step="0.01" min="0" placeholder="Enter cost" required style="max-width: 70px;text-align: right;">


                                <!-- Campo para la venta -->

                                <label for="venta">Sale(€):</label>
                                <input type="number" id="venta" name="venta" step="0.01" min="0" placeholder="Enter sale" required style="max-width: 70px;text-align: right;">



                            </div>

                            <!-- Campo para el empaquetado -->
                            <div class="form-group">


                                <label for="alergeno-Ing">Allergen:</label>
                                <select name="alergeno-Ing" id="alergeno-Ing" style="width: 280px;">
                                    <?php
                                    foreach ($listaAlergenos as $alergeno) {
                                        $nombreAlergeno = htmlspecialchars($alergeno->getNombre());
                                        $idAlergeno = $alergeno->getId();
                                        echo "<option value='$nombreAlergeno' data-id='$idAlergeno'>$nombreAlergeno</option>";
                                    }
                                    ?>
                                </select>

                            </div>



                            <!-- Campo para el coste -->
                            <div class="form-group">

                                <label for="localizacion">Location:</label>
                                <select name="warehouse-Ing" id="warehouse-Ing" style="width: 280px;">

                                    <option value="Freezer">Freezer</option>
                                    <option value="Warehouse">Warehouse</option>
                                    <option value="Final product area">Final product area</option>
                                    <option value="Dry">Dry</option>

                                </select>
                            </div>



                            <div class="form-group col-md-12  align-items-end">
                                <label for="descripcionCortaIng">Short description:</label>



                                <textarea id="descripcionCortaIng" class="form-control" placeholder="Short description" rows="1" style="max-width: 450px;"></textarea>
                            </div>
                        </div>


                        <div class="modal-column-right">


                            <div class="form-group">


                                <label for="empaquetado">Package:</label>
                                <select name="packaging-Ing" id="packaging-Ing">
                                    <option value="Bag">Bag</option>
                                    <option value="Pack">Pack</option>
                                    <option value="Box">Box</option>
                                    <option value="Bottle">Bottle</option>
                                    <option value="Can">Can</option>
                                </select>

                                <label for="expire-Ing">Expire:</label>
                                <input type="number" id="expire-Ing" name="expire-Ing" step="1" min="0" placeholder="" style="max-width: 70px;text-align: right;">

                                <span style="font-weight: bold;">days</span>


                            </div>


                            <div class="form-group">
                                <label for="unidad-Ing">Unit of measurement:</label>
                                <select name="unidad-Ing" id="unidad-Ing" onchange="showWeightField()">
                                    <option value="Kg">Kg</option>
                                    <option value="L">L</option>
                                    <option value="Und">Und</option>
                                </select>

                                </div>

                                <div class="form-group">
                                <label for="unitWeight-Ing" id="labelWeight">Weight:</label>
                                <input type="number" id="unitWeight-Ing" name="unitWeight-Ing" step="0.01" min="0" placeholder="Weight in (kg)" style="max-width: 100px;text-align: right;">
                                <span style="font-weight: bold;">Kg</span>

                            </div>






                            <div id="divAddCantidad" class="form-group d-flex align-items-center">
                                <label for="addCantidad-Ing">Sales quantities:</label>
                                <input type="number" id="addCantidad-Ing" name="addCantidad-Ing" step="0.01" min="0" placeholder="Add quantity" style="max-width: 100px;margin: 10px;text-align: right;">


                                <button id="btnAddCantidad" class="" type="button">Add</button>
                            </div>
                            <p id="nombreValores" style="font-weight: bold;"></p>
                            <div id="content-cantidad-edit">


                            </div>
                        </div>

                    </div>










                </form>


            </div>
            <div class="modal-footer">
                <!-- Botón para guardar los cambios -->
                <button type="submit" id="btnCostIng" class="btn-save">Edit</button>
            </div>
        </div>
    </div>


    <!-- The Modal Ingredients -->
    <div id="ingModal" class="modal-ing">
        <!-- Modal content -->
        <div class="modal-content">
            <!-- Header Section: Name and Image -->
            <div class="modal-header d-flex">
                <div class="modal-name">
                    <h3 id="nombreIng-h3"></h3>
                </div>
                <div class="modal-image">
                    <img id="imagenIng" src="./../default-image.png" alt="Ingredient Image" class="ingredient-image" style="width: 80px; height: 80px;">
                </div>
            </div>

            <!-- Body Section: Two Columns -->
            <div class="modal-body">
                <div class="modal-columns">
                    <!-- Left Column: Basic Details -->
                    <div class="modal-column-left">
                        <div>
                            <p id="caducidadIng"><strong>Expire in:</strong> N/A days</p>
                            <p id="mermaIng"><strong>Decrease:</strong> N/A </p>
                            <p id="costIng"><strong>Cost:</strong> N/A </p>
                            <p id="saleIng"><strong>Sale:</strong> N/A </p>
                            <p id="weightIng"><strong>Weight:</strong> N/A </p>
                            <p id="shortDescriptionIng"><strong>Short description:</strong> N/A </p>

                        </div>
                    </div>

                    <!-- Right Column: Other Details -->
                    <div class="modal-column-right">
                        <div>
                            <p id="localizacionIng"><strong>Localization:</strong> N/A</p>
                            <p id="empaquetadoIng"><strong>Package:</strong> N/A</p>
                            <p id="alergenoIng"><strong>Allergen:</strong> N/A</p>
                            <p id="clasificacion-ing"><strong>Clasification:</strong> N/A</p>

                            <div id="content-cantidad">


                            </div>
                        </div>
                    </div>





                </div>

            </div>
            <div class="modal-footer">
                <button class='btn-primary rounded' id='btnPrint' type='button' onclick="imprimir()">
                    Print <img src='./../../svg/printing.svg' alt='Ing' title='Ingredients' style="width: 25px; height:25px">
                </button>
            </div>
        </div>
    </div>

    <!-- The Background Overlay -->
    <div id="backgroundOverlay"></div>
    <script>
        function showWeightField() {
            const unitSelect = document.getElementById('unitNewIng');
            const weightField = document.getElementById('weightField');

            // Si se selecciona "Und", mostrar el campo de peso
            if (unitSelect.value === 'Und' || unitSelect.value === 'L') {
                weightField.style.display = 'block';
            } else {
                weightField.style.display = 'none';
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

        function previewImageIng(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById('imagenChIng');
                output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }

        function deleteIng(id) {

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning', // Icono de advertencia
                background: '#fff3cd', // Fondo amarillo suave para advertencia
                iconColor: '#856404', // Color del icono (amarillo oscuro)
                showCancelButton: true, // Muestra un botón de cancelación
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true, // Cambia el orden de los botones
                customClass: {
                    title: 'swal-title-custom', // Clase personalizada para el título
                    content: 'swal-content-custom', // Clase personalizada para el contenido
                    confirmButton: 'swal-confirm-btn', // Clase personalizada para el botón de confirmación
                    cancelButton: 'swal-cancel-btn' // Clase personalizada para el botón de cancelación
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Si el usuario confirma la acción, elimina el item
                    deleteItem(id, 'ing');
                }
            });


        }

        function deleteItem(id, type) {
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
                    location.reload();
                    // Elimina la fila de la tabla correspondiente
                    $('#row-' + type + '-' + id).remove();
                    // Muestra un mensaje de éxito con SweetAlert2
                    Swal.fire({
                        title: 'Ingredient deleted!',
                        text: 'The ingredient has been successfully deleted.',
                        icon: 'error', // Usamos un icono de error para mostrar que es una eliminación
                        background: '#f8d7da', // Fondo de color suave (rojo claro)
                        iconColor: '#721c24', // Color del icono (rojo más oscuro)
                        timer: 2000, // 1500ms = 1.5 segundos
                        showConfirmButton: false, // Elimina el botón de confirmación
                        customClass: {
                            title: 'swal-title-custom', // Agregar clase personalizada al título
                            content: 'swal-content-custom' // Agregar clase personalizada al contenido
                        }
                    }).then(() => {
                        location.reload(); // Recarga la página después de que el usuario haga clic en "OK"
                    });
                },


                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }



        function editIng(id) {



            var cantidad = "";

            // Realizar la solicitud AJAX
            $.ajax({
                url: './../../controllers/getIngredient.php',
                type: 'GET',
                data: {
                    id: id
                },
                dataType: 'json',
                success: function(data) {
                    console.log("Respuesta de la API:", data); // Depuración para verificar la respuesta completa

                    // Verificar si los datos del ingrediente existen
                    if (!data || !data.ingrediente) {
                        alert("No se encontraron datos del ingrediente.");
                        return;
                    }
                    // Calcular el porcentaje de merma
                    const merma = (data.ingrediente.merma * 100).toFixed(2);
                    // Actualizar los elementos del modal con los datos recibidos
                    var nombre = document.getElementById('nombre').value = data.ingrediente.nombre || "N/A";
                    console.log(nombre);
                    document.getElementById('imagen-ing').src = data.ingrediente.imagen ? './../' + data.ingrediente.imagen : './../default-image.png';

                    document.getElementById('warehouse-Ing').value = data.ingrediente.warehouse || "N/A";
                    document.getElementById('packaging-Ing').value = data.ingrediente.empaquetado || "N/A";
                    document.getElementById('coste').value = data.ingrediente.coste || "N/A";
                    document.getElementById('venta').value = data.ingrediente.venta || "N/A";

                    document.getElementById('unitWeight-Ing').value = data.ingrediente.peso || "N/A";


                    document.getElementById('unidad-Ing').value = data.ingrediente.unidad || "N/A";
                    document.getElementById('alergeno-Ing').value = data.ingrediente.alergeno || "N/A";

                    document.getElementById('descripcionCortaIng').value = data.ingrediente.descripcion_corta || "N/A";
                    document.getElementById('nombreValores').textContent = data.ingrediente.nombre_valores_tienda || "N/A";
                    document.getElementById('expire-Ing').value = data.ingrediente.expira_dias || "N/A";
                    document.getElementById('labelWeight').textContent = "Weight(1" + data.ingrediente.unidad + "):";

                    var divCantidad = document.getElementById('content-cantidad-edit');

                    var divCantidad = document.getElementById('content-cantidad-edit');
                    var inputCantidad = document.getElementById('addCantidad-Ing');
                    var btnAgregar = document.getElementById('btnAddCantidad');

                    cantidad = data.ingrediente.cantidades_tienda || "N/A";
                    var cantidadArray = cantidad !== "N/A" ? cantidad.split(",") : [];

                    // Div contenedor para las cantidades
                    var cantidadTienda = document.createElement('div');

                    // Función para agregar una cantidad al DOM
                    function agregarCantidad(valor, index) {
                        var cantidadSplit = valor.split(":"); // Divide el elemento en clave-valor
                        var p = document.createElement('p');
                        p.innerHTML = `<strong>${cantidadSplit[0]}</strong> ${data.ingrediente.unidad}`;
                        p.classList.add('square-edit');

                        // Agregar evento de clic para eliminar
                        p.addEventListener('click', function() {
                            cantidadArray.splice(index, 1);
                            cantidad = cantidadArray.join(","); // Actualiza la variable cantidad
                            console.log("Cantidad eliminada:", cantidad);
                            p.remove(); // Elimina del DOM
                        });

                        cantidadTienda.appendChild(p);
                    }

                    // Agregar las cantidades ya existentes al cargar
                    cantidadArray.forEach((element, index) => {
                        agregarCantidad(element, index);
                    });

                    // Evento para agregar nueva cantidad desde el input existente
                    btnAgregar.addEventListener('click', function() {
                        var nuevaCantidad = inputCantidad.value.trim();
                        if (nuevaCantidad && !isNaN(nuevaCantidad) && parseFloat(nuevaCantidad) > 0) {
                            cantidadArray.push(nuevaCantidad);
                            agregarCantidad(nuevaCantidad, cantidadArray.length - 1);
                            cantidad = cantidadArray.join(","); // Actualiza la variable cantidad
                            inputCantidad.value = ''; // Limpia el input
                            console.log("Nueva cantidad añadida:", cantidad);
                        } else {
                            alert("Ingrese una cantidad válida.");
                        }
                    });

                    // Agregar el contenedor de cantidades al DOM
                    divCantidad.appendChild(cantidadTienda);

                    // Mostrar el modal
                    modal.style.display = "block";
                    backgroundOverlay.style.display = "block";
                },
                error: function(xhr, status, error) {
                    console.error("Error en la solicitud AJAX:", status, error);
                    console.error("Respuesta del servidor:", xhr.responseText); // Depuración adicional
                    alert("Ocurrió un error al obtener los datos del ingrediente. Por favor, inténtalo más tarde.");
                }
            });







            var modal = document.getElementById("ingredientsModal");
            modal.style.display = "block";
            backgroundOverlay.style.display = "block";
            $(document).ready(function() {
                // No es necesario volver a seleccionar el modal aquí
                // var modal = document.getElementById("ingredientsModal");
                window.onclick = function(event) {
                    if (event.target == modal) {
                        modal.style.display = "none";
                        backgroundOverlay.style.display = "none";
                        var ingredientsList = document.getElementById("ingredientsList");
                        ingredientsList.style.display = "flex";
                        document.getElementById('content-cantidad-edit').replaceChildren();
                    }
                }
                $('button[id="btnCostIng"]').click(function(e) {


                    e.preventDefault(); // Prevent form submission


                    Swal.fire({
                        title: "Warning!",
                        text: "Modifying this ingredient will not update previously created batches. Do you want to continue?",
                        icon: "warning",
                        background: '#fff3cd', // Fondo amarillo suave para advertencia
                        iconColor: '#856404', // Color del icono (amarillo oscuro)
                        showCancelButton: true,
                        confirmButtonText: "Yes, update it",
                        cancelButtonText: "Cancel"
                    }).then((result) => {
                        if (result.isConfirmed) {


                            var nombre = document.getElementById('nombre').value.trim();
                            var cost = document.getElementById('coste').value.trim();
                            var sale = document.getElementById('venta').value.trim();
                            var peso = document.getElementById('unitWeight-Ing').value.trim();
                            var localizacion = document.getElementById('warehouse-Ing').value.trim();
                            var empaquetado = document.getElementById('packaging-Ing').value.trim();
                            var unidad = document.getElementById('unidad-Ing').value.trim();
                            var alergeno = document.getElementById('alergeno-Ing').value.trim();
                            var descripcionCorta = document.getElementById('descripcionCortaIng').value.trim();
                            var expire = document.getElementById('expire-Ing').value.trim();;
                            // Obtiene el ID del alérgeno de la opción seleccionada
                            var alergenoSelect = document.getElementById('alergeno-Ing');
                            var alergenoId = alergenoSelect.selectedOptions.length > 0 ? alergenoSelect.selectedOptions[0].dataset.id : "";

                            // Validaciones individuales con alertas específicas
                            if (!nombre) {
                                alert("Please enter the ingredient name.");
                                return;
                            }
                            if (!cost) {
                                alert("Please enter the ingredient cost.");
                                return;
                            }
                            if (!sale) {
                                alert("Please enter the sale price.");
                                return;
                            }
                            if (!peso) {
                                alert("Please enter the unit weight.");
                                return;
                            }
                            if (!localizacion) {
                                alert("Please enter the warehouse location.");
                                return;
                            }
                            if (!empaquetado) {
                                alert("Please enter the packaging details.");
                                return;
                            }
                            if (!unidad) {
                                alert("Please select the unit type.");
                                return;
                            }
                            if (!alergeno) {
                                alert("Please select an allergen.");
                                return;
                            }
                            if (!cantidad.trim()) {
                                alert("Please enter a quantity for e-commerce.");
                                return;
                            }
                            if (!expire.trim()) {
                                alert("Please enter expire days.");
                                return;
                            }




                            // Make an AJAX request to save the ingredient cost
                            $.ajax({
                                url: './../../controllers/get_ingredients.php',
                                type: 'GET',
                                data: {
                                    editIng: id,
                                    nombre: nombre,
                                    cost: cost,
                                    sale: sale,
                                    localizacion: localizacion,
                                    empaquetado: empaquetado,
                                    //  nombreVariacion: nombreVariacion,
                                    cantidades_variacion: cantidad,
                                    unidad: unidad,
                                    alergeno: alergeno,
                                    alergenoId: alergenoId,
                                    peso: peso,
                                    descripcionCorta: descripcionCorta,
                                    expiracion: expire
                                },
                                dataType: 'json',
                                success: function(data) {
                                    console.log('Response de edit:', data); // Depuración

                                    var ingrediente = formateoDatosIng(data)
                                    modificarIngredienteEcommerce(ingrediente);

                                    Swal.fire({
                                        title: "Ingredient successfully updated!",
                                        text: "The ingredient has been updated successfully.",
                                        icon: "success", // Icono de éxito   
                                        timer: 1500, // 2000ms = 2 segundos
                                        showConfirmButton: false, // Elimina el botón de confirmación

                                    }).then(() => {
                                        modal.style.display = "none";
                                        location.reload(); // Refresca la página después de que el swal se cierre automáticamente
                                    });
                                },
                                error: function(xhr, status, error) {
                                    console.error('Error:', error); // Manejo de errores
                                }
                            });



                        }
                    });
                });









            });
        }



        function formateoDatosIng(ingrediente) {



            //  let ingrediente = ingrentesDatosForm();
            //console.log("Ingrediente para ecommerce: " + JSON.stringify(ingrediente));

            let precios = [];
            let valoresCantidad = ingrediente.cantidades_variacion.split(',');

            valoresCantidad.forEach(valorCantidad => {
                let cantidad = parseFloat(valorCantidad);
                if (!isNaN(cantidad)) {
                    let precioVariacion = ingrediente.venta * cantidad;
                    precios.push(precioVariacion);
                }
            });

            const producto = {
                sku: ingrediente.sku,
                name: ingrediente.nombre,
                type: 'variable',
                description: `Este ingrediente (${ingrediente.nombre}) es una parte esencial de nuestras recetas.`,
                short_description: ingrediente.descripcionCorta,
                localizacion: ingrediente.localizacion,
                empaquetado: ingrediente.empaquetado,
                alergeno: ingrediente.alergeno,
                type_unit: ingrediente.unidad,
                cost_price: ingrediente.coste,
                regular_price: ingrediente.venta,
                sale_price: ingrediente.venta,
                peso: ingrediente.peso,

                images: [{
                    src: 'default.jpg'
                }],
                categories: [{
                    id: 22
                }],
                attributes: [{
                    name: ingrediente.nombre_variacion, // Este es el nombre exacto del atributo que ya tienes en WooCommerce
                    options: ingrediente.cantidades_variacion ? ingrediente.cantidades_variacion.split(',').map(option => option.trim()) : [], // Los valores de peso
                    variation: true // Definir este atributo como una variación
                }],
                meta_data: [{
                        key: 'cost_price',
                        value: ingrediente.coste || 0
                    },
                    {
                        key: 'type_unit',
                        value: ingrediente.unidad || 'Unidad'
                    },
                    {
                        key: 'localizacion',
                        value: ingrediente.localizacion || 'Desconocida'
                    },
                    {
                        key: 'empaquetado',
                        value: ingrediente.empaquetado || 'Desconocido'
                    },
                    {
                        key: 'alergeno',
                        value: ingrediente.alergeno || 'Desconocido'
                    }

                ],
                manage_stock: false, // Si no manejas stock, deja esto en falso
                variations: ingrediente.cantidades_variacion ? ingrediente.cantidades_variacion.split(',').map((valor, index) => ({
                    regular_price: precios[index], // Asegúrate de que 'precios' tenga los valores correctos
                    stock_quantity: 0, // Ajusta el stock según lo necesario
                    attributes: [{
                        name: ingrediente.nombre_variacion, // Aquí debe coincidir con el nombre del atributo
                        option: valor.trim() // Valor específico para esta variación
                    }]
                })) : []
            };


            return producto;
        }





        function modificarIngredienteEcommerce(ingrediente) {
            console.log("Ingrediente para ecommerce: " + JSON.stringify(ingrediente));
            // URL del archivo PHP que maneja la solicitud POST

            $.ajax({
                url: 'http://localhost:8080/ecommerce/apiwoo/modificarIng.php', // Ruta del servidor
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(ingrediente), // Convertir el objeto a JSON
                dataType: 'json',
                success: function(response) {
                    console.log("Respuesta del servidor:", response);

                    try {
                      
                        if (response.success) {
                            alert("Ingrediente modificado correctamente.");

                        } else {
                            alert("Error: " + response.message);
                        }
                    } catch (error) {
                        console.error("Error al procesar la respuesta:", error);
                        alert("Error en la respuesta del servidor.");
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error AJAX:", error);
                    alert("Hubo un problema al modificar el ingrediente.");
                }
            });
        }














        /** 

                function formateoDatosIng(sku) {
                    let ingrediente = ingrentesDatosForm();
                    let precios = [];
                    let valoresCantidad = ingrediente.ing_atr_values.split(',');

                    valoresCantidad.forEach(valorCantidad => {
                        let cantidad = parseFloat(valorCantidad);
                        if (!isNaN(cantidad)) {
                            let precioVariacion = ingrediente.ing_cost_price * 1.5 * cantidad;
                            precios.push(precioVariacion);
                        }
                    });

                    const producto = {
                        sku: sku,
                        name: ingrediente.ing_name,
                        type: 'variable',
                        description: `Este ingrediente (${ingrediente.ing_name}) es una parte esencial de nuestras recetas.`,
                        short_description: `Ingrediente: ${ingrediente.ing_name}`,
                        images: [{
                            src: 'http://localhost:8080/kitchen/img/ingredients/' + (document.getElementById('imagenIng').files[0]?.name || 'default.jpg')
                        }],
                        categories: [{
                            id: 22
                        }],
                        attributes: [{
                            name: 'Peso', // Este es el nombre exacto del atributo que ya tienes en WooCommerce
                            options: ingrediente.ing_atr_values ? ingrediente.ing_atr_values.split(',').map(option => option.trim()) : [], // Los valores de peso
                            variation: true // Definir este atributo como una variación
                        }],
                        meta_data: [{
                                key: 'cost_price',
                                value: ingrediente.ing_cost_price || 0
                            },
                            {
                                key: 'type_unit',
                                value: ingrediente.ing_unit || 'Unidad'
                            }
                        ],
                        manage_stock: false, // Si no manejas stock, deja esto en falso
                        variations: ingrediente.ing_atr_values ? ingrediente.ing_atr_values.split(',').map((valor, index) => ({
                            regular_price: precios[index], // Asegúrate de que 'precios' tenga los valores correctos
                            stock_quantity: 0, // Ajusta el stock según lo necesario
                            attributes: [{
                                name: 'Peso', // Aquí debe coincidir con el nombre del atributo
                                option: valor.trim() // Valor específico para esta variación
                            }]
                        })) : []
                    };


                    return producto;
                }


                function modificarIngrediente(data) {



                    console.log("producto: " + JSON.stringify(data));
                    // URL del archivo PHP que maneja la solicitud POST
                    const url = 'http://localhost:8080/ecommerce/apiwoo/crearProductoPrincipalIng.php';

                    const requestOptions = {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(data)
                    };

                    // Realizar la solicitud y devolver la promesa
                    return fetch(url, requestOptions)
                        .then(response => response.text()) // Convertir la respuesta a texto
                        .then(data => {
                            //console.log(data); // Imprimir la respuesta
                        })
                        .catch(error => {
                            console.error('Error al agregar el producto:', error);
                            throw error; // Lanzar el error para que pueda ser manejado fuera de la función
                        });
                }
                        */
    </script>

    <script>
        function modalRecipe(id) {
            console.log(id);
            $(document).ready(function() {
                // Obtener el modal
                var modal = document.getElementById("recipeModal");
                var backgroundOverlay = document.getElementById("backgroundOverlay");

                // Obtener el botón de cierre del modal
                var span = document.getElementsByClassName("close")[0];

                // ID de la receta
                var recipeId = id;

                // Realizar la solicitud AJAX
                $.ajax({
                    url: './../../controllers/getReceta.php',
                    type: 'GET',
                    data: {
                        id: recipeId
                    },
                    dataType: 'json',
                    success: function(data) {
                        console.log("Respuesta de la API:", data); // Depuración para verificar la respuesta completa

                        // Verificar si los datos de la receta existen
                        if (!data || !data.receta) {
                            console.error("No se encontraron datos de la receta.");
                            return;
                        }

                        // Resto de la asignación de variables para la receta
                        var raciones = data.receta.num_raciones > 1 ?
                            " (" + data.receta.num_raciones + " rations)" :
                            " (" + data.receta.num_raciones + " ration)";

                        var nombre = document.getElementById('nombreReceta');
                        nombre.innerHTML = data.receta.receta + raciones;

                        var imagen = document.getElementById('imagenReceta');
                        imagen.src = './../' + data.receta.imagen;

                        var caducidad = document.getElementById('caducidadReceta');
                        caducidad.innerHTML = "<strong>Expire in: </strong>" + data.receta.expira_dias + " days";

                        var peso = document.getElementById('pesoReceta');
                        peso.innerHTML = "<strong>Weight: </strong>" + data.receta.peso + "kg";



                        var localizacion = document.getElementById('localizacionReceta');
                        localizacion.innerHTML = "<strong>Localization: </strong>" + data.receta.localizacion;

                        var empaquetado = document.getElementById('empaquetadoReceta');
                        empaquetado.innerHTML = "<strong>Package: </strong>" + data.receta.empaquetado;

                        // Manejo de ingredientes (si hay)
                        var ingredientsList = document.getElementById("ingredientsList");
                        ingredientsList.innerHTML = ""; // Vaciar contenido previo
                        if (Array.isArray(data.ingredientes) && data.ingredientes.length > 0) {
                            data.ingredientes.forEach(ingredient => {
                                var p = document.createElement('p');
                                p.innerHTML = `<strong>${ingredient.nombre}</strong> ${ingredient.cantidad} ${ingredient.unidad}`;
                                ingredientsList.appendChild(p);
                            });
                        } else {
                            console.log("No hay ingredientes disponibles.");
                        }

                        // Manejo de elaborados (si hay)
                        var elaboradosH = document.getElementById("h3-elaborados");
                        var elaboradosList = document.getElementById("elaboradosList");
                        elaboradosList.innerHTML = ""; // Vaciar contenido previo
                        if (Array.isArray(data.elaborados) && data.elaborados.length > 0) {
                            elaboradosList.style.display = "block"; // Mostrar la lista de elaborados
                            elaboradosH.style.display = "block"; // Mostrar el título de elaborados
                            data.elaborados.forEach(elaborado => {
                                var p = document.createElement('p');
                                p.innerHTML = `<strong>${elaborado.nombre}</strong> ${elaborado.cantidad} und`;
                                elaboradosList.appendChild(p);
                            });
                        } else {
                            console.log("No hay elaborados disponibles.");
                        }


                        // Instrucciones
                        var divInstrucciones = document.getElementById('divInstrucciones');
                        divInstrucciones.innerHTML = ""; // Vaciar contenido previo

                        // Reemplazar los saltos de línea con <br>
                        var instrucciones = data.receta.instrucciones.replace(/\n/g, "<br>");

                        // Asignar las instrucciones con los saltos de línea procesados
                        divInstrucciones.innerHTML = `<p>${instrucciones}</p>`;

                        // Mostrar el modal
                        modal.style.display = "block";
                        backgroundOverlay.style.display = "block";
                    },
                    error: function(xhr, status, error) {
                        console.error("Error en la solicitud AJAX:", status, error);
                        console.error("Respuesta del servidor:", xhr.responseText); // Depuración adicional
                    }
                });

                // Cerrar el modal al hacer clic fuera del mismo
                window.onclick = function(event) {
                    if (event.target == modal) {
                        closeModal();

                    }
                };

                // Cerrar el modal al hacer clic en el botón de cierre
                span.onclick = function() {
                    closeModal();
                };

                // Función para cerrar el modal y limpiar los datos
                function closeModal() {
                    modal.style.display = "none";
                    backgroundOverlay.style.display = "none";

                    // Restablecer el contenido del modal
                    document.getElementById('nombreReceta').innerHTML = "";
                    document.getElementById('imagenReceta').src = "";
                    document.getElementById('caducidadReceta').innerHTML = "";
                    document.getElementById('pesoReceta').innerHTML = "";
                    document.getElementById('localizacionReceta').innerHTML = "";
                    document.getElementById('empaquetadoReceta').innerHTML = "";
                    document.getElementById('ingredientsList').innerHTML = ""; // Vaciar lista de ingredientes
                    document.getElementById('elaboradosList').innerHTML = ""; // Vaciar lista de elaborados
                    document.getElementById('divInstrucciones').innerHTML = ""; // Vaciar instrucciones
                    document.getElementById('h3-elaborados').style.display = "none"; // Ocultar título de elaborados
                    document.getElementById('elaboradosList').style.display = "none"; // Ocultar lista de elaborados


                }
            });
        }


        function modalIng(id) {
            // Obtener el modal y el fondo
            const modal = document.getElementById("ingModal");
            const backgroundOverlay = document.getElementById("backgroundOverlay");

            // Realizar la solicitud AJAX
            $.ajax({
                url: './../../controllers/getIngredient.php',
                type: 'GET',
                data: {
                    id: id
                },
                dataType: 'json',
                success: function(data) {
                    console.log("Respuesta de la API:", data); // Depuración para verificar la respuesta completa

                    // Verificar si los datos del ingrediente existen
                    if (!data || !data.ingrediente) {
                        alert("No se encontraron datos del ingrediente.");
                        return;
                    }
                    // Calcular el porcentaje de merma
                    const merma = (data.ingrediente.merma * 100).toFixed(2);
                    // Actualizar los elementos del modal con los datos recibidos
                    var nombre = document.getElementById('nombreIng-h3').innerHTML = data.ingrediente.nombre || "N/A";
                    console.log(nombre);
                    document.getElementById('imagenIng').src = data.ingrediente.imagen ? './../' + data.ingrediente.imagen : './../default-image.png';
                    document.getElementById('caducidadIng').innerHTML = `<strong>Expire in: </strong>${data.ingrediente.expira_dias || "N/A"} days`;
                    document.getElementById('mermaIng').innerHTML = `<strong>Decrease: </strong>${merma || "N/A"} %`;
                    document.getElementById('costIng').innerHTML = `<strong>Cost for (${data.ingrediente.unidad}): </strong>${data.ingrediente.coste || "N/A"} €`;
                    document.getElementById('saleIng').innerHTML = `<strong>Sale for (${data.ingrediente.unidad}): </strong>${data.ingrediente.venta || "N/A"} €`;

                    document.getElementById('localizacionIng').innerHTML = `<strong>Localization: </strong>${data.ingrediente.warehouse || "N/A"}`;

                    document.getElementById('empaquetadoIng').innerHTML = `<strong>Package: </strong>${data.ingrediente.empaquetado || "N/A"}`;
                    document.getElementById('alergenoIng').innerHTML = `<strong>Allergen: </strong>${data.ingrediente.alergeno || "N/A"}`;
                    document.getElementById('weightIng').innerHTML = `<strong>Weight for (${data.ingrediente.unidad}): </strong>${data.ingrediente.peso || "N/A"} kg`;
                    document.getElementById('clasificacion-ing').innerHTML = `<strong>Clasification: </strong>${data.ingrediente.clasificacion_ing || "N/A"}`;

                   
                    document.getElementById('shortDescriptionIng').innerHTML = `<strong>Short description: </strong>${data.ingrediente.descripcion_corta || "N/A"} `;

                    shortDescriptionIng
                    var divCantidad = document.getElementById('content-cantidad');

                    var cantidad = data.ingrediente.cantidades_tienda || "N/A";
                    if (cantidad != "N/A") {
                        var cantidadArray = cantidad.split(",");
                        var cantidadTienda = document.createElement('div');
                        cantidadTienda.innerHTML = `<strong> ${data.ingrediente.nombre_valores_tienda}</strong>`;
                        cantidadArray.forEach(element => {
                            var cantidad = element.split(":");
                            var p = document.createElement('p');
                            p.innerHTML = `<strong>${cantidad[0]}</strong> ${data.ingrediente.unidad}`;


                            // Apply the class for styling the square
                            p.classList.add('square-text');
                            cantidadTienda.appendChild(p);
                            divCantidad.appendChild(cantidadTienda);
                        });
                    }


                    // Mostrar el modal
                    modal.style.display = "block";
                    backgroundOverlay.style.display = "block";
                },
                error: function(xhr, status, error) {
                    console.error("Error en la solicitud AJAX:", status, error);
                    console.error("Respuesta del servidor:", xhr.responseText); // Depuración adicional
                    alert("Ocurrió un error al obtener los datos del ingrediente. Por favor, inténtalo más tarde.");
                }
            });

            // Cerrar el modal al hacer clic fuera del mismo
            window.onclick = function(event) {
                if (event.target == modal) {
                    closeModalIng();

                }
            };
        }

        // Cerrar el modal
        function closeModalIng() {
            const modal = document.getElementById("ingModal");
            const backgroundOverlay = document.getElementById("backgroundOverlay");
            document.getElementById('content-cantidad').replaceChildren();
            modal.style.display = "none";
            backgroundOverlay.style.display = "none";
        }



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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>



</html>