<?php
//	require __DIR__ . '/vendor/autoload.php';

//use Automattic\WooCommerce\Client;
$f = "visit.php";
if(!file_exists($f)){
	touch($f);
	$handle =  fopen($f, "w" ) ;
	fwrite($handle,0) ;
	fclose ($handle);

}
 

require __DIR__ . '/../../models/tagsPreelaboraciones.php';
require_once __DIR__ . '/../../models/tagsPreelaboracionesDao.php';
require __DIR__ . '/../../models/tagspreelaboraciones_ingredientes.php';
require_once __DIR__ . '/../../models/tagspreelaboraciones_ingredientesDao.php';
require __DIR__ . '/../../models/unit.php';
require_once __DIR__ . '/../../models/unitDao.php';
require __DIR__ . '/../../models/alergenos.php';
require_once __DIR__ . '/../../models/alergenosDao.php';







include('libs/phpqrcode/qrlib.php'); 
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'kitchentag');
 
/* Attempt to connect to MySQL database */
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

function getUsernameFromEmail($email) {
	$find = '@';
	$pos = strpos($email, $find);
	$username = substr($email, 0, $pos);
	return $username;
}



$ID = 0;
$tempDir = "";
$email = "";
$fileName = "";
$fName = "";
$packaging = "";
$productAmount = 0;
$fechaElab = "";
$fechaCad = "";
$warehouse = "";
$costCurrency = "";
$costPrice = 0;
$saleCurrency = "";
$salePrice = 0;
$codeContents = "";
$image = null;

$imageErr = "";



$tagPreelab = new TagsPreelaboraciones(
	$ID,
	$tempDir,
	$email,
	$fileName,
	$fName,
	$packaging,
	$productAmount,
	$fechaElab,
	$fechaCad,
	$warehouse,
	$costCurrency,
	$costPrice,
	$saleCurrency,
	$salePrice,
	$codeContents,
	$image,
);



$id_del_elaborado = 0;


if (isset($_GET['id'])) {
	// Obtener el ID de la URL
	$id = $_GET['id'];





	$tagPreelab =  TagsPreelaboracionesDao::select($id);
}


$listIng = array();







if(isset($_POST['submit']) ) {



	$err = false;
	if (count($_FILES) > 0) {
		if (isset($_FILES['image']['tmp_name']) && $_FILES['image']['size'] > 0) {
			$fileType = strtolower(pathinfo(
				basename($_FILES['image']['name']),
				PATHINFO_EXTENSION
			));
			// Check file size
			if ($_FILES["image"]["size"] > 5000000) { //5MB
				$imageErr = "* File too large. Max 5MB.";
				echo $imageErr;
				$err = true;
			}
			//Chek type
			if (!in_array($fileType, ['jpg', 'png', 'jpeg', 'gif', 'bmp'])) {
				$imageErr = '* Only JPG, JPEG, PNG, BMP & GIF files are allowed.';
				echo $imageErr;
				$err = true;
			}
			if (!$err) {
				$imgData = file_get_contents($_FILES['image']['tmp_name']);
				$image = $imgData;
			}
		}
	}




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
	$ingredientesJson = $_POST['ingredientData'];

	// Decodificar la cadena JSON en un array PHP
	$ingredientes = json_decode($ingredientesJson, true);

	foreach ($ingredientes as $ingredient) {
		$codeContents .= '&ingredient[]=' . urlencode($ingredient['ingrediente']) . '|' . urlencode($ingredient['cantidad']) . '|' . urlencode($ingredient['unidad']) . '|' . urlencode($ingredient['alergeno']);
	}


	$tagPreelab->setTempDir($tempDir);
	$tagPreelab->setEmail($email);
	$tagPreelab->setFilename($filename);
	$tagPreelab->setFname($fName);
	$tagPreelab->setPackaging($packaging);
	$tagPreelab->setProductamount($productamount);
	$tagPreelab->setFechaElab($fechaElab);
	$tagPreelab->setFechaCad($fechaCad);
	$tagPreelab->setWarehouse($warehouse);
	$tagPreelab->setCostCurrency($costCurrency);
	$tagPreelab->setCostPrice($costPrice);
	$tagPreelab->setSalePrice($salePrice);
	$tagPreelab->setSaleCurrency($saleCurrency);
	$tagPreelab->setCodeContents($codeContents);
	$tagPreelab->setImage($image);

TagsPreelaboracionesDao::insert($tagPreelab);





//	$result = mysqli_query($link, "INSERT INTO `tagspreelaboraciones`(`tempDir`, `email`, `filename`, `fName`, `packaging`, `productamount`, `fechaElab`, `fechaCad`, `warehouse`, `costCurrency`, `costPrice`, `saleCurrency`, `salePrice`, `codeContents`) VALUES ('$tempDir','$email','$filename','$fName','$packaging','$productamount','$fechaElab','$fechaCad','$warehouse','$costCurrency','$costPrice','$saleCurrency','$salePrice','$codeContents');");
	
	
	






//	QRcode::png($codeContents, $tempDir.''.$filename.'.png', QR_ECLEVEL_L, 5);


	


	 $lastInsert =TagsPreelaboracionesDao::getLastInsertId();
	$ingredientesJson = $_POST['ingredientData'];

// Decodificar la cadena JSON en un array PHP
$ingredientes = json_decode($ingredientesJson, true);
	 // Guardar cada ingrediente en la base de datos
	 foreach ($ingredientes as $ingrediente) {
         $ingr = $ingrediente['ingrediente'];
		 $cant = $ingrediente['cantidad'];
		 $unit = $ingrediente['unidad'];
		 $alergen = $ingrediente['alergeno'];
		 mysqli_query($link, "INSERT INTO `tagspreelaboraciones_ingredients`(`preelaboracion_id`, `ingrediente`, `cantidad`, `unidad`, `alergeno`) VALUES ('$lastInsert','$ingr','$cant','$unit','$alergen');");
	 }

$id_del_elaborado = $lastInsert;

	$redirect_url = "printQr.php?id=" . urlencode($id_del_elaborado);


	header("Location: $redirect_url");
	exit();

}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
	<title>Cook code tag generator</title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="">
	<meta name="author" content="">
	<link rel="stylesheet" href="libs/style.css">
	<script src="libs/navbarclock.js"></script>
	<script src="https://kit.fontawesome.com/2e2cc992ca.js" crossorigin="anonymous"></script>
	<link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
	<!-- Custom styles for this template-->
	<link href="../../css/sb-admin-2.min.css" rel="stylesheet">
	<link href="../../menu.css" rel="stylesheet">
	</head>
	<body id="page-top">
	<div id="wrapper">
		
			<!-- Sidebar -->
			<ul class="navbar-nav bg-primary sidebar sidebar-dark accordion" id="accordionSidebar">

				<!-- Sidebar - Brand -->
				<a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
					<div class="sidebar-brand-icon rotate-n-15">

					</div>
					<div class="sidebar-brand-text mx-3"><img src="../../img/ccsLogoWhite.png" ></div>
				</a>

				




				<!-- Nav Item - Dashboard -->
				<li class="nav-item active">
					<a class="nav-link" href="../../dashboard/index.php">
						<i class="fas fa-fw fa-tachometer-alt"></i>
						<span>Dashboard</span></a>
				</li>

			





			
				<!-- Nav Item - Pedidos -->


				<!-- Create User -->




				<li class="nav-item ">

					<a class="nav-link " href="tickets.php" id="userDropdown" role="button">
						<i class="fa-solid fa-user"></i>
						<span>QR list</span>
					</a>

				</li>



				<!-- Edit User -->
				<li class="nav-item ">
					<div>
						<a class="nav-link " href="#" id="qrDropdown" role="button">
							<i class="fa-solid fa-qrcode"></i>
							<span>Edit QR</span>
						</a>


					</div>
				</li>





				<!-- Divider -->
				<hr class="sidebar-divider d-none d-md-block">

				<!-- Sidebar Toggler (Sidebar) -->
				<div class="text-center d-none d-md-inline">
					<button class="rounded-circle border-0" id="sidebarToggle"></button>
				</div>
			</ul>
			<!-- End of Sidebar -->
	


		<div id="content-wrapper" class="d-flex flex-column">

			<!-- Main Content -->
			<div id="content">



				<!-- Topbar -->
				<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

					<!-- Sidebar Toggle (Topbar) -->
					<button id="sidebarToggleTop" name="boto_burguer" class="btn btn-link d-md-none rounded-circle mr-3">
						<i class="fa fa-bars"></i>
					</button>


					<!-- Topbar Navbar -->
					<ul class="navbar-nav ml-auto">

						<!-- Nav Item - Search Dropdown (Visible Only XS) -->
						<li class="nav-item dropdown no-arrow d-sm-none">
							<a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<i class="fas fa-search fa-fw"></i>
							</a>
							<!-- Dropdown - Messages -->
							<div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
								<form class="form-inline mr-auto w-100 navbar-search">
									<div class="input-group">
										<input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
										<div class="input-group-append">
											<button class="btn btn-primary" type="button">
												<i class="fas fa-search fa-sm"></i>
											</button>
										</div>
									</div>
								</form>
							</div>
						</li>

						<!-- Nav Item - Alerts -->
						<li class="nav-item dropdown no-arrow mx-1">
							<a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<i class="fas fa-bell fa-fw"></i>
								<!-- Counter - Alerts -->
								<span class="badge badge-danger badge-counter">3+</span>
							</a>
							<!-- Dropdown - Alerts -->
							<div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alertsDropdown">
								<h6 class="dropdown-header">
									Alerts Center
								</h6>
								<a class="dropdown-item d-flex align-items-center" href="#">
									<div class="mr-3">
										<div class="icon-circle bg-primary">
											<i class="fas fa-file-alt text-white"></i>
										</div>
									</div>
									<div>
										<div class="small text-gray-500">August 20, 2023</div>
										<span class="font-weight-bold">Bolognese tags level i slow</span>
									</div>
								</a>
								<a class="dropdown-item d-flex align-items-center" href="#">
									<div class="mr-3">
										<div class="icon-circle bg-success">
											<i class="fas fa-donate text-white"></i>
										</div>
									</div>
									<div>
										<div class="small text-gray-500">August 15, 2023</div>
										Some falafel is expired
									</div>
								</a>
								<a class="dropdown-item d-flex align-items-center" href="#">
									<div class="mr-3">
										<div class="icon-circle bg-warning">
											<i class="fas fa-exclamation-triangle text-white"></i>
										</div>
									</div>
									<div>
										<div class="small text-gray-500">August 10, 2023</div>
										Order without stock of Meat balls
									</div>
								</a>
								<a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>
							</div>
						</li>



						<div class="topbar-divider d-none d-sm-block"></div>

						<!-- Nav Item - User Information -->
						<li class="nav-item dropdown no-arrow">
							<a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<span class="mr-2 d-none d-lg-inline text-gray-600 small"> admin</span>
								<img class="img-profile rounded-circle" src="../../img/undraw_profile.svg">
							</a>
							<!-- Dropdown - User Information -->
							<div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
								<a class="dropdown-item" href="#">
									<i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
									Profile
								</a>
								<a class="dropdown-item" href="#">
									<i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
									Settings
								</a>
								<a class="dropdown-item" href="#">
									<i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
									Activity Log
								</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="logout.php">
									<i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
									Logout
								</a>
							</div>
						</li>

					</ul>

				</nav>



				<div class="container-fluid text-center align-center">
					<div class="container mt-4">
						<a href="elab.php" class="btn btn-primary">
							Elaboration
						</a>
						<a href="preelab.php" class="btn btn-primary">
							Preelaboration
						</a>
						<a href="ingredient.php" class="btn btn-primary">
							Ingredient
						</a>
					</div>
					<hr>



					<h3><strong>Quick Response (QR) Code Generator</strong></h3>


					<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">


						<div class="border border-primary rounded row justify-content-center">

							<div class=" text-center  col-12 col-md-12 ">


								<h3>Please Fill-out All Fields</h3>

							</div>

							<div class=" text-left border-right border-primary  col-12 col-md-6 ">
								<!-- Campo oculto para enviar el ID mediante GET -->
								<input type="hidden" name="elaborado_id" value="">

								

								<div id="divImage">
									<img class="border border-primary rounded m-3" width="100px" src="<?= !is_null($tagPreelab->getImage()) ? 'data:image/jpg;charset=utf8;base64,' . base64_encode($tagPreelab->getImage()) : 'img/bandeja-de-comida.png' ?>" alt="Imagen de elab">
									<br>
								</div>

								<div class="form-group ">
									<label>Email</label>
									<input type="email" name="mail" placeholder="Enter your Email" value="<?= $tagPreelab->getEmail() ?>" required />
								</div>


								<div class="form-group">
									<label>Product name</label>
									<input type="text" name="fName" placeholder="Enter product name" value="<?= $tagPreelab->getFName() ?>" />
								</div>

								<div class="form-group">
									<label>Product amount</label>
									<input type="number" id="productamount" name="productamount" step="0.1" min="0" max="100000" value="" />
									<label>Units</label>
									<select name="units" id="units" style="max-width:90%;">
										<option value="KG"> KILOGRAM </option>
										<option value="ML"> MILILITER </option>
										<option value="GR"> GRAM </option>
									</select>
								</div>
								<div class="form-group">
									<label>Production date</label>
									<input type="datetime-local" id="fechaElab" name="fechaElab" value="<?= $tagPreelab->getFechaElab() ?>" />
								</div>
								<div class="form-group">
									<label>Expiration date</label>
									<input type="datetime-local" id="fechaCad" name="fechaCad" value="<?= $tagPreelab->getFechaCad() ?>" />
								</div>
								<div class="form-group">
									<label>Packaging</label>
									<select name="packaging" id="packaging" style="max-width:90%;" value="">
										<option value="bag"> Bag </option>
										<option value="pack"> Pack </option>
										<option value="box"> Box </option>
									</select>
								</div>
								<div class="form-group">
									<label>Select localization of product</label>
									<select name="warehouse" id="warehouse" style="max-width:90%;" value="">
										<option value="bag"> Freezer </option>
										<option value="pack"> Warehouse </option>
										<option value="box"> Final product area </option>
									</select>
								</div>
								<div class="form-group">
									<label>Cost currency</label>
									<select name="costCurrency" id="costCurrency" style="max-width:90%;" value="">
										<option value="Euro"> Euro </option>
										<option value="Dirham"> Dirham </option>
										<option value="Yen"> Yen </option>
									</select>
									<label>Cost price</label>
									<input type="number" id="costPrice" name="costPrice" step="0.1" min="0" max="100" value="<?= $tagPreelab->getCostPrice() ?>" />
								</div>
								<div class="form-group">
									<label>Sale price</label>
									<input type="number" id="salePrice" name="salePrice" step="0.1" min="0" max="100" value="<?= $tagPreelab->getSalePrice() ?>" />

									<label>Sale currency</label>
									<select name="saleCurrency" id="saleCurrency" style="max-width:90%;" value="">
										<option value="Euro"> Euro </option>
										<option value="Dirham"> Dirham </option>
										<option value="Yen"> Yen </option>
									</select>
								</div>


								<label>Image: <br>
									
									
									<input type="file" name="image">
								</label>


							</div>


							<div class=" text-left  col-12 col-md-6 pt-5">


								<br>

								<h4 class="text-center mt-5">Ingredientes</h4>
								<hr class="m-3 mb-5">

								<div class="form-group   mt-5">
									<label>Nombre</label>
									<input type="text" id="ingrediente" name="ingrediente" />
								</div>

								<div class="form-group  ">
									<label>Cantidad</label>
									<input type="number" id="ingredienteCant" name="ingredienteCant" />



									<label>Units</label>
									<select name="unitsIng" id="unitsIng" style="max-width:90%;">
										<?php
										$listUnits = UnitDao::getAll();

										foreach ($listUnits as $unit) {
											echo "<option value=" . $unit->getUnit() . ">" . $unit->getUnit() . " </option>";
										}

										?>

									</select>
								</div>

								<div class="form-group ">
									<label>Alergenos</label>
									<select name="alergenos" id="alergenos" style="max-width:90%;">
										<?php
										$listAlergenos = AlergenoDao::getAll();

										foreach ($listAlergenos as $alergeno) {
											echo "<option value=" . $alergeno->getNombre() . ">" . $alergeno->getNombre() . " </option>";
										}

										?>

									</select>
								</div>
								<div class="form-group text-center">
									<button class="btn-primary rounded m-3 " style="width:10em; " type="button" name="ing" onclick='guardarIngrediente()'>Añadir</button>
									<button class="btn-danger rounded m-3 " style="width:10em; " type="button" name="ing" onclick='deleteIngrediente()'>Delete</button>
								</div>

								<div class="form-group">
									<label for="textarea1">Ingredientes añadidos:</label>
									<textarea class="form-control" id="textarea1" rows="8"></textarea>
								</div>


							</div>
							<div class=" text-center  col-12 col-md-12 ">
								<input type="text" id="ingredientData" name="ingredientData" style="display: none" />

								<hr>

								<div class="form-group text-center">
									<input type="submit" name="submit" class="btn btn-primary submitBtn" style="width:20em; margin:0;" />
								</div>
							</div>
						</div>

						<!-- Lista de ingredientes -->



					</form>

				</div>
			</div>





		</div>
	</div>

	<footer>
		<center><br></center>
	</footer>
	<script>
		var ingredientes = []; // Array para almacenar los ingredientes

		function guardarIngrediente() {
			// var listIngredientes = document.getElementById('listIngredientes');
			// listIngredientes.style.display = 'block';

			var ingrediente = document.getElementById('ingrediente').value;
			var cantidad = document.getElementById('ingredienteCant').value;
			var unidad = document.getElementById('unitsIng').value;
			var alergeno = document.getElementById('alergenos').value;

			// Agregar el ingrediente a la lista en el navegador
			var nuevoIngrediente = {
				'ingrediente': ingrediente,
				'cantidad': cantidad,
				'unidad': unidad,
				'alergeno': alergeno
			};
			ingredientes.push(nuevoIngrediente);
			actualizarListaIngredientes();

			// Actualizar el campo de datos oculto con los ingredientes para enviar al servidor
			document.getElementById('ingredientData').value = JSON.stringify(ingredientes);
		}


		function actualizarListaIngredientes() {
			var textArea = document.getElementById('textarea1');

			// Crear una cadena de texto para contener los ingredientes
			var listaIngredientes = '';

			// Iterar sobre los ingredientes y agregarlos a la cadena de texto
			ingredientes.forEach(function(ingrediente) {
				listaIngredientes += "Ingrediente: " + ingrediente['ingrediente'] +
					" Cantidad: " + ingrediente['cantidad'] +
					" Unidad: " + ingrediente['unidad'] +
					" Alergeno: " + ingrediente['alergeno'] + "\n";
			});

			// Asignar la cadena de texto al valor del textarea
			textArea.value = listaIngredientes;

		}

		function deleteIngrediente() {
			var textArea = document.getElementById('textarea1');


			textArea.value = "";
			ingredientes = [];

		}


		function mostrarVistaPrevia() {
			var archivo = document.getElementById('imagen').files[0];
			var vistaPrevia = document.getElementById('vista-previa');
			var contenedorVistaPrevia = document.getElementById('contenedor-vista-previa');

			if (archivo) {
				var lector = new FileReader();
				lector.onload = function(evento) {
					vistaPrevia.src = evento.target.result;
					vistaPrevia.style.display = 'block';
				}
				lector.readAsDataURL(archivo);
			} else {
				vistaPrevia.src = '#';
				vistaPrevia.style.display = 'none';
			}
		}
	</script>
	  <!-- Bootstrap core JavaScript-->
	  <script src="./../../vendor/jquery/jquery.min.js"></script>
    <script src="./../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="./../../vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="./../../js/sb-admin-2.min.js"></script>

</body>



</html>
