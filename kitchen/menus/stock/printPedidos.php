<?php

require __DIR__ . '/../../models/almacenElaboraciones.php';
require_once __DIR__ . '/../../models/almacenElaboracionesDao.php';

require __DIR__ . '/../../models/almacenIngredientes.php';
require_once __DIR__ . '/../../models/almacenIngredientesDao.php';

$f = "visit.php";
if (!file_exists($f)) {
    touch($f);
    $handle = fopen($f, "w");
    fwrite($handle, 0);
    fclose($handle);
}

include('./../../menus/qrcode/generator/libs/phpqrcode/qrlib.php');

$logout = "./../login/logout.php";

require_once __DIR__ . '/../../DBConnection.php';

// Conexión a la BD empresa_usuarios con PDO
try {
    $connEmpresa = DBConnection::connectDB();
    if (!$connEmpresa) {
        throw new Exception("Error en la conexión a empresa_usuarios");
    }
} catch (Exception $e) {
    die("Connection failed: " . $e->getMessage());
}

// Consulta empresa
$queryEmpresa = "SELECT * FROM `empresa` WHERE ID = 1";
$stmtEmpresa = $connEmpresa->prepare($queryEmpresa);
$stmtEmpresa->execute();
$empresa = $stmtEmpresa->fetch(PDO::FETCH_ASSOC);

if ($empresa) {
    $nombreEmpresa = $empresa["nombre"];
    $direccionEmpresa = $empresa["direccion"];
    $codigoPostalEmpresa = $empresa["codigo_postal"];
    $ciudadEmpresa = $empresa["ciudad"];
    $paisEmpresa = $empresa["pais"];
    $imagenEmpresa = $empresa["imagen"];
} else {
    // Valores por defecto o manejo de error
    $nombreEmpresa = $direccionEmpresa = $codigoPostalEmpresa = $ciudadEmpresa = $paisEmpresa = $imagenEmpresa = '#######';
}

// Conexión a la BD kitchentag con PDO
try {
    $conn = DBConnection::connectDB();
    if (!$conn) {
        throw new Exception("Error en la conexión a kitchentag");
    }
} catch (Exception $e) {
    die("Connection failed: " . $e->getMessage());
}

$tipo = "";

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["ped_id"])) {
    $id_qr_order = (int) $_GET['ped_id']; // casteo a entero para seguridad

    $queryPedidos = "SELECT * FROM `pedidos_ecommerce` WHERE `id` = :id";
    $stmtPedidos = $conn->prepare($queryPedidos);
    $stmtPedidos->execute([':id' => $id_qr_order]);

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
    } else {
        // Manejar caso pedido no encontrado
        $id_pedido = $id_restaurante = $fecha_pedido = $total = $nombre_cliente = $email_cliente = $telefono_cliente = $direccion_cliente = null;
    }

    $tempDir = "temp/";
    $fileName = $id_qr_order;
    QRcode::png(
        'http://192.168.1.147:8080/kitchen/menus/stock/datosQrPedido.php?id=' . $id_qr_order,
        $tempDir . $fileName . '.png',
        QR_ECLEVEL_M,
        3,
        4
    );
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />

    <title>Print etiqueta</title>

    <!-- Custom fonts for this template-->
    <script src="https://kit.fontawesome.com/2e2cc992ca.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet" />
    <link href="./../../css/sb-admin-2.min.css" rel="stylesheet" />
    <link href="./../../css/navs.css" rel="stylesheet" />
    <link href="./../../css/elaborations/print.css" rel="stylesheet" />

</head>

<body id="page-top">
    <?php include './../../includes/session.php'; ?>
    <?php include './../../includes/navs.php'; ?>
    <?php insertarTopNav('./', './../../svg/orders_Black.svg', 'Envio e-commerce'); ?>
    <?php insertarTopNav('estadoEnvioCompra.php', './../../svg/orders_Black.svg', 'Envio restaurant'); ?>

    <div id="content-wrapper" class="d-flex flex-column">

        <div class="content">
            <div class="content_tag ">

                <div class="left-part ">
                    <div>
                        <!-- Imagen de empresa -->
                        <img src="./../../img/cookcodeblack.jpg" style="width: 230px; height:80px" />
                    </div>

                    <div class="div-direction-row">
                        <!-- Dirección de empresa -->
                        <div class="address">
                            <span><?= htmlspecialchars($nombreEmpresa) ?></span><br />
                            <span><?= htmlspecialchars($direccionEmpresa) ?></span><br />
                            <span>
                                <?= htmlspecialchars($codigoPostalEmpresa) ?>
                                <?= htmlspecialchars($ciudadEmpresa) ?>
                                <?= htmlspecialchars($paisEmpresa) ?>
                            </span>
                        </div>
                    </div>
                    <hr />
                    <div class="div-name">
                        <h5>
                            <?= isset($id_pedido) ? "Pedido nº: " . htmlspecialchars($id_pedido) : '########' ?>
                        </h5>
                    </div>
                </div>

                <div class="right-part ">
                    <div class="table-container">
                        <div class="qrframe" id="qr" style="border:2px solid black;">
                            <img src="temp/<?= isset($id_qr_order) ? htmlspecialchars($id_qr_order) : '' ?>.png" style="width:100%;" />
                        </div>

                        <div class="exp_lote">
                            <!-- Puedes agregar más info aquí si quieres -->
                        </div>
                    </div>
                </div>

            </div>

            <button class="btn-primary" onclick="imprimir()">
                <img src="./../../svg/print.svg" alt="Print" />
            </button>
        </div>
    </div>

    <script>
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

</body>

</html>
