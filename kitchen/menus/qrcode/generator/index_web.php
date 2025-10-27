<?php
$f = "visit.php";
if(!file_exists($f)){
	touch($f);
	$handle =  fopen($f, "w" ) ;
	fwrite($handle,0) ;
	fclose ($handle);

}
 
include('libs/phpqrcode/qrlib.php'); 
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'bellvera_jI4QZ2Vi');
define('DB_PASSWORD', 'XzEbkwTBQ85JgOkh');
define('DB_NAME', 'bellvera_kitchentag');
 
/* Attempt to connect to MySQL database */
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

function getUsernameFromEmail($email) {
	$find = '@';
	$pos = strpos($email, $find);
	$username = substr($email, 0, $pos);
	return $username;
}

if(isset($_POST['submit']) ) {
	$tempDir = 'temp/'; 
	$email = $_POST['mail'];
	//$subject =  $_POST['subject'];
	$filename = getUsernameFromEmail($email);
	$fName =  $_POST['fName'];
	$packaging = $_POST['packaging'];
	$productamount = $_POST['productamount'];
 	$fechaElab=$_POST['fechaElab'];
	$fechaCad=$_POST['fechaCad'];
	$warehouse=$_POST['warehouse'];
	$costCurrency=$_POST['costCurrency'];
	$costPrice=$_POST['costPrice'];
	$salePrice=$_POST['salePrice'];
	$saleCurrency=$_POST['saleCurrency'];
	$codeContents='https://cookcode.com?productName='.urlencode($fName).'&productamount='.urlencode($productamount).'&fechaElab='.urlencode($fechaElab).'&fechaCad='.urlencode($fechaCad).'&warehouse='.urlencode($warehouse).'&costCurrency='.urlencode($costCurrency).'&saleCurrency='.urlencode($saleCurrency).'&salePrice='.urlencode($salePrice).'&costPrice='.urlencode($costPrice);

	$result = mysqli_query($link, "INSERT INTO `tagscreados`(`tempDir`, `email`, `filename`, `fName`, `packaging`, `productamount`, `fechaElab`, `fechaCad`, `warehouse`, `costCurrency`, `costPrice`, `saleCurrency`, `salePrice`, `codeContents`) VALUES ('$tempDir','$email','$filename','$fName','$packaging','$productamount','$fechaElab','$fechaCad','$warehouse','$costCurrency','$costPrice','$saleCurrency','$salePrice','$codeContents');");


	QRcode::png($codeContents, $tempDir.''.$filename.'.png', QR_ECLEVEL_L, 5);
}
?>
<!DOCTYPE html>
<html lang="en-US">
	<head>
	<title>Cook code tag generator</title>
	<link rel="stylesheet" href="libs/css/bootstrap.min.css">
	<link rel="stylesheet" href="libs/style.css">
	<script src="libs/navbarclock.js"></script>
	</head>
	<body onload="startTime()">
		<nav class="navbar-inverse" role="navigation">
			<a href=#>
				<img src="img/cookco.png" class="hederimg">
			</a>
			<div id="clockdate">
				<div class="clockdate-wrapper">
					<div id="clock"></div>
					<div id="date"><?php echo date('l, F j, Y'); ?></div>
				</div>
			</div>
			
			</div>
		</nav>
		<div class="myoutput">
			<h3><strong>Quick Response (QR) Code Generator</strong></h3>
			<div>
				<h3>Please Fill-out All Fields</h3>
				<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" >
				<div class="form-group">
						<label>Email</label>
						<input type="email" class="form-control" name="mail" style="width:20em;" placeholder="Enter your Email" value="<?php echo @$email; ?>" required />
				</div>
					
				<div class="form-group">
						<label>Packaging</label>
						<select name="packaging" id="packaging" style="max-width:90%;">
								<option value="bag"> Bag </option>
								<option value="pack"> Pack </option>
								<option value="box"> Box </option>
						</select>
				</div>
					<div class="form-group">
						<label>Product name</label>
						<input type="text" class="form-control" name="fName" style="width:20em;" placeholder="Enter product name"/>
					</div>
					
					<div class="form-group">
						<label>Product amount</label>
						<input type="number"  id="productamount" name="productamount" step="0.1" min="0" max="100000"/>
						<label>Units</label>
						<select name="units" id="units" style="max-width:90%;">
							<option value="KG"> KILOGRAM </option>
							<option value="ML"> MILILITER </option>
							<option value="GR"> GRAM </option>
						</select>
					</div>
					<div class="form-group">
						<label>Production date</label>
						<input type="datetime-local" id="fechaElab" name="fechaElab"/>
					</div>
					<div class="form-group">
						<label>Expiration date</label>
						<input type="datetime-local" id="fechaCad" name="fechaCad"/>
					</div>
					<div class="form-group">
						<label>Select localization of product</label>
						<select name="warehouse" id="warehouse" style="max-width:90%;">
							<option value="bag"> Freezer </option>
							<option value="pack"> Warehouse </option>
							<option value="box"> Final product area </option>
						</select>
					</div>
					<div class="form-group">
						<label>Cost currency</label>
						<select name="costCurrency" id="costCurrency" style="max-width:90%;">
							<option value="Euro"> Euro </option>
							<option value="Dirham"> Dirham </option>
							<option value="Yen"> Yen </option>
						</select>
						<label>Cost price</label>
						<input type="number"  id="costPrice" name="costPrice" step="0.1" min="0" max="100"/>
					</div>
					<div class="form-group">
						<label>Sale price</label>
						<input type="number"  id="salePrice" name="salePrice" step="0.1" min="0" max="100"/>
		
						<label>Sale currency</label>
						<select name="saleCurrency" id="saleCurrency" style="max-width:90%;">
							<option value="Euro"> Euro </option>
							<option value="Dirham"> Dirham </option>
							<option value="Yen"> Yen </option>
						</select>
					</div>
					<div class="form-group">
						<input type="submit" name="submit" class="btn btn-primary submitBtn" style="width:20em; margin:0;" />
					</div>
				</form>
			<?php
			if(!isset($filename)){
				$filename = "author";
			}
			?>
			<div class="qr-field">
				<h2 style="text-align:center">QR Code Result: </h2>
				<center>
					<div class="qrframe" style="border:2px solid black; width:210px; height:210px;">
							<?php echo '<img src="temp/'. @$filename.'.png" style="width:200px; height:200px;"><br>'; ?>
					</div>
					<a class="btn btn-primary submitBtn" style="width:210px; margin:5px 0;" href="download.php?file=<?php echo $filename; ?>.png ">Download QR Code</a>
				</center>
			</div>
		</div>
			
			<div class = "dllink" style="text-align:center;margin:-100px 0px 50px 0px;">
				<h4>Cook code</h4>
			</div>
		</div>
	</body>
	<footer><center><br></center></footer>
</html>

