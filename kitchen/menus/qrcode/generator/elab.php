<?php
require __DIR__ . '/../../../models/tagsElaboraciones.php';
require_once __DIR__ . '/../../../models/tagsElaboracionesDao.php';

require __DIR__ . '/../../../models/tagselaboraciones_ingredientes.php';
require_once __DIR__ . '/../../../models/tagselaboraciones_ingredientesDao.php';

require __DIR__ . '/../../../models/unit.php';
require_once __DIR__ . '/../../../models/unitDao.php';

require __DIR__ . '/../../../models/alergenos.php';
require_once __DIR__ . '/../../../models/alergenosDao.php';


require __DIR__ . '/../../../models/recetas.php';
require_once __DIR__ . '/../../../models/recetasDao.php';







define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'kitchentag');

/* Attempt to connect to MySQL database */
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

$queryRecetas = "SELECT * FROM `recetas`";

$resultRecetas = $link->query($queryRecetas);

$listaRecetas = array();

while ($row = $resultRecetas->fetch_assoc()) {
	// Agregar cada receta al array $listaRecetas
	$listaRecetas[] = array(
		'id' => $row['id'],
		'nombre' => $row['receta'],
		'num_raciones' => $row['num_raciones'],
		'imagen' => $row['imagen'],
		'caducidad' => $row['expira_dias'],
		'packaging' => $row['empaquetado'],
		'warehouse' => $row['localizacion'],
		'num_raciones' => $row['num_raciones'],
	);
}

$ID = 0;
$tempDir = "temp/";
$email = "";
$fileName = "";
$fName = "";
$packaging = "";
$productAmount = 1;
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

$tagElab = new TagsElaboraciones(
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
	$image
);
$diasParaCaducidad = 0;
$id_del_elaborado = 0;
$btnText = 'Save';
if (isset($_GET['id'])) {
	// Obtener el ID de la URL
	$id = $_GET['id'];
	$id_del_elaborado = $id;
	$tagElab =  TagsElaboracionesDao::select($id);
	$recetaDelElaborado = RecetasDao::select($tagElab->getRecetaId());
	$btnText = 'Edit';
	$diasParaCaducidad = $recetaDelElaborado->getCaducidad();
}

$listIng = array();


$allRecetas = RecetasDao::getAll();

?>
<html lang="en">

<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="">
	<meta name="author" content="">

	<title>Cook code</title>
	<link rel="icon" type="image/png" href="./../../../img/logo.png">
	<!-- Custom fonts for this template-->
	<!-- <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">-->
	<script src="https://kit.fontawesome.com/2e2cc992ca.js" crossorigin="anonymous"></script>
	<link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
	<link href="./../../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
	<!-- Custom styles for this template-->
	<link href="./../../../css/sb-admin-2.min.css" rel="stylesheet">
	<link href="./../../../css/navs.css" rel="stylesheet">
	<link href="./../../../css/qr/forms.css" rel="stylesheet">
	<style>
		#contenedorimagenCh {
			display: inline-flex;
			border: solid 3px black;
			border-radius: 5px;
			margin: 10px;
			width: 200px;
			height: 250px;

		}

		#contenedorimagenCh img {
			width: 100%;
			height: 100%;
			border: solid 3px black;
			border-radius: 5px;
			object-fit: cover;
		}

		.modal {
			display: none;
			/* Por defecto, estará oculto */
			position: fixed;
			/* Posición fija */
			z-index: 1;
			/* Se situará por encima de otros elementos de la página*/
			padding-top: 200px;
			/* El contenido estará situado a 200px de la parte superior */
			left: 0;
			top: 0;
			width: 100%;
			/* Ancho completo */
			height: 100%;
			/* Algura completa */
			overflow: auto;
			/* Se activará el scroll si es necesario */
			background-color: rgba(0, 0, 0, 0.5);
			/* Color negro con opacidad del 50% */
		}


		.modal-content {
			display: flex;
			background-color: #fff;
			border: 10px solid #ccc;
			border-radius: 12px;
			box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
			max-width: 500px;
			margin: 0 auto;
			margin-top: 20px;
			color: black;
			text-align: center;
		}

		.qrframe {
			margin: 0 auto;
			justify-content: center;
			align-items: center;
			margin-bottom: 20px;
		}

		.qrframe img {
			width: 100%;
			height: 100%;
		}

		/* The Close Button */
		.close {
			color: #aaa;
			float: right;
			font-size: 28px;
			font-weight: bold;
			text-align: right;
			background-color: #007934;
			padding: 5px;
		}

		.close:hover,
		.close:focus {
			color: black;
			text-decoration: none;
			cursor: pointer;
		}

		.container-fluid {
			color: black;
		}

		.disabled-link {
			pointer-events: none;
			opacity: 0.5;
			cursor: not-allowed;
		}

		.btn:hover {
			background-color: #11AD37;
			/* Cambio de color al pasar el mouse */
		}

		.btn-shadow {
			box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3);
			/* Sombra con desplazamiento x de 2px, desplazamiento y de 2px, desenfoque de 5px y opacidad de 0.3 */
		}
	</style>
</head>

<body id="page-top">


	<?php

	$imgProfile = "./../../../img/undraw_profile.svg";
	$pathDashboard = "./../../../dashboard";
	$pathLogo = "./../../../img/ccsLogoWhite.png";
	$pathLogout = "./../../../login/logout.php";


	$menu_options = [
		'dashboard' => ['url' => './../../dashboard', 'icon' => './../../../svg/dashboard.svg', 'text' => 'Dashboard'],
		'users' => ['url' => './../../users', 'icon' => './../../../svg/user.svg', 'text' => 'User'],
		'qr' => ['url' => './../../qrcode', 'icon' => './../../../svg/qr_code.svg', 'text' => 'QR'],
		'ecommerce' => ['url' => 'http://localhost:8080/ecommerce', 'icon' => './../../../svg/tpv.svg', 'text' => 'E-commerce'],
		'restaurant' => ['url' => 'http://localhost:8080/restaurant/public/restaurants.html', 'icon' => './../../../svg/restaurant.svg', 'text' => 'Restaurant'],
		'elaborations' => ['url' => './../../elaborations', 'icon' => './../../../svg/recipes.svg', 'text' => 'Elaborations'],
		'stock' => ['url' => './../../stock', 'icon' => './../../../svg/stock.svg', 'text' => 'Stock'],
		'suppliers' => ['url' => './../../suppliers', 'icon' => './../../../svg/orders.svg', 'text' => 'Suppliers'],
		'economic' => ['url' => '#', 'icon' => './../../../svg/graph.svg', 'text' => 'Economic'],
	];


	?>

	<?php include './../../../includes/session.php'; ?>
	<?php include './../../../includes/navs.php'; ?>


	<?php insertarTopNav('./../', './../../../svg/qr_code_print.svg', 'Qr list'); ?>



	<!-- End of Sidebar -->

	<div id="content-wrapper" class="d-flex flex-column">

		<!-- Main Content -->
		<div id="content">
			<div class="container-fluid text-center align-center">
				<div class="container mt-4">
					<a href="elab.php" class="btn btn-primary btn-shadow disabled-link">
						Elaboration
					</a>

					<a href="ingredient.php" class="btn btn-primary btn-shadow">
						Ingredient
					</a>
				</div>
				<hr>
				<h3><strong>Substitute elaboration tag (Qr)</strong></h3>

				<form method="post" id="formElaborado" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">

					<div class="shadow-lg border rounded row justify-content-center m-5">

						<div class=" text-center  col-12 col-md-12 ">

							<!-- El label simula un botón para seleccionar otra imagen -->
							<div class="form-group ">


								<div id="contenedorimagenCh">
									<img class="" src="<?= isset($recetaDelElaborado) ? htmlspecialchars("./../." . $recetaDelElaborado->getImagen()) : "./../../../svg/image.svg" ?>" id="imagenCh" alt="Insert Image" />
								</div>


							</div>

							<!-- Campo oculto para enviar el ID mediante GET -->
							<input type="hidden" name="elaborado_id" id="elaborado_id" value="<?php echo htmlspecialchars($id_del_elaborado); ?>">

						</div>

						<div class=" text-left   col-12 col-md-6 ">



							<div class="form-group">
								<label for="fNameInput">Product name</label>
								<input type="text" id="fNameInput" name="fNameInput" list="list_recipes" class="campo-obligatorio" data-id="<?= $tagElab->getRecetaId() ?>" value="<?= $tagElab->getFName() ?>">

								<datalist id="list_recipes">
									<?php
									foreach ($allRecetas as $receta) {
										echo "<option value='" . $receta->getReceta() . "' data-id='" . $receta->getId() . "'>" . $receta->getReceta() . "</option>";
									}
									?>
								</datalist>
							</div>

							<div class="form-group">
								<label>Product amount</label>
								<input class="campo-obligatorio text-right-input" type="number" id="productamount" name="productamount" step="0.1" min="0" max="100000" value="<?= $tagElab->getProductamount() ?>" />
							</div>

							<div class="form-group">
								<label>Nº rations for package</label>
								<input type="number" id="numRaciones" name="numRaciones" step="0.1" min="0" max="100000" value="<?= $tagElab->getRationsPackage()  ?>" class="campo-obligatorio text-right-input" />

							</div>

							<div class="form-group">
								<label>Production date</label>
								<input class="campo-obligatorio" type="date" id="fechaElab" name="fechaElab" value="<?= (new DateTime($tagElab->getFechaElab()))->format('Y-m-d') ?>" />
							</div>

							<?php
							$diasParaCaducidad = 0;
							if ($id_del_elaborado > 0) {
								$fechaElab = new DateTime($tagElab->getFechaElab());
								$fechaCad = new DateTime($tagElab->getFechaCad());

								// Calcular la diferencia
								$interval = $fechaElab->diff($fechaCad);

								// Obtener la diferencia en días
								$diasParaCaducidad = $interval->days;
							}

							?>



							<div class="form-group">
								<label>Expiration</label>
								<input type="number" id="caducidad" name="caducidad" value="<?= $diasParaCaducidad ?>" placeholder="0" class="campo-obligatorio text-right-input" readonly />
								<label> days</label>
							</div>
						</div>

						<div class=" text-left  col-12 col-md-6 ">

							<div class="form-group">
								<label>Packaging</label>
								<?php
								$selectedPackaging = $tagElab->getPackaging();

								?>

								<select name="packaging" id="packaging" style="max-width:90%;">
									<option value="Bag" <?= $selectedPackaging == 'Bag' ? 'selected' : '' ?>>Bag</option>
									<option value="Pack" <?= $selectedPackaging == 'Pack' ? 'selected' : '' ?>>Pack</option>
									<option value="Box" <?= $selectedPackaging == 'Box' ? 'selected' : '' ?>>Box</option>
									<option value="Bottle" <?= $selectedPackaging == 'Bottle' ? 'selected' : '' ?>>Bottle</option>
									<option value="Can" <?= $selectedPackaging == 'Can' ? 'selected' : '' ?>>Can</option>
								</select>
							</div>
							<div class="form-group">
								<label>Select localization of product</label>
								<?php
								$selectedWarehouse = $tagElab->getWarehouse();

								?>

								<select name="warehouse" id="warehouse" style="max-width:90%;">
									<option value="Freezer" <?= $selectedWarehouse == 'Freezer' ? 'selected' : '' ?>>Freezer</option>
									<option value="Warehouse" <?= $selectedWarehouse == 'Warehouse' ? 'selected' : '' ?>>Warehouse</option>
									<option value="Final product area" <?= $selectedWarehouse == 'Final product area' ? 'selected' : '' ?>>Final product area</option>
								</select>
							</div>
							<div class="form-group">

								<label>Cost price</label>
								<input type="number" class="campo-obligatorio text-right-input" id="costPrice" name="costPrice" step="0.01" min="0" max="10000" value="<?= $tagElab->getCostPrice() ?>" />
								<?php
								$selectedCostCurrency = $tagElab->getCostCurrency();

								?>

								<!-- Select for costCurrency -->
								<select name="costCurrency" id="costCurrency" style="max-width:90%;">
									<option value="Euro" <?= $selectedCostCurrency == 'Euro' ? 'selected' : '' ?>>Euro</option>
									<option value="Dirham" <?= $selectedCostCurrency == 'Dirham' ? 'selected' : '' ?>>Dirham</option>
									<option value="Yen" <?= $selectedCostCurrency == 'Yen' ? 'selected' : '' ?>>Yen</option>
									<option value="Dolar" <?= $selectedCostCurrency == 'Dolar' ? 'selected' : '' ?>>Dolar</option>
								</select>
							</div>
							<div class="form-group">
								<label>Sale price</label>
								<input type="number" class="campo-obligatorio text-right-input" id="salePrice" name="salePrice" step="0.01" min="0" max="100000" value="<?= $tagElab->getSalePrice() ?>" />

								<?php
								$selectedSaleCurrency = $tagElab->getSaleCurrency();

								?>
								<!-- Select for saleCurrency -->
								<select name="saleCurrency" id="saleCurrency" style="max-width:90%;">
									<option value="Euro" <?= $selectedSaleCurrency == 'Euro' ? 'selected' : '' ?>>Euro</option>
									<option value="Dirham" <?= $selectedSaleCurrency == 'Dirham' ? 'selected' : '' ?>>Dirham</option>
									<option value="Yen" <?= $selectedSaleCurrency == 'Yen' ? 'selected' : '' ?>>Yen</option>
									<option value="Dolar" <?= $selectedSaleCurrency == 'Dolar' ? 'selected' : '' ?>>Dolar</option>
								</select>

							</div>
						</div>

						<div class=" text-center  col-12 col-md-12 ">

							<input type="text" id="ingredientData" name="ingredientData" style="display: none" />
							<hr style=" border: 1px solid #ccc;">
							<div class="form-group text-center">

								<button type="submit" name="submit" class="btn btn-primary submitBtn" style="width:20em; margin:0;" disabled><?= $btnText ?></button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div id="ventanaModal" class="modal">
		<div class="modal-content">
			<span class="cerrar"></span>
			<h2>QR Elaborations &#10004;</h2>

			<div class="qrframe " id="qr" style="border:2px solid black; width:305px; height:305px;">
				<img id="imgQr" />
			</div>
		</div>
	</div>

	<footer>
		<center><br></center>
	</footer>
	<script>
		var ingredientes = [];

		function guardarIngrediente() {

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

			document.getElementById('ingredientData').value = JSON.stringify(ingredientes);
		}

		function actualizarListaIngredientes() {
			var textArea = document.getElementById('textarea1');

			var listaIngredientes = '';

			ingredientes.forEach(function(ingrediente) {
				listaIngredientes += "Ingrediente: " + ingrediente['ingrediente'] +
					" Cantidad: " + ingrediente['cantidad'] +
					" Unidad: " + ingrediente['unidad'] +
					" Alergeno: " + ingrediente['alergeno'] + "\n";
			});

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


	<script>
		<?php

		$queryRecetaIngediente = "SELECT * FROM `receta_ingrediente`";

		$resultRecetaIngediente = $link->query($queryRecetaIngediente);

		$listaRecetaIngrediente = array();


		while ($row = $resultRecetaIngediente->fetch_assoc()) {

			$listaRecetaIngrediente[] = array(
				'id' => $row['id'],
				'receta' => $row['receta'],
				'ingrediente' => $row['ingrediente'],
				'elaborado' => $row['elaborado'],
				'cantidad' => $row['cantidad'],
				'tipo_cantidad' => $row['tipo_cantidad'],
			);
		}


		$queryIngrediente = "SELECT * FROM `ingredients`";

		$resultIngrediente = $link->query($queryIngrediente);

		$listaIngredients = array();


		while ($row = $resultIngrediente->fetch_assoc()) {
			// Agregar cada receta al array $listaRecetas
			$listaIngredients[] = array(
				'id' => $row['ID'],
				'fName' => $row['fName'],
				'unidad' => $row['unidad'],
				'costPrice' => $row['costPrice'],
				'merma' => $row['merma'],

			);
		}

		?>
		var listaRecetas = <?php echo json_encode($listaRecetas); ?>;
		var listaRecetaIngrediente = <?php echo json_encode($listaRecetaIngrediente); ?>;
		var listaIngredients = <?php echo json_encode($listaIngredients); ?>;
	</script>
	<script src="./js/elab.js"></script>
	<!-- Bootstrap core JavaScript-->
	<script src="./../../../vendor/jquery/jquery.min.js"></script>
	<script src="./../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

	<!-- Core plugin JavaScript-->
	<script src="./../../../vendor/jquery-easing/jquery.easing.min.js"></script>

	<!-- Custom scripts for all pages-->
	<script src="./../../../js/sb-admin-2.min.js"></script>

</body>

</html>