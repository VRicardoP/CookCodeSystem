<?php

require __DIR__ . '/../../models/almacenElaboraciones.php';
require_once __DIR__ . '/../../models/almacenElaboracionesDao.php';
// Obtener los parámetros de la URL

$idReceta = isset($_GET['id']) ? urldecode($_GET['id']) : 'N/A';
$nombreReceta = isset($_GET['Na']) ? urldecode($_GET['Na']) : 'Desconocido';
$productAmount = isset($_GET['am']) ? urldecode($_GET['am']) : 'N/A';
$fechaElab = isset($_GET['fE']) ? urldecode($_GET['fE']) : 'N/A';
if ($fechaElab !== 'N/A') {
    $fechaObj = DateTime::createFromFormat('Y-m-d', $fechaElab);
    if ($fechaObj) {
        $fechaElab = $fechaObj->format('d/m/Y');  // Formato día/mes/año
    } else {
        $fechaElab = 'Formato de fecha inválido';
    }
}

$warehouse = isset($_GET['hou']) ? urldecode($_GET['hou']) : 'N/A';
$salePrice = isset($_GET['saP']) ? urldecode($_GET['saP']) : 'N/A';
$costPrice = isset($_GET['coP']) ? urldecode($_GET['coP']) : 'N/A';
$numRaciones = isset($_GET['numRaciones']) ? urldecode($_GET['numRaciones']) : 'N/A';
$imagenReceta = isset($_GET['img']) ? urldecode($_GET['img']) : 'no-image.png';
$listaIng = isset($_GET['ings']) ? urldecode($_GET['ings']) : 'Ninguno';
$instrucciones = isset($_GET['instruc']) ? urldecode($_GET['instruc']) : 'N/A';

$elaborado = AlmacenElaboracionesDao::select($idReceta);

$estadoElaborado = $elaborado->getEstado();

$btnText = "";
switch ($estadoElaborado) {
    case 'Registered':
        $btnText = "Sent";
        $classEstate = "estado-registrado";
        break;
    case 'Sent':
        $btnText = "Received";
        $classEstate = "estado-enviado";
        break;

    case 'Received':
        $btnText = "";
        $classEstate = "estado-recibido";
        break;


    default:
        $btnText = "";
        $classEstate = "estado-desconocido";
        break;
}


?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de la Receta</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }

        .card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            max-width: 400px;
            margin: 0 auto;
        }

        .card img {
            max-width: 100%;
            border-radius: 8px;
        }

        .card h2 {
            margin-top: 0;
            font-size: 24px;
            color: #333;
        }

        .card p {
            font-size: 16px;
            color: #555;
        }

        .card .price {
            color: #28a745;
            font-weight: bold;
            font-size: 18px;
        }

        .card .raciones {
            color: #007bff;
            font-size: 16px;
        }

        button {
            background-color: #28a745;
            width: 100%;
            height: 50px;
        }

        .estado-registrado {
            background-color: #f39c12;
            /* Naranja */
            color: white;
            font-weight: bold;
            text-align: center;
        }

        .estado-enviado {
            background-color: #3498db;
            /* Azul */
            color: white;
            font-weight: bold;
            text-align: center;
        }

        .estado-recibido {
            background-color: #2ecc71;
            /* Verde */
            color: white;
            font-weight: bold;
            text-align: center;
        }

        .estado-desconocido {
            background-color: #e74c3c;
            /* Rojo para el estado desconocido */
            color: white;
            font-weight: bold;
            text-align: center;
        }
    </style>
    <!-- Cargar jQuery y SweetAlert -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Custom fonts for this template-->
    <script src="https://kit.fontawesome.com/2e2cc992ca.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
</head>

<body>

    <div class="card">
        <img src="<?php echo htmlspecialchars($imagenReceta); ?>" alt="Imagen de la receta">
        <h2><?php echo htmlspecialchars($nombreReceta); ?></h2>
        <p><strong>ID:</strong> <?php echo htmlspecialchars($idReceta); ?></p>
        <p><strong>Preparation Date:</strong> <?php echo htmlspecialchars($fechaElab); ?></p>
        <p><strong>Store:</strong> <?php echo htmlspecialchars($warehouse); ?></p>
        <p><strong>Product Quantity:</strong> <?php echo htmlspecialchars($productAmount * $numRaciones); ?></p>
        <p><strong>Sale price:</strong> <span class="price"><?php echo htmlspecialchars($salePrice); ?> €</span></p>
        <p><strong>Cost Price:</strong> <?php echo htmlspecialchars($costPrice); ?> €</p>
        <p><strong>Number of Servings:</strong> <span class="raciones"><?php echo htmlspecialchars($numRaciones); ?></span></p>
        <p><strong>Ingredients:</strong> <?php echo htmlspecialchars($listaIng); ?></p>
        <p><strong>Instructions:</strong> <?php echo htmlspecialchars($instrucciones); ?></p>
        <p><strong>Estate:</strong> <span class="<?php echo htmlspecialchars($classEstate); ?>"><?php echo htmlspecialchars($estadoElaborado); ?></span> </p>
        <!-- Verifica que el idReceta esté presente -->
        <?php if ($estadoElaborado !== 'Received'): ?>
            <button class="btn-primary " id="btnEnvio" onclick="registrarEnviado('<?= htmlspecialchars($idReceta) ?>','<?= htmlspecialchars($estadoElaborado) ?>')"><?= htmlspecialchars($btnText) ?></button>
        <?php endif; ?>
    </div>

</body>

</html>


<script type="module">

      import { BASE_URL } from './config.js';


    function agregarProducto(data) {
        // URL del archivo PHP que maneja la solicitud POST
        const url = BASE_URL + 'ecommerce/apiwoo/crearLoteElab.php';

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
                console.log("Producto de lote: " + data); // Imprimir la respuesta
            })
            .catch(error => {
                console.error('Error al agregar el producto:', error);
                throw error; // Lanzar el error para que pueda ser manejado fuera de la función
            });
    }


    function registrarEnviado(id, estado) {

        // Validar que el id no sea vacío
        if (!id || id === 'N/A') {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'ID de receta no válido.'
            });
            return;
        }


        let dataToSend = {
            idElaborado: id,
            estado: estado
        };


        if (estado == "Registered") {


            $.ajax({
                url: './../stock/registroEnviado.php',
                type: 'POST',
                data: JSON.stringify(dataToSend),
                contentType: 'application/json',
                dataType: 'json',
                beforeSend: function() {
                    Swal.fire({
                        title: 'Sending...',
                        text: 'Marking the dish as sent...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                },
                success: async function(response) {
                    console.log(response); // Verifica lo que devuelve el servidor
                    if (response.success) {
                        Swal.close();
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Preparation marked as sent.',
                            timer: 3000, // 3000 milisegundos = 3 segundos
                            showConfirmButton: false // Ocultar el botón de confirmación
                        });
                        location.reload();
                    } else {
                        Swal.close();
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Could not register as sent. try again.',
                            timer: 3000, // 3000 milisegundos = 3 segundos
                            showConfirmButton: false // Ocultar el botón de confirmación
                        });
                    }

                },
                error: function(jqXHR, textStatus, errorThrown) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'There was a problem: ' + textStatus,
                        timer: 3000, // 3000 milisegundos = 3 segundos
                        showConfirmButton: false // Ocultar el botón de confirmación
                    });
                },

            });

        } else if (estado == "Sent") {

            console.log("dataToSend: " + JSON.stringify(dataToSend));

            $.ajax({
                url: './../stock/registroEnviado.php',
                type: 'POST',
                data: JSON.stringify(dataToSend),
                contentType: 'application/json',
                dataType: 'json',
                beforeSend: function() {
                    Swal.fire({
                        title: 'Sending...',
                        text: 'Marking the dish as received...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                },
                success: async function(response) {
                    console.log(response); // Verifica lo que devuelve el servidor
                    if (response.success) {
                        let idCategoria = 0;
                        if (response.tipo_product == "Elaborado") {
                            idCategoria = 26;
                        } else if (response.tipo_product == "Pre-Elaborado") {
                            idCategoria = 41;
                        }

                        // Producto de ejemplo
                        const producto = {
                            name: response.name,
                            type: 'simple',
                            regular_price: response.cost_price,
                            description: 'Descripción completa del producto.',
                            short_description: response.listaIng,
                            categories: [{
                                id:  idCategoria
                            }],
                            image: {
                                src: BASE_URL + 'kitchen/img/recipes/' + response.imagen
                            },
                            parent_sku: response.parent_id,
                            sku: response.sku,
                            manage_stock: true,
                            stock_quantity: response.stock_quantity,
                            meta_data: [{
                                    key: 'fecha_elaboracion',
                                    value: response.fecha_elab
                                },
                                {
                                    key: 'fecha_caducidad',
                                    value: response.fecha_cad
                                },
                                {
                                    key: 'cost_price',
                                    value: response.coste_price
                                },
                                {
                                    key: 'type_unit',
                                    value: response.tipo_unidad
                                } ,
                                {
                                    key: 'localizacion',
                                    value: response.localizacion
                                } ,
                                {
                                    key: 'alergeno',
                                    value: response.alergeno
                                } ,
                                {
                                    key: 'peso',
                                    value: response.peso
                                } ,
                               
                            ]
                        };
  
                        // Llamar a la función agregarProducto

                        await agregarProducto(producto)
                            .then(() => {


                                document.body.innerHTML += producto.name + " subido al e-commerce<br>";
                                console.log(producto.name + " subido al ecommerce");
                            })
                            .catch(error => {
                                console.error('Error al agregar el producto:', error);
                            });

                        Swal.close();
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Preparation marked as received.',
                            timer: 3000, // 3000 milisegundos = 3 segundos
                            showConfirmButton: false // Ocultar el botón de confirmación
                        });
                        location.reload();
                    } else {
                        Swal.close();
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Could not register as received. try again.',
                            timer: 3000, // 3000 milisegundos = 3 segundos
                            showConfirmButton: false // Ocultar el botón de confirmación
                        });
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'There was a problem: ' + textStatus,
                        timer: 3000, // 3000 milisegundos = 3 segundos
                        showConfirmButton: false // Ocultar el botón de confirmación
                    });
                },

            });

        }


    }
</script>
<script src="./../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>