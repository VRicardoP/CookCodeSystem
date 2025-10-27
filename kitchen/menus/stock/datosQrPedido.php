<?php

require_once __DIR__ . '/../../models/almacenElaboraciones.php';
require_once __DIR__ . '/../../models/almacenElaboracionesDao.php';
require_once __DIR__ . '/../../DBConnection.php';  

// Obtener los parámetros de la URL
$id = isset($_GET['id']) ? urldecode($_GET['id']) : 'N/A';
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

// Conectar usando PDO con tu clase DBConnection
$conn = DBConnection::connectDB();
if (!$conn) {
    die("Error: No se pudo conectar a la base de datos.");
}

// Consulta del pedido
$queryPedidos = "SELECT * FROM pedidos_ecommerce WHERE id = :id";
$stmtPedidos = $conn->prepare($queryPedidos);
$stmtPedidos->bindParam(':id', $id, PDO::PARAM_INT);
$stmtPedidos->execute();

$pedido = $stmtPedidos->fetch(PDO::FETCH_ASSOC);

if ($pedido) {
    $id_pedido = $pedido['pedido_id'];
    $id_restaurante = $pedido['id_restaurante'];
    $fecha_pedido = $pedido['fecha_pedido'];
    $total = $pedido['total'];
    $nombre_cliente = $pedido['nombre_cliente'];
    $email_cliente = $pedido['email_cliente'];
    $telefono_cliente = $pedido['telefono_cliente'];
    $direccion_cliente = $pedido['direccion_cliente'];
    $estado_envio = $pedido['estado_envio'];
} else {
    die("Pedido no encontrado.");
}

// Consulta de productos del pedido
$queryProductos = "SELECT * FROM productos_pedido WHERE pedido_id = :pedido_id";
$stmtProductos = $conn->prepare($queryProductos);
$stmtProductos->bindParam(':pedido_id', $id_pedido, PDO::PARAM_INT);
$stmtProductos->execute();

$productos = $stmtProductos->fetchAll(PDO::FETCH_ASSOC);

// Botón y clase según estado
$btnText = "";
switch ($estado_envio) {
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
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Detalles del pedido</title>
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
            border: none;
            color: white;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
        }

        .estado-registrado {
            background-color: #f39c12;
            color: white;
            font-weight: bold;
            text-align: center;
            padding: 5px;
            border-radius: 5px;
        }

        .estado-enviado {
            background-color: #3498db;
            color: white;
            font-weight: bold;
            text-align: center;
            padding: 5px;
            border-radius: 5px;
        }

        .estado-recibido {
            background-color: #2ecc71;
            color: white;
            font-weight: bold;
            text-align: center;
            padding: 5px;
            border-radius: 5px;
        }

        .estado-desconocido {
            background-color: #e74c3c;
            color: white;
            font-weight: bold;
            text-align: center;
            padding: 5px;
            border-radius: 5px;
        }
    </style>

    <!-- Cargar jQuery y SweetAlert -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Custom fonts -->
    <script src="https://kit.fontawesome.com/2e2cc992ca.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet" />
</head>

<body>
    <div class="card">
        <p><strong>ID pedido:</strong> <?php echo htmlspecialchars($id_pedido); ?></p>
        <p><strong>ID restaurante:</strong> <?php echo htmlspecialchars($id_restaurante); ?></p>
        <p><strong>Total:</strong> <?php echo htmlspecialchars($total); ?> €</p>
        <p><strong>Nombre cliente:</strong> <?php echo htmlspecialchars($nombre_cliente); ?></p>
        <p><strong>Email cliente:</strong> <?php echo htmlspecialchars($email_cliente); ?></p>
        <p><strong>Teléfono cliente:</strong> <span class="price"><?php echo htmlspecialchars($telefono_cliente); ?></span></p>
        <p><strong>Dirección cliente:</strong> <?php echo htmlspecialchars($direccion_cliente); ?></p>
        <p><strong>Estado:</strong> <span class="<?php echo htmlspecialchars($classEstate); ?>"><?php echo htmlspecialchars($estado_envio); ?></span></p>
        <hr>
        <?php if (!empty($productos)) : ?>
            <?php foreach ($productos as $producto) : ?>
                <p><strong>Sku lote:</strong> <?php echo htmlspecialchars($producto['sku_lote']); ?></p>
                <p><strong>Nombre:</strong> <?php echo htmlspecialchars($producto['nombre_producto']); ?></p>
                <p><strong>Cantidad:</strong> <?php echo htmlspecialchars($producto['cantidad_lote']); ?></p>
                <p><strong>Caducidad:</strong> <?php echo htmlspecialchars($producto['fecha_caducidad']); ?></p>
                <hr>
            <?php endforeach; ?>
        <?php else : ?>
            <p>No hay productos disponibles.</p>
        <?php endif; ?>

        <?php if ($estado_envio !== 'Received' && !empty($btnText)) : ?>
            <button id="btn" onclick="registrarEnviado('<?php echo htmlspecialchars($id); ?>','<?php echo htmlspecialchars($estado_envio); ?>')"><?php echo htmlspecialchars($btnText); ?></button>
        <?php endif; ?>
    </div>

    <script>
        function registrarEnviado(id, estado) {
            if (!id || id === 'N/A') {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'ID de pedido no válido.'
                });
                return;
            }

            let dataToSend = {
                idPedido: id,
                estado: estado
            };

            if (estado == "Registered") {
                $.ajax({
                    url: './../stock/registroPedido.php',
                    type: 'POST',
                    data: dataToSend,
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
                    success: function(response) {
                        if (response.success) {
                            Swal.close();
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: 'Preparation marked as sent.',
                                timer: 3000,
                                showConfirmButton: false
                            });
                            location.reload();
                        } else {
                            Swal.close();
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Could not register as sent. Try again.',
                                timer: 3000,
                                showConfirmButton: false
                            });
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'There was a problem: ' + textStatus,
                            timer: 3000,
                            showConfirmButton: false
                        });
                    },
                });
            } else if (estado == "Sent") {
                $.ajax({
                    url: './../stock/registroPedido.php',
                    type: 'POST',
                    data: dataToSend,
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
                    success: function(response) {
                        if (response.success) {
                            Swal.close();
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: 'Preparation marked as sent.',
                                timer: 3000,
                                showConfirmButton: false
                            });
                        } else {
                            Swal.close();
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Could not register as sent. Try again.',
                                timer: 3000,
                                showConfirmButton: false
                            });
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'There was a problem: ' + textStatus,
                            timer: 3000,
                            showConfirmButton: false
                        });
                    },
                });

                // Solicitud AJAX para marcar recibido
                $.ajax({
                    url: 'recibidoRestaurant.php',
                    type: 'POST',
                    data: { id: id },
                    dataType: 'json',
                    beforeSend: function() {
                        Swal.fire({
                            title: 'Sending...',
                            text: 'Marking the dish as recibido...',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                    },
                    success: function(response) {
                        Swal.close();
                        if (response.pedido && response.producto) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: 'Order and product marked as received',
                                timer: 3000,
                                showConfirmButton: false
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Order and product not marked as received',
                                timer: 3000,
                                showConfirmButton: false
                            });
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'There was a problem: ' + textStatus,
                            timer: 3000,
                            showConfirmButton: false
                        });
                    }
                });
            }
        }
    </script>
</body>

</html>
