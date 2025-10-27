<?php
require __DIR__ . '/../../models/almacenElaboraciones.php';
require_once __DIR__ . '/../../models/almacenElaboracionesDao.php';
require_once __DIR__ . '/../../DBConnection.php'; 

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["ped_id"])) {
    $id = $_GET["ped_id"];
    header("Location: printPedidos.php?ped_id=" . urlencode($id));
    exit();
}

try {
    $conn = DBConnection::connectDB();
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
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
    <link href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css" rel="stylesheet" type="text/css">
    <link href="./../../css/navs.css" rel="stylesheet">
    <link href="./../../css/tables.css" rel="stylesheet">
    <style>
        .estado-registrado {
            background-color: #f39c12;
            color: white;
            font-weight: bold;
            text-align: center;
        }
        .estado-enviado {
            background-color: #3498db;
            color: white;
            font-weight: bold;
            text-align: center;
        }
        .estado-recibido {
            background-color: #2ecc71;
            color: white;
            font-weight: bold;
            text-align: center;
        }
        .estado-desconocido {
            background-color: #e74c3c;
            color: white;
            font-weight: bold;
            text-align: center;
        }
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
    <?php include './../../includes/session.php'; ?>
    <?php include './../../includes/navs.php'; ?>
    <?php insertarTopNav('./', './../../svg/orders_Black.svg', 'E-commerce delivery'); ?>

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
        <!-- Main Content -->
        <div id="content">
            <div class="container-fluid">
                <div class="card shadow mb-4">
                    <div class="card-header">
                        <h6 class="m-0 font-weight-bold text-primary">Tracking production and delivery of products</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <form name="pedidosList" action="" method="get">
                                <table class="display" id="dataTableElab">
                                    <thead>
                                        <tr>
                                            <th>order id</th>
                                            <th>restaurant id</th>
                                            <th>total</th>
                                            <th>order date</th>
                                            <th>client name</th>
                                            <th>client email</th>
                                            <th>Action</th>
                                            <th>State</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $queryPedidos = "SELECT * FROM `pedidos_ecommerce` ORDER BY `id` DESC";
                                        $stmtPedidos = $conn->query($queryPedidos);
                                        
                                        while ($rowPedidos = $stmtPedidos->fetch(PDO::FETCH_ASSOC)) {
                                            echo '<tr id="row-pedidos-' . htmlspecialchars($rowPedidos["id"]) . '">';
                                            echo '<td>' . htmlspecialchars($rowPedidos["pedido_id"]) . '</td>';
                                            echo '<td>' . htmlspecialchars($rowPedidos["id_restaurante"]) . '</td>';
                                            echo '<td>' . htmlspecialchars($rowPedidos["total"]) . '</td>';
                                            echo '<td>' . htmlspecialchars($rowPedidos["fecha_pedido"]) . '</td>';
                                            echo '<td>' . htmlspecialchars($rowPedidos["nombre_cliente"]) . '</td>';
                                            echo '<td>' . htmlspecialchars($rowPedidos["email_cliente"]) . '</td>';
                                            
                                            echo '<td class="action_button">
                                                <button class="btn-primary rounded" type="submit" name="ped_id" id="ped_id" value="' . htmlspecialchars($rowPedidos["id"]) . '">
                                                    <img src="./../../svg/printing.svg" alt="Print" title="Print">
                                                </button>
                                            </td>';
                                            
                                            // Status with CSS class
                                            $statusClasses = [
                                                'Registered' => 'estado-registrado',
                                                'Sent' => 'estado-enviado',
                                                'Received' => 'estado-recibido'
                                            ];
                                            $statusClass = $statusClasses[$rowPedidos["estado_envio"]] ?? 'estado-desconocido';
                                            echo '<td class="' . $statusClass . '">' . htmlspecialchars($rowPedidos["estado_envio"]) . '</td>';
                                            
                                            echo '</tr>';
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Footer -->
    <footer class="sticky-footer bg-white"></footer>

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