<?php
$link = mysqli_connect('localhost', 'root', '', 'kitchentag');
$requestProv = 'SELECT * FROM `proveedores`';

$resultProv = mysqli_query($link, $requestProv);

$proveedores = [];

if($resultProv){
    while($row = mysqli_fetch_assoc($resultProv)){
        $proveedores[] = $row;
    }
}

$requestProd = 'SELECT * FROM `ingredients`';

$resultProd = mysqli_query($link, $requestProd);

$productos = [];

if($resultProd){
    while($row = mysqli_fetch_assoc($resultProd)){
        $productos[] = $row;
    }
}

$link->close();

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
    <h3><strong>Place Order</strong></h3>
    <div class="border border-primary rounded row justify-content-center mx-5 mt-4">

        <form method="post" id="formSupplier" action="">
            <div class="form-group mt-5">
                <label for="nombre" style="cursor: pointer;">Supplier</label>
                <select id="proveedor" style="width: 200px; border-radius: 10px; border: 2px solid green; text-align: center; padding: 2px;">
                <option value="0">--Select a supplier--</option>
                <?php
                foreach($proveedores as $proveedor){
                    echo '<option value="'.$proveedor['id'].'">'.$proveedor['nombre'].'</option>';
                }
                ?>
                </select>
            </div>
            <div class="form-group mt-5">
                <label for="producto" style="cursor: pointer;">Product</label>
                <select id="producto" style="width: 200px; border-radius: 10px; border: 2px solid green; text-align: center; padding: 2px;">
                <option value="0">--Select a product--</option>
                <?php
                foreach($productos as $producto){
                    echo '<option value="'.$producto['ID'].'">'.$producto['fName'].'</option>';
                }
                ?>
                </select>
            </div>
            <div class="form-group mt-5">
                <label for="cantidad" style="cursor: pointer;">Amount</label>
                <input type="number" id="cantidad" placeholder="0" style="width: 200px; border-radius: 10px; border: 2px solid green; text-align: center; padding: 2px;">
            </div>
            <div class="form-group mt-5">
                <label for="tipo_cantidad" style="cursor: pointer;">Package amount</label>
                <select id="tipo_cantidad" style="width: 200px; border-radius: 10px; border: 2px solid green; text-align: center; padding: 2px;">
                </select>
            </div>
            <div class="form-group mt-5">
                <label for="tipo_pago" style="cursor: pointer;">Payment type</label>
                <select id="tipo_pago" style="width: 200px; border-radius: 10px; border: 2px solid green; text-align: center; padding: 2px;">
                    <option value="efectivo">Cash</option>
                    <option value="tarjeta">Card</option>
                    <option value="transferencia">Transfer</option>
                </select>
            </div>
            <div class="form-group mt-5">
                <label for="tiempo_pago" style="cursor: pointer;">Payement time</label>
                <select id="tiempo_pago" style="width: 200px; border-radius: 10px; border: 2px solid green; text-align: center; padding: 2px;">
                    <option value="inmediato">Now</option>
                    <option value="30_dias">30 Days</option>
                    <option value="60_dias">60 Days</option>
                    <option value="90_dias">90 Days</option>
                </select>
            </div>
            <div class="form-group mt-5">
                <button class="btn-primary text-white rounded-pill p-2 px-3">Place Order</button>
            </div>
        </form>
    </div>
</div>

<script src="./js/formOrder.js"></script>
<script>
    mostrarPackageAmount(<?= json_encode($productos) ?>);
</script>