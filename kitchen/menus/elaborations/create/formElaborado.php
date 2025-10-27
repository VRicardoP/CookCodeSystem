<?php
require_once __DIR__ . '/../../../DBConnection.php';

/* Attempt to connect to MySQL database */
$link = DBConnection::connectDB();


$queryRecetas = "SELECT * FROM `recetas` WHERE `tipo` = 'Elaborado'";
$resultRecetas = $link->query($queryRecetas);
$listaRecetas = array();
while ($row = $resultRecetas->fetch(PDO::FETCH_ASSOC)) {
	// Agregar cada receta al array $listaRecetas
	$listaRecetas[] = array(
		'id' => $row['id'],
		'nombre' => $row['receta'],
		'num_raciones' => $row['num_raciones'],
		'imagen' => $row['imagen'],
		'caducidad' => $row['expira_dias'],
		'packaging' => $row['empaquetado'],
		'warehouse' => $row['localizacion'],
		// 'num_raciones' => $row['num_raciones'],
	);
}

$allRecetas = RecetasDao::getElaborados();



$image = null;
$imageErr = "";
$merma = 0;

$almacenElab = new AlmacenElaboraciones(
	$ID = 0,
	$tipoProduct = "",
	$nombreReceta = "",
	$packaging = "Bag",
	$productAmount = 1,
	$fechaElab = "",
	$caducidad = 0,
	$warehouse = "Freezer",
	$costCurrency = "Euro",
	$costPrice = 0,
	$saleCurrency = "Euro",
	$salePrice = 0,
	$codeContents = ""

);

$id_del_elaborado = 0;
$btnText = 'Save';

if (isset($_GET['edit_elab'])) {
	// Obtener el ID de la URL
	$id = $_GET['edit_elab'];
	$almacenElab =  AlmacenElaboracionesDao::select($id);
	$recetaDelElaborado = RecetasDao::select($almacenElab->getRecetaId());
	$id_del_elaborado = $id;
	$btnText = 'Edit';
}

$listIng = array();
$idRecetaOption = 1;
?>

<h1><strong>Elaboration</strong></h1>

<form method="post" id="formElaborado" enctype="multipart/form-data" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
	<div class="shadow-lg border rounded row justify-content-center m-5">
		<!-- Cabecera del formulario (Imagen) -->
		<div class="col-12 col-md-12 ">
			<!-- Campo oculto para enviar el ID mediante GET -->
			<input type="hidden" name="elaborado_id" id="elaborado_id" value="<?php echo htmlspecialchars($id_del_elaborado); ?>">

			<!-- El label simula un botón para seleccionar otra imagen -->
			<div class="form-group ">

				<div id="contenedorimagenCh">
					<img class="" src=<?= isset($recetaDelElaborado) ? $recetaDelElaborado->getImagen() : "./../../../svg/image.svg" ?> id="imagenCh" alt="Insert Image" />
				</div>

			</div>
		</div>

		<!-- Parte izquierda del formulario -->
		<div class=" text-left   col-12 col-md-6 ">

			<div class="form-group">
				<label for="fNameInput">Product name</label>
				<input type="text" id="fNameInput" name="fNameInput" list="list_recipes" data-id="<?= $almacenElab->getRecetaId()  ?>" class="campo-obligatorio" value="<?= $almacenElab->getFName() ?>">
				<datalist id="list_recipes">
					<?php
					foreach ($allRecetas as $receta) {
						echo "<option value='" . $receta->getReceta() . "' data-id='" . $receta->getId() . "'>" . $receta->getReceta() . "</option>";
					}
					?>

				</datalist>
			</div>

			<script>
				document.addEventListener('DOMContentLoaded', function() {
					var inputNombre = document.getElementById('fNameInput');
					var dataListOptions = document.querySelectorAll('#list_recipes option');

					inputNombre.addEventListener('input', function() {
						var value = inputNombre.value;
						var selectedOption = Array.from(dataListOptions).find(option => option.value === value);

						if (selectedOption) {
							var recetaId = selectedOption.getAttribute('data-id');
							inputNombre.setAttribute('data-id', recetaId);
							// console.log("Selected Recipe ID: " + recetaId);
						} else {
							inputNombre.removeAttribute('data-id');
							console.log("Recipe not found in the list.");
						}
					});
				});
			</script>

			<div class="form-group">
				<label>Number of packages</label>
				<input type="number" id="productamount" name="productamount" step="0.1" min="0" max="100000" value="<?= $almacenElab->getProductamount()  ?>" class="campo-obligatorio text-right-input" />
			</div>

			<div class="form-group">
				<label>Nº rations for package</label>
				<input type="number" id="numRaciones" name="numRaciones" step="0.1" min="0" max="100000" value="<?= $almacenElab->getRationsPackage()  ?>" class="campo-obligatorio text-right-input" />

			</div>
			<div class="form-group">
				<label>Production date</label>
				<input type="date" id="fechaElab" name="fechaElab" value="<?= (new DateTime($almacenElab->getFechaElab()))->format('Y-m-d')  ?>" class="campo-obligatorio" />
			</div>

			<?php
			$diasParaCaducidad = 0;
			if ($id_del_elaborado > 0) {
				$fechaElab = new DateTime($almacenElab->getFechaElab());
				$fechaCad = new DateTime($almacenElab->getFechaCad());

				// Calcular la diferencia
				$interval = $fechaElab->diff($fechaCad);

				// Obtener la diferencia en días
				$diasParaCaducidad = $interval->days;
			}

			?>

			<div class="form-group">
				<label>Expiration</label>
				<input type="number" id="caducidad" name="caducidad" value="<?= $diasParaCaducidad ?>" placeholder="0" class="campo-obligatorio text-right-input" readonly/>
				<label> days</label>
			</div>
		</div>

		<!-- Parte derecha del formulario -->
		<div class=" text-left   col-12 col-md-6 ">
			<div class="form-group">
				<label>Packaging</label>
				<?php
				$selectedPackaging = $almacenElab->getPackaging();

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
				$selectedWarehouse = $almacenElab->getWarehouse();

				?>

				<select name="warehouse" id="warehouse" style="max-width:90%;">
					<option value="Freezer" <?= $selectedWarehouse == 'Freezer' ? 'selected' : '' ?>>Freezer</option>
					<option value="Warehouse" <?= $selectedWarehouse == 'Warehouse' ? 'selected' : '' ?>>Warehouse</option>
					<option value="Final product area" <?= $selectedWarehouse == 'Final product area' ? 'selected' : '' ?>>Final product area</option>
					<option value="Dry" <?= $selectedWarehouse == 'Dry' ? 'selected' : '' ?>>Dry</option>
				</select>
			</div>
			<div class="form-group">

				<label>Cost price</label>
				<input type="number" id="costPrice" name="costPrice" step="0.01" min="0" max="100000" value="<?php echo htmlspecialchars($almacenElab->getCostPrice()) ?>" class="campo-obligatorio text-right-input" readonly />

				<?php
				$selectedCostCurrency = $almacenElab->getCostCurrency();

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
				<input type="number" id="salePrice" name="salePrice" step="0.01" min="0" max="1000000" value="<?= $almacenElab->getSalePrice() ?>" class="campo-obligatorio text-right-input" />

				<?php
				$selectedSaleCurrency = $almacenElab->getSaleCurrency();

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

			<div class="form-group">

				<input type="hidden" id="idReceta" name="idReceta" value="<?= $idRecetaOption ?>" />

			</div>
			<input type="text" id="ingredientData" name="ingredientData" style="display: none" />

			<hr style="border: 1px solid #ccc;">

			<div class="form-group text-center">
				<button type="submit" name="submit" id="submitElab" class="btn btn-primary submitBtn" style="width:20em; margin:0;" disabled><?= $btnText ?></button>
			</div>
		</div>
	</div>
</form>
	


	<script>
		// Lista de recetas
		var listaRecetas = <?php echo json_encode($listaRecetas); ?>;
	</script>
	<script>
		<?php
		$queryRecetaIngrediente = "SELECT * FROM `receta_ingrediente` WHERE `receta` = $idRecetaOption";
		$resultRecetaIngrediente = $link->query($queryRecetaIngrediente);
		$listaRecetaIngrediente = array();
		while ($row = $resultRecetaIngrediente->fetch(PDO::FETCH_ASSOC)) {

			$listaRecetaIngrediente[] = array(
				'id' => $row['ingrediente'],

			);
		}

		$listaIngrediente = array();

		for ($i = 0; $i < count($listaRecetaIngrediente); $i++) {
			$ingredienteId = $listaRecetaIngrediente[$i]['id'];
			$queryIngrediente = "SELECT * FROM `ingredients` WHERE `ID` = $ingredienteId";
			$resultIngrediente = $link->query($queryIngrediente);

			while ($row = $resultIngrediente->fetch(PDO::FETCH_ASSOC)) {
				$listaIngrediente[] = array(
					'id' => $row['ID'],
					'fName' => $row['fName'],

				);
			}
		}

		?>
	</script>

	<script>
		var ingredientes = []; // Array para almacenar los ingredientes
	</script>
	<script>
		<?php

		$queryRecetaIngediente = "SELECT * FROM `receta_ingrediente`";

		$resultRecetaIngediente = $link->query($queryRecetaIngediente);

		$listaRecetaIngrediente = array();


		while ($row = $resultRecetaIngediente->fetch(PDO::FETCH_ASSOC)) {

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


		while ($row = $resultIngrediente->fetch(PDO::FETCH_ASSOC)) {
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

	<!-- Bootstrap core JavaScript-->
	
	<script>



document.addEventListener('DOMContentLoaded', function () {

calcularCosteSegunRaciones(listaRecetas, listaRecetaIngrediente, listaIngredients);
calcularCosteSegunPaquetes(listaRecetas, listaRecetaIngrediente, listaIngredients);

mostrarDatosReceta(listaRecetas, listaRecetaIngrediente, listaIngredients);

// Obtener todos los campos de entrada obligatorios
var camposObligatorios = document.querySelectorAll('.campo-obligatorio');

// Obtener el botón de enviar
var botonEnviar = document.getElementById('submitElab');

// Agregar un evento input a cada campo de entrada
camposObligatorios.forEach(function (campo) {
	campo.addEventListener('input', function () {
		// Verificar si todos los campos obligatorios están llenos
		var todosLlenos = Array.from(camposObligatorios).every(function (campo) {
			return campo.value.trim() !== ''; // Verificar si el valor del campo no está vacío después de recortar los espacios en blanco
		});

		// Habilitar o deshabilitar el botón de enviar según si todos los campos obligatorios están llenos
		botonEnviar.disabled = !todosLlenos;
	});
});
});

	</script>

	<script  src="./js/formElaborado.js" ></script>