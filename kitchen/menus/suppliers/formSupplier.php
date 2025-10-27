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
    <!-- <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">-->
    <script src="https://kit.fontawesome.com/2e2cc992ca.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
     <!-- Custom styles for this template-->
    <link href="./../../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="./../../css/navs.css" rel="stylesheet">
    <link rel="stylesheet" href="suppliers.css">
</head>
<?php
 include './../../includes/session.php'; 
 include './../../includes/navs.php'; 
 insertarTopNav("./formOrder.php", "./../../svg/suppliers_Black.svg", "Place Order");
 insertarTopNav("./formSupplier.php", "./../../svg/suppliers_Black.svg", "Create");
 insertarTopNav("./index.php", "./../../svg/suppliers_Black.svg", "List");
?>

<div class="container-fluid text-center align-center">
    <h3><strong>New Supplier</strong></h3>
    <div class="border border-primary rounded row justify-content-center mx-5 mt-4">

        <form method="post" id="formSupplier" action="">
            <div class="form-group mt-5">
                <label for="nombre" style="cursor: pointer;">Supplier Name</label>
                <input type="text" id="nombre" placeholder="Enter supplier name">
            </div>
            <div class="form-group mt-5">
                <label for="numero" style="cursor: pointer;">Contact Number</label>
                <input type="text" id="numero" placeholder="Enter contact number">
            </div>
            <div class="form-group mt-5">
                <label for="correo" style="cursor: pointer;">Email Address</label>
                <input type="text" id="correo" placeholder="Enter email address">
            </div>
            <div class="form-group mt-5">
                <label for="direccion" style="cursor: pointer;">Address</label>
                <input type="text" id="direccion" placeholder="Enter address">
            </div>
            <div class="form-group mt-5">
            <button class="btn-primary text-white rounded-pill p-2 px-3">Create Supplier</button>
            </div>
        </form>
    </div>

</div>
<script src="./../../vendor/jquery/jquery.min.js"></script>
<script src="./../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="./../../vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="./../../js/sb-admin-2.min.js"></script>

<!-- Page level plugins -->
<script src="./../../vendor/datatables/jquery.dataTables.min.js"></script>
<script src="./../../vendor/datatables/dataTables.bootstrap4.min.js"></script>

<!-- Crear un nuevo proveedor por js -->
 <script src="./js/formSupplier.js"></script>

 <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>