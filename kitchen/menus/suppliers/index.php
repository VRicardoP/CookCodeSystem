<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Cook code</title>
    <link rel="icon" type="image/png" href="./../../img/logo.png">
    <script src="https://kit.fontawesome.com/2e2cc992ca.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link rel="stylesheet" href="suppliers.css">
    <link href="./../../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="./../../css/navs.css" rel="stylesheet">
</head>
<?php
    include './../../includes/session.php'; 
    include './../../includes/navs.php'; 
    // insertarTopNav("./scan/", "./../../svg/suppliers_Black.svg", "Scan Delivery Note");
    insertarTopNav("./formOrder.php", "./../../svg/suppliers_Black.svg", "Place Order");
    insertarTopNav("./formSupplier.php", "./../../svg/suppliers_Black.svg", "Create");
    insertarTopNav("./index.php", "./../../svg/suppliers_Black.svg", "List");
?>

<div class="container-fluid">
    <?php include 'suppliersList.php'; ?>
    <?php include 'listOrders.php'; ?>
</div>
<script src="js/listSuppliers.js"></script>
<!-- Bootstrap core JavaScript-->
<script src="./../../vendor/jquery/jquery.min.js"></script>
<script src="./../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Core plugin JavaScript-->
<script src="./../../vendor/jquery-easing/jquery.easing.min.js"></script>
<!-- Custom scripts for all pages-->
<script src="./../../js/sb-admin-2.min.js"></script>
