<?php

require __DIR__ . '/../../models/almacenElaboraciones.php';
require_once __DIR__ . '/../../models/almacenElaboracionesDao.php';
require_once __DIR__ . '/../../DBConnection.php';




if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET["ing_elab"])) {
        $id = $_GET["ing_elab"];
        header("Location: ./../elaborations/listIngr.php?ing_elab=" . urlencode($id));
        exit();
    } elseif (isset($_GET["edit_elab"])) {
        $id = $_GET["edit_elab"];
        header("Location: ./../elaborations/formElaborado.php?edit_elab=" . urlencode($id));
        exit();
    } elseif (isset($_GET["edit_ing"])) {
        $id = $_GET["edit_ing"];
        header("Location: ./../elaborations/formIngrediente.php?edit_ing=" . urlencode($id));
        exit();
    } elseif (isset($_GET["elab"])) {
        $id = $_GET["elab"];
        header("Location: ./../elaborations/printElaborations.php?elab=" . urlencode($id));
        exit();
    } elseif (isset($_GET["tag_ing"])) {
        $id = $_GET["tag_ing"];
        header("Location: ./../elaborations/printElaborations.php?tag_ing=" . urlencode($id));
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

    <link href="./../../css/navs.css" rel="stylesheet">
    <link href="./../../css/tables.css" rel="stylesheet">
    <style>
        .estado-registrado {

            background-color: #f39c12;
            /* Color naranja */
            color: white;
            font-weight: bold;
            text-align: center;
        }

        .estado-enviado {
            background-color: #3498db;
            /* Color azul */
            color: white;
            font-weight: bold;
            text-align: center;
        }

        .estado-recibido {
            background-color: #2ecc71;
            /* Color verde */
            color: white;
            font-weight: bold;
            text-align: center;
        }

        .estado-desconocido {
            background-color: #e74c3c;
            /* Color rojo (para casos no esperados) */
            color: white;
            font-weight: bold;
            text-align: center;
        }
    </style>

</head>

<body id="page-top">
    <?php include './../../includes/session.php'; ?>
    <?php include './../../includes/navs.php'; ?>
    <?php //insertarTopNav('compra.php', './../svg/orders_Black.svg', 'Compras'); 
    ?>
    <?php insertarTopNav('estadoEnvioCompra.php', './../../svg/orders_Black.svg', 'Restaurant delivery'); ?>
    <?php
   

try {
    $conn = DBConnection::connectDB();
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
    ?>

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
        <!-- Main Content -->
        <?php include 'stock.php'; ?>
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