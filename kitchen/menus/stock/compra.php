<?php

require __DIR__ . '/../models/user.php';
require_once __DIR__ . '/../models/userDao.php';

require __DIR__ . '/../models/grupos.php';
require_once __DIR__ . '/../models/gruposDao.php';

require __DIR__ . '/../models/permisos.php';
require_once __DIR__ . '/../models/permisosDao.php';
require __DIR__ . '/../models/gruposPermisos.php';
require_once __DIR__ . '/../models/gruposPermisosDao.php';

require __DIR__ . '/../models/almacenElaboraciones.php';
require_once __DIR__ . '/../models/almacenElaboracionesDao.php';

require_once __DIR__ . '/../../DBConnection.php';  // Ajusta la ruta a donde tienes DBConnection.php

$userses = new User();
$grupo = new Grupo();
session_start();

if (isset($_SESSION['user_id'])) {

    $id = $_SESSION['user_id'];
    $userSession = UserDao::select($_SESSION['user_id']);
    $grupoSession = GruposDao::select($userSession->getGrupo_id());
} else {
    //No authenticated user
    header('Location: ./../login/login.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET["ped_id"])) {
        $id = $_GET["ped_id"];
        header("Location: printPedidos.php?ped_id=" . urlencode($id));
        exit();
    }
}

$pdo = DBConnection::connectDB();

if (!$pdo) {
    die("No se pudo conectar a la base de datos.");
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

    <!-- Custom fonts for this template-->
    <script src="https://kit.fontawesome.com/2e2cc992ca.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
    <link href=" https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css" rel="stylesheet" type="text/css">

    <link href="./../css/navs.css" rel="stylesheet">

    <style>
        .action_button button {
            width: 30px;
            height: 30px;
            margin-right: 2px;
            padding: 2px;
        }

        .action_button button:last-child {
            margin-right: 0px;
        }
    </style>

</head>

<body id="page-top">
    <?php include '../templates/navs.php'; ?>
    <?php insertarTopNav('stock.php', './../svg/orders_Black.svg', 'Enviado e-commerce'); ?>
    <?php insertarTopNav('estadoEnvioCompra.php', './../svg/orders_Black.svg', 'Envio compras'); ?>

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
        <!-- Main Content -->
        <div id="content">

            <div class="container-fluid">

                <div class="card shadow mb-4 ">
                    <div class="card-header">
                        <h6 class="m-0 font-weight-bold text-primary">Tracking production and delivery of products</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <form name="pedidosList" action="" method="get">
                                <table class="display" id="dataTableElab">
                                    <thead>
                                        <tr>
                                            <th>pedido id</th>
                                            <th>restaurante id</th>
                                            <th>total</th>
                                            <th>estado</th>
                                            <th>fecha pedido</th>
                                            <th>nombre cliente</th>
                                            <th>email cliente</th>
                                            <th>acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $queryPedidos = "SELECT * FROM pedidos_ecommerce ORDER BY id DESC";
                                        $stmt = $pdo->query($queryPedidos);
                                        $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                        if ($pedidos) {
                                            foreach ($pedidos as $rowPedidos) {
                                                echo '<tr id="row-pedidos-' . htmlspecialchars($rowPedidos["id"]) . '" >';

                                                echo '<td>' . htmlspecialchars($rowPedidos["pedido_id"]) . '</td>';
                                                echo '<td>' . htmlspecialchars($rowPedidos["id_restaurante"]) . '</td>';
                                                echo '<td>' . htmlspecialchars($rowPedidos["total"]) . '</td>';
                                                echo '<td>' . htmlspecialchars($rowPedidos["estado"]) . '</td>';
                                                echo '<td>' . htmlspecialchars($rowPedidos["fecha_pedido"]) . '</td>';
                                                echo '<td>' . htmlspecialchars($rowPedidos["nombre_cliente"]) . '</td>';
                                                echo '<td>' . htmlspecialchars($rowPedidos["email_cliente"]) . '</td>';

                                                echo "<td class='action_button'>
                                                    <button class='btn-primary rounded' type='button' name='ing_elab' id='ing_elab' value='" . htmlspecialchars($rowPedidos["id"]) . "'>
                                                        <img src='./../svg/ingredients.svg' alt='Ing' title='Ingredients'>
                                                    </button>
                                                    <button class='btn-primary rounded' type='submit' name='ped_id' id='ped_id' value='" . htmlspecialchars($rowPedidos["id"]) . "'>
                                                        <img src='./../svg/printing.svg' alt='Print' title='Print'>
                                                    </button>
                                                </td>";

                                                echo '</tr>';
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Contenedor donde se mostrarán los pedidos -->
                <div id="pedidos"></div>

            </div>

        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- End of Main Content -->

    <!-- End of Content Wrapper -->
    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
    <!-- Footer -->
    <footer class="sticky-footer bg-white">

    </footer>
    <!-- End of Footer -->

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script>
        $(document).ready(function() {
            // Hacer la solicitud AJAX al archivo PHP para obtener todos los pedidos
            $.ajax({
                url: 'http://localhost:8080/ecommerce/apiwoo/pedido.php',
                type: 'POST',
                data: {
                    todo: true
                }, // Envía la acción para obtener todos los pedidos
                success: function(response) {
                    // Procesar la respuesta que envía el archivo PHP
                    console.log('Pedidos obtenidos:', response);

                    try {
                        var pedidos = JSON.parse(response); // Convertir la respuesta en JSON

                        // Aquí procesamos y mostramos los detalles de cada pedido
                        pedidos.forEach(function(pedido) {
                            var html = '<div class="pedido">';
                            html += '<h3>Pedido ID: ' + pedido.id + ' - Número: ' + pedido.number + '</h3>';
                            html += '<p><strong>Estado:</strong> ' + pedido.status + '</p>';
                            html += '<p><strong>Total:</strong> ' + pedido.total + ' ' + pedido.currency + '</p>';
                            html += '<p><strong>Fecha de creación:</strong> ' + pedido.date_created + '</p>';
                            html += '<p><strong>Método de pago:</strong> ' + pedido.payment_method_title + '</p>';

                            // Información del cliente
                            html += '<h4>Información del Cliente:</h4>';
                            html += '<p><strong>Nombre:</strong> ' + pedido.billing.first_name + ' ' + pedido.billing.last_name + '</p>';

                            // Información de los productos
                            html += '<h4>Productos:</h4>';
                            pedido.line_items.forEach(function(item) {
                                html += '<p>Producto: ' + item.name + ' - Cantidad: ' + item.quantity + ' - Precio: ' + item.price + '</p>';

                                // Buscar la información del lote en los meta_data
                                item.meta_data.forEach(function(meta) {
                                    if (meta.key === '_informacion_lote') {
                                        // Verificar si meta.value es un array
                                        if (Array.isArray(meta.value)) {
                                            html += '<p><strong>Información del Lote:</strong><br>';
                                            meta.value.forEach(function(lote) {
                                                html += 'SKU: ' + lote.sku + '<br>';
                                                html += 'Nombre: ' + lote.nombre + '<br>';
                                                html += 'Cantidad Utilizada: ' + lote.cantidad_utilizada + '<br>';
                                                html += 'Fecha de Caducidad: ' + lote.fecha_caducidad + '<br><br>';
                                            });
                                            html += '</p>';
                                        } else {
                                            // En caso de que meta.value no sea un array (fallback para compatibilidad)
                                            html += '<p><strong>Información del Lote:</strong><br>' + meta.value + '</p>';
                                        }
                                    }
                                });

                            });

                            html += '</div><hr>';

                            // Agregar los detalles del pedido al contenedor con ID #pedidos
                            $('#pedidos').append(html);

                            // Enviar el pedido a la base de datos
                            $.ajax({
                                url: 'guardarPedidosBd.php', // Cambia esto según tu estructura
                                type: 'POST',
                                data: {
                                    pedido: JSON.stringify(pedido) // Enviar el pedido como una cadena JSON
                                },
                                success: function(saveResponse) {
                                    console.log('Pedido guardado en la base de datos:', saveResponse);
                                },
                                error: function(jqXHR, textStatus, errorThrown) {
                                    console.error('Error al guardar el pedido en la base de datos:', textStatus, errorThrown);
                                }
                            });
                        });
                    } catch (e) {
                        console.error('Error al parsear el JSON:', e);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('Error al obtener los pedidos:', textStatus, errorThrown);
                }
            });
        });
    </script>

    <!-- Bootstrap core JavaScript-->
    <script src="./../vendor/jquery/jquery.min.js"></script>
    <script src="./../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="./../vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="./../js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="./../vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="./../vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="./../js/demo/datatables-demo.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>
