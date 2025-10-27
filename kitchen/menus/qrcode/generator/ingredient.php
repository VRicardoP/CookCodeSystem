<?php


define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'kitchentag');

require __DIR__ . '/../../../models/tagsIngredientes.php';
require_once __DIR__ . '/../../../models/tagsIngredientesDao.php';
require __DIR__ . '/../../../models/unit.php';
require_once __DIR__ . '/../../../models/unitDao.php';
require __DIR__ . '/../../../models/gruposPermisos.php';
require_once __DIR__ . '/../../../models/gruposPermisosDao.php';
require __DIR__ . '/../../../models/user.php';
require_once __DIR__ . '/../../../models/userDao.php';
require __DIR__ . '/../../../models/grupos.php';
require_once __DIR__ . '/../../../models/gruposDao.php';
require __DIR__ . '/../../../models/permisos.php';
require_once __DIR__ . '/../../../models/permisosDao.php';
require __DIR__ . '/../../../models/ingredientes.php';
require_once __DIR__ . '/../../../models/ingredientesDao.php';

$userses = new User();
$grupo = new Grupo();
session_start();

if (isset($_SESSION['user_id'])) {
	$id = $_SESSION['user_id'];
	$userSession = UserDao::select($_SESSION['user_id']);
	$grupoSession = GruposDao::select($userSession->getGrupo_id());
} else {
	//No authenticated user
	header('Location: ./../../login/login.php');
}


$allIngredientes = IngredientesDao::getAll();

/* Attempt to connect to MySQL database */
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

$queryIngredientes = "SELECT * FROM `ingredients`";

$resultIngredientes = $link->query($queryIngredientes);

$listaIngredientes = array();

while ($row = $resultIngredientes->fetch_assoc()) {
	// Agregar cada receta al array $listaRecetas
	$listaIngredientes[] = array(
		'id' => $row['ID'],
		'nombre' => $row['fName'],
		'unidad' => $row['unidad'],
		'costPrice' => $row['costPrice'],
		'imagen' => $row['image'],
		'caducidad' => $row['expira_dias'],
		'packaging' => $row['packaging'],
		'warehouse' => $row['warehouse'],
	);
}

$ID = 0;
$tempDir = "";
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


$tagIng = new TagsIngredientes(
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

$id_del_elaborado = 0;
$diasParaCaducidad = 0;
$btnText = 'Save';
if (isset($_GET['id'])) {
	// Obtener el ID de la URL
	$id = $_GET['id'];
	$id_del_elaborado = $id;
	$tagIng =  TagsIngredientesDao::select($id);
	$ing = IngredientesDao::select($tagIng->getIngredienteId());
	$unitIngredientEdit = $ing->getUnidad();
	$btnText = 'Edit';
	$diasParaCaducidad = $ing->getCaducidad();
}

function crearQr()
{
	$id = TagsIngredientesDao::getLastInsertId();
	$tagCreado =  TagsIngredientesDao::select($id);
	QRcode::png($tagCreado->getCodeContents(), $tagCreado->getTempDir() . '' . $tagCreado->getFilename() . '.png', QR_ECLEVEL_M, 3, 4);
	return $tagCreado->getFilename();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<title>Ingredient(Qr)</title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="">
	<meta name="author" content="">
	<link rel="stylesheet" href="libs/style.css">
	<script src="libs/navbarclock.js"></script>
	<script src="https://kit.fontawesome.com/2e2cc992ca.js" crossorigin="anonymous"></script>
	<link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
	<link href="./../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
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
			background-color: #11AD37 ;
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
		'dashboard' => ['url' => './../../dashboard', 'icon' => './../../svg/dashboard.svg', 'text' => 'Dashboard'],
		'users' => ['url' => './../../users/userList.php', 'icon' => './../../svg/user.svg', 'text' => 'User'],
		'qr' => ['url' => './../../qrcode/generator/tickets.php', 'icon' => './../../svg/qr_code.svg', 'text' => 'QR'],
		'ecommerce' => ['url' => 'http://localhost:8080/ecommerce', 'icon' => './../../svg/tpv.svg', 'text' => 'E-commerce'],
		'restaurant' => ['url' => 'http://localhost:8080/restaurant/public/restaurants.html', 'icon' => './../../svg/restaurant.svg', 'text' => 'Restaurant'],
		'elaborations' => ['url' => './../../elaborations/elabList.php', 'icon' => './../../svg/recipes.svg', 'text' => 'Elaborations'],
		'stock' => ['url' => './../../stock/preelaborationStock.php', 'icon' => './../../svg/stock.svg', 'text' => 'Stock'],
		'suppliers' => ['url' => './../../suppliers/suppliersList.php', 'icon' => './../../svg/orders.svg', 'text' => 'Suppliers'],
		'economic' => ['url' => '#', 'icon' => './../../svg/graph.svg', 'text' => 'Economic'],
	];

	 include './../../../includes/session.php'; 
	 include './../../../includes/navs.php'; 
	?>

	<?php insertarTopNav('printQr.php', './../../../svg/qr_code_print.svg', 'Print Qr'); ?>

	<div id="content-wrapper" class="d-flex flex-column">
		<!-- Main Content -->
		<div id="content">
			<div class="container-fluid text-center align-center">
				<div class="container mt-4">
					<a href="elab.php" class="btn btn-primary btn-shadow">
						Elaboration
					</a>
					<a href="ingredient.php" class="btn btn-primary btn-shadow disabled-link">
						Ingredient
					</a>
				</div>
				<hr>
				<h3><strong>Substitute ingredient tag (Qr)</strong></h3>
				<form method="post" id="formIngredientes" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">

					<div class="shadow-lg border rounded row justify-content-center m-5">

						<div class=" text-center  col-12 col-md-12 ">

							<!-- El label simula un botón para seleccionar otra imagen -->
							<div class="form-group ">

								<div id="contenedorimagenCh">
									<img class="" src=<?= isset($ing) ? "./../".$ing->getImage() : "./../../svg/image.svg" ?> id="imagenCh" alt="Insert Image" />
								</div>

							</div>
							<!-- Campo oculto para enviar el ID mediante GET -->
							<input type="hidden" name="elaborado_id" id="elaborado_id" value="<?php echo htmlspecialchars($id_del_elaborado); ?>">

						</div>

						<div class=" text-left col-12 col-md-6 ">
							<div class="form-group">
								<label for="fNameInput">Product name</label>
								<input type="text" id="fNameInput" name="fNameInput" list="list_ing" data-id="<?= $tagIng->getIngredienteId()  ?>" class="campo-obligatorio" value="<?= isset($tagIng) ? $tagIng->getFName() : "" ?>">
								<datalist id="list_ing">
									<?php
									foreach ($allIngredientes as $ingrediente) {
										echo "<option value=" . $ingrediente->getFName() . " data-id=" . $ingrediente->getId() . "  data-und=" . $ingrediente->getUnidad() . " > </option>";
									}
									?>

								</datalist>
							</div>

							<script>
								// Lista de ingredientes
								var listaIngredientes = <?php echo json_encode($listaIngredientes); ?>;
							</script>

							<div class="form-group">
								<label for="productamount">Product amount</label>
								<input type="number" class="campo-obligatorio text-right-input" id="productamount" name="productamount" step="0.1" min="0" max="100000" value="<?= $tagIng->getProductamount() ?>" />

							</div>
							<div class="form-group">

								<label for="cantidadPaquete" id="labelUnidad">
									<?php if (isset($unitIngredientEdit)) : ?>
										Nº <?= $unitIngredientEdit ?> for package
									<?php else : ?>
										Nº kg for package
									<?php endif; ?>
								</label>
								<input type="number" id="cantidadPaquete" name="cantidadPaquete" step="0.01" min="0" max="100000" value="<?= $tagIng->getCantidadPaquete() ?>" class="campo-obligatorio text-right-input" />
							</div>
							<div class="form-group">
								<label for="fechaElab">Production date</label>
								<input type="date" class="campo-obligatorio" id="fechaElab" name="fechaElab" value="<?= (new DateTime($tagIng->getFechaElab()))->format('Y-m-d') ?>" />
							</div>

							<div class="form-group">
								<label for="caducidad">Expiration</label>
								<input type="number" id="caducidad" name="caducidad" value="<?= $diasParaCaducidad ?>" placeholder="0" class="campo-obligatorio text-right-input" readonly/>
								<span> days</span>
							</div>
						</div>

						<div class=" text-left  col-12 col-md-6 ">

							<div class="form-group">
								<label for="packaging">Packaging</label>
								<?php
								isset($tagIng) ? $selectedPackaging = $tagIng->getPackaging() : $selectedPackaging = ''
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
								<label for="warehouse">Select localization of product</label>
								<?php
								isset($tagIng) ? $selectedWarehouse = $tagIng->getWarehouse() : $selectedWarehouse = ''
								?>

								<select name="warehouse" id="warehouse" style="max-width:90%;">
									<option value="Freezer" <?= $selectedWarehouse == 'Freezer' ? 'selected' : '' ?>>Freezer</option>
									<option value="Warehouse" <?= $selectedWarehouse == 'Warehouse' ? 'selected' : '' ?>>Warehouse</option>
									<option value="Final product area" <?= $selectedWarehouse == 'Final product area' ? 'selected' : '' ?>>Final product area</option>
								</select>
							</div>
							<div class="form-group">
								<label for="costPrice">Cost price</label>
								<input type="number" id="costPrice" name="costPrice" step="0.1" min="0" max="1000000" value="<?= isset($tagIng) ? $tagIng->getCostPrice() : 0 ?>" class="campo-obligatorio text-right-input" />
								<?php
								isset($tagIng) ? $selectedCostCurrency = $tagIng->getCostCurrency() : $selectedCostCurrency = ''
								?>
								<!-- Select for costCurrency -->
								<select name="costCurrency" id="costCurrency" style="max-width:90%;">
									<option value="Euro" <?= $selectedCostCurrency == 'Euro' ? 'selected' : '' ?>>Euro</option>
									<option value="Dirham" <?= $selectedCostCurrency == 'Dirham' ? 'selected' : '' ?>>Dirham</option>
									<option value="Yen" <?= $selectedCostCurrency == 'Yen' ? 'selected' : '' ?>>Yen</option>
									<option value="Dolar" <?= $selectedCostCurrency == 'Dolar' ? 'Dolar' : '' ?>>Dolar</option>
								</select>
							</div>
							<div class="form-group">
								<label for="salePrice">Sale price</label>
								<input type="number" id="salePrice" name="salePrice" step="0.1" min="0" max="10000000" value="<?= isset($tagIng) ? $tagIng->getSalePrice() : 0 ?>" class="campo-obligatorio text-right-input" />
								<?php
								isset($tagIng) ? $selectedSaleCurrency = $tagIng->getSaleCurrency() : $selectedSaleCurrency = ''
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
							<hr style=" border: 1px solid #ccc;">
							<div class="form-group text-center">
								<button type="submit" name="submit" id="submit" class="btn btn-primary submitBtn" style="width:20em; margin:0;" disabled><?= $btnText ?></button>
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
			<h2>QR Ingredients &#10004;</h2>

			<div class="qrframe " id="qr" style="border:2px solid black; width:305px; height:305px;">
				<img id="imgQr" />
			</div>
		</div>
	</div>
	<footer>
		<center><br></center>
	</footer>
	<script>
		var listaIngredientes = <?php echo json_encode($listaIngredientes); ?>;
	</script>
	<script>
		document.getElementById('formIngredientes').addEventListener('submit', function(event) {
			event.preventDefault();

			var idElaborado = document.getElementById('elaborado_id').value;
			var tipoProduct = "Elaborado";
			var input = document.getElementById('fNameInput');
			var idIngrediente = input.getAttribute('data-id');
			var packaging = document.getElementById('packaging').value;
			var productAmount = document.getElementById('productamount').value;
			var cantidadPaquete = document.getElementById('cantidadPaquete').value;
			var fechaElab = document.getElementById('fechaElab').value;
			var fechaCad = document.getElementById('caducidad').value;
			var warehouse = document.getElementById('warehouse').value;
			var costCurrency = document.getElementById('costCurrency').value;
			var costPrice = document.getElementById('costPrice').value;
			var salePrice = document.getElementById('salePrice').value;
			var saleCurrency = document.getElementById('saleCurrency').value;

			let fechaCaducidad = new Date(fechaElab);


	fechaCaducidad.setDate(fechaCaducidad.getDate() + parseInt(fechaCad));

	// Convertir la nueva fecha de caducidad a un formato legible
	 fechaCaducidad = fechaCaducidad.toISOString().split('T')[0];

			let dataToSend = {
				idElaborado: idElaborado,
				tipoProduct: tipoProduct,
				idIngrediente: idIngrediente,
				packaging: packaging,
				productAmount: productAmount,
				cantidadPaquete: cantidadPaquete,
				fechaElab: fechaElab,
				fechaCad: fechaCaducidad,
				warehouse: warehouse,
				costCurrency: costCurrency,
				costPrice: costPrice,
				salePrice: salePrice,
				saleCurrency: saleCurrency,
			};

			$.ajax({
				url: 'crearEditarTagIngrediente.php',
				type: 'POST',
				data: dataToSend,
				success: function(response) {



					console.log(response);
					modalQrGuardado(response['fileName']);


					document.getElementById('imagenCh').src = "./../../svg/image.svg";
					document.getElementById('cantidadPaquete').value = 0;
					document.getElementById('fNameInput').value = "";
					document.getElementById('packaging').value = "Bag";
					document.getElementById('productamount').value = "";
					document.getElementById('fechaElab').value = "<?= (new DateTime())->format('Y-m-d') ?>";
					document.getElementById('caducidad').value = "";
					document.getElementById('warehouse').value = "Freezer";
					document.getElementById('costCurrency').value = "Euro";
					document.getElementById('costPrice').value = 0;
					document.getElementById('salePrice').value = 0;
					document.getElementById('saleCurrency').value = "Euro";
					document.querySelector('.submitBtn').disabled = true;


				},
				error: function(xhr, status, error) {
					console.error(error);
				}
			});

		});
	</script>
	<!-- Bootstrap core JavaScript-->
	<script src="./../../vendor/jquery/jquery.min.js"></script>
	<script src="./../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
	<!-- Core plugin JavaScript-->
	<script src="./../../vendor/jquery-easing/jquery.easing.min.js"></script>
	<!-- Custom scripts for all pages-->
	<script src="./../../js/sb-admin-2.min.js"></script>
	<script src="./js/ingredient.js"></script>
</body>

</html>