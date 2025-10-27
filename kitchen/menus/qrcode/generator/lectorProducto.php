<?php

	//$subject =  $_POST['subject'];

	$fName =  $_GET['productName'];
	$packaging = $_GET['packaging'];
	$productamount = $_GET['productamount'];
 	$fechaElab=$_GET['fechaElab'];
	$fechaCad=$_GET['fechaCad'];
	$warehouse=$_GET['warehouse'];
	$costCurrency=$_GET['costCurrency'];
	$costPrice=$_GET['costPrice'];
	$salePrice=$_GET['salePrice'];
	$saleCurrency=$_GET['saleCurrency'];

    echo $fName;
    echo $packaging;
    echo $productamount;
    echo $fechaElab;
    echo $fechaCad;
    echo $warehouse;
    echo $costCurrency;
    echo $costPrice;
    echo $salePrice;
    echo $saleCurrency;
    ?>