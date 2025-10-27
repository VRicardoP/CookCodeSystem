<?php

require __DIR__ . '/../../models/almacenIngredientes.php';
require_once __DIR__ . '/../../models/almacenIngredientesDao.php';

// Obtener los parámetros de la URL
$idIng = isset($_GET['id']) ? urldecode($_GET['id']) : 'N/A';
$productName = isset($_GET['productName']) ? urldecode($_GET['productName']) : 'Producto desconocido';
$imagenIngrediente = isset($_GET['img']) ? urldecode($_GET['img']) : 'no-image.png';
$cantidad = isset($_GET['productamount']) ? urldecode($_GET['productamount']) : 'N/A';
$fechaElab = isset($_GET['fechaElab']) ? urldecode($_GET['fechaElab']) : 'N/A';
$pesoPaquete = isset($_GET['pesoPaquete']) ? urldecode($_GET['pesoPaquete']) : 'N/A';

if ($fechaElab !== 'N/A') {
    $fechaObj = DateTime::createFromFormat('Y-m-d', $fechaElab);
    if ($fechaObj) {
        $fechaElab = $fechaObj->format('d/m/Y');  // Formato día/mes/año
    } else {
        $fechaElab = 'Formato de fecha inválido';
    }
}

$warehouse = isset($_GET['warehouse']) ? urldecode($_GET['warehouse']) : 'N/A';
$costCurrency = isset($_GET['costCurrency']) ? urldecode($_GET['costCurrency']) : 'N/A';
$saleCurrency = isset($_GET['saleCurrency']) ? urldecode($_GET['saleCurrency']) : 'N/A';
$salePrice = isset($_GET['salePrice']) ? urldecode($_GET['salePrice']) : 'N/A';
$costPrice = isset($_GET['costPrice']) ? urldecode($_GET['costPrice']) : 'N/A';


$stock_quantity = floatval($cantidad) ;


$ing = AlmacenIngredientesDao::select($idIng);

$estadoIng = $ing->getEstado();

$btnText = "";
switch ($estadoIng) {
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
    <title>Detalles del Ingrediente</title>
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

        .card .currency {
            color: #007bff;
            font-size: 16px;
        }

        .card .amount {
            font-size: 16px;
            color: #6c757d;
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
</head>

<body>

    <div class="card">
        <img src="<?php echo htmlspecialchars($imagenIngrediente); ?>" alt="Imagen del ingrediente">
        <h2><?php echo htmlspecialchars($productName); ?></h2>
        <p><strong>ID:</strong> <?php echo htmlspecialchars($idIng); ?></p>
        <p><strong>Product Quantity:</strong> <span class="amount"><?php echo htmlspecialchars($stock_quantity); ?></span></p>
        <p><strong>Peso package:</strong> <span class="amount"><?php echo htmlspecialchars($pesoPaquete); ?></span></p>
        <p><strong>Preparation Date:</strong> <?php echo htmlspecialchars($fechaElab); ?></p>



        
        <p><strong>Store:</strong> <?php echo htmlspecialchars($warehouse); ?></p>
        <p><strong>Sale price:</strong> <span class="price"><?php echo htmlspecialchars($salePrice); ?> <?php echo htmlspecialchars($saleCurrency); ?></span></p>
        <p><strong>Cost Price:</strong> <?php echo htmlspecialchars($costPrice); ?> <?php echo htmlspecialchars($costCurrency); ?></p>


        <p><strong>Estate:</strong> <span class="<?php echo htmlspecialchars($classEstate); ?>"><?php echo htmlspecialchars($estadoIng); ?></span> </p>
        <!-- Verifica que el idIng esté presente -->
        <?php if ($estadoIng !== 'Received'): ?>
            <button id="btnEnvio" class="btn-primary" onclick="registrarEnviado('<?= htmlspecialchars($idIng) ?>','<?= htmlspecialchars($estadoIng) ?>')"><?= htmlspecialchars($btnText) ?></button>
        <?php endif; ?>
    </div>

</body>

</html>

<script>
    function agregarProducto(data) {



        console.log("producto: " + JSON.stringify(data));
        // URL del archivo PHP que maneja la solicitud POST
        const url = 'http://localhost:8080/ecommerce/apiwoo/crearLoteIng.php';

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













    function registrarEnviado(id, estado) {
        // Validar que el id no sea vacío
        console.log(id)
        if (!id || id === 'N/A') {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'ID de receta no válido.'
            });
            return;
        }

        let dataToSend = {
            idIngrediente: id,
            estado: estado
        };

        if (estado == "Registered") {


            $.ajax({
                url: './../stock/registroEnviadoIng.php',
                type: 'POST',
                data: dataToSend,
                dataType: 'json',
                beforeSend: function() {
                    Swal.fire({
                        title: 'Sending...',
                        text: 'Marking the ingredient as shipped...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                },
                success: async function(response) {
                    if (response.success) {
                        Swal.close();
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Ingredient marked as shipped.',
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
                        text: 'Hubo un problema: ' + textStatus,
                        timer: 3000, // 3000 milisegundos = 3 segundos
                        showConfirmButton: false // Ocultar el botón de confirmación
                    });
                    console.log(textStatus)
                }
            });

        } else if (estado == "Sent") {



            $.ajax({
                url: './../stock/registroEnviadoIng.php',
                type: 'POST',
                data: dataToSend,
                dataType: 'json',
                beforeSend: function() {
                    Swal.fire({
                        title: 'Sending...',
                        text: 'Marking the ingredient as received...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                },
                success: async function(response) {
                    if (response.success) {
                        let qty = parseFloat(response.stock_quantity);

                        console.log("Response: " + response);


                        // Producto de ejemplo
                        const lote = {
                            sku: response.sku , // SKU único para el lote, basado en el SKU del producto principal
                            name: `${response.name} - Lote ${response.sku}`, // Nombre del lote
                          
                            description: `Lote específico del producto ${response.name}. Este lote tiene una cantidad disponible de ${response.stock_quantity} unidades.`,
                            short_description: `Lote de ${response.name}. Fecha de caducidad: ${response.fecha_cad}`,
                            stock_quantity: response.stock_quantity, // Cantidad disponible en el lote
                            manage_stock: true, // Este lote maneja su propio inventario
                            regular_price: ""+response.regular_price, // Precio regular (puede ajustarse por lote si es necesario)
                            sale_price: response.sale_price || null, // Precio en oferta (opcional)
                            attributes: [{
                                name: response.atr_name_tienda, // Nombre del atributo (por ejemplo, peso o tamaño)
                                option: response.option_cantidad // Valor específico (por ejemplo, 10kg, 20kg)
                            }],
                            image: {
                                src: 'http://localhost:8080/kitchen/img/ingredients/' + response.imagen // Imagen asociada al lote
                            },
                            parent_sku: response.parent_id, // sku del producto principal asociado a este lote
                            meta_data: [{
                                    key: 'fecha_elaboracion',
                                    value: response.fecha_elab // Fecha de elaboración específica del lote
                                },
                                {
                                    key: 'fecha_caducidad',
                                    value: response.fecha_cad // Fecha de caducidad específica del lote
                                },
                                {
                                    key: 'cost_price',
                                    value: response.coste_price // Precio de costo del lote
                                },
                                {
                                    key: 'type_unit',
                                    value: response.tipo_unidad // Unidad de medida
                                },
                               
                            ]
                        };


                        // Llamar a la función agregarProducto
                        await agregarProducto(lote)
                            .then(() => {
                                document.body.innerHTML += lote.name + " subido al e-commerce<br>";
                                console.log(lote.name + " subido al ecommerce");



                            })
                            .catch(error => {
                                console.error('Error al agregar el producto:', error);
                            });
                        // Cerrar el círculo de carga
                        Swal.close();

                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Ingredient marked as received.',
                            timer: 3000, // 3000 milisegundos = 3 segundos
                            showConfirmButton: false // Ocultar el botón de confirmación
                        });
                        //  location.reload();
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
                        text: 'Hubo un problema: ' + textStatus,
                        timer: 3000, // 3000 milisegundos = 3 segundos
                        showConfirmButton: false // Ocultar el botón de confirmación
                    });
                }
            });

        } else {

            let btn = document.getElementById('btnEnvio');
            btn.style.display = 'none';
        }



    }
</script>
<script src="./../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>