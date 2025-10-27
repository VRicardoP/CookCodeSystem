<?php
require_once __DIR__ . '/../../../DBConnection.php';

/* Attempt to connect to MySQL database */
$link = DBConnection::connectDB();

$queryIngredientes = "SELECT * FROM `ingredients`";

$resultIngredientes = $link->query($queryIngredientes);

// Procesar el campo 'atr_valores_tienda'


$listaIngredientes = array();

while ($row = $resultIngredientes->fetch(PDO::FETCH_ASSOC)) {
	// Agregar cada receta al array $listaRecetas

	$atrValores = explode(',', $row['atr_valores_tienda']); // Convertir la cadena en un array
	$listaIngredientes[] = array(
		'id' => $row['ID'],
		'nombre' => $row['fName'],
		'unidad' => $row['unidad'],
		'costPrice' => $row['costPrice'],
		'salePrice' => $row['salePrice'],
		'imagen' => $row['image'],
		'caducidad' => $row['expira_dias'],
		'packaging' => $row['packaging'],
		'warehouse' => $row['warehouse'],
		'saleCurrency' => $row['saleCurrency'],
		'atr_valores_tienda' => $atrValores
	);
}


$allIngredientes = IngredientesDao::getAll();

function getUsernameFromEmail($email)
{
	$find = '@';
	$pos = strpos($email, $find);
	$username = substr($email, 0, $pos);
	return $username;
}


$tempDir = "";
$email = "";
$fileName = "";
$merma = 0.0;
$unidad = "";
$image = null;
$imageErr = "";
$diasParaCaducidad = 0;


$almacenIng = new AlmacenIngredientes(
	$ID = 0,
	$tipoProd = "",
	$fName = "",
	$packaging = "Bag",
	$cantidad = 0,
	$fechaElab = "",
	$fechaCad = "",
	$warehouse = "Freezer",
	$costCurrency = "Euro",
	$costPrice = 0,
	$saleCurrency = "Euro",
	$salePrice = 0,
	$codeContents = "",
	$ingrediente_id = 0,
	$cantidadPaquete = 0
);


$id_del_ingrediente = 0;
$btnText = 'Save';

if (isset($_GET['edit_ing'])) {
	// Obtener el ID de la URL
	$id = $_GET['edit_ing'];
	$almacenIng =  AlmacenIngredientesDao::select($id);
	$ing = IngredientesDao::select($almacenIng->getIngredienteId());
	$unitIngredientEdit = $ing->getUnidad();

	// Verificamos si comienza con "Und"
	if (strpos($unitIngredientEdit, 'Und') === 0) {
		// Si es así, partimos el string por el guion
		$parts = explode('-', $unitIngredientEdit);

		// La primera parte será "Und"
		$firstPart = $parts[0];

		// Si necesitas la segunda parte (el valor numérico), puedes hacer:
		$secondPart = isset($parts[1]) ? $parts[1] : null; // Asegura que exista una segunda parte
		$unitIngredientEdit = $firstPart;
	}



	$id_del_ingrediente = $id;
	$btnText = 'Edit';
}


?>



<h1><strong>Ingredient</strong></h1>

<form id="formIngrediente" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">


	<div class="shadow-lg border rounded row justify-content-center m-5">
		<div class="col-12 col-md-12">

			<div class="form-group ">

				<div id="contenedorimagenCh">
					<img class="" src="./../../../svg/image.svg" id="imagenChIng" alt="Insert Image" />
				</div>

			</div>


			<!-- Campo oculto para enviar el ID  -->
			<input type="hidden" name="ingrediente_id" id="ingrediente_id" value="<?php echo htmlspecialchars($id_del_ingrediente); ?>">

		</div>

		<div class=" text-left   col-12 col-md-6 ">


			<div class="form-group">
				<label>Product name</label>
				<input type="text" id="fNameInputIng" name="fNameInputIng" list="list_ing" data-id="<?= $almacenIng->getIngredienteId()  ?>" class="campo-obligatorio-ing" value="<?= isset($almacenIng) ? $almacenIng->getFName() : "" ?>">
				<datalist id="list_ing">
					<?php
					foreach ($allIngredientes as $ingrediente) {
						echo "<option  data-id=" . $ingrediente->getId() . "  data-und=" . $ingrediente->getUnidad() . " >". $ingrediente->getFName()." </option>";
					}
					?>

				</datalist>
			</div>

			<script>
				document.addEventListener('DOMContentLoaded', function() {
					var inputNombre = document.getElementById('fNameInputIng');
					var dataListOptions = document.querySelectorAll('#list_ing option');


					inputNombre.addEventListener('input', function() {
						var value = inputNombre.value;
						var selectedOption = Array.from(dataListOptions).find(option => option.value === value);

						if (selectedOption) {
							var ingId = selectedOption.getAttribute('data-id');
							inputNombre.setAttribute('data-id', ingId);
							console.log("Selected Ingredient ID: " + ingId);
							var ingUnd = selectedOption.getAttribute('data-und');
							inputNombre.setAttribute('data-und', ingUnd);

							if (ingUnd.startsWith('Und')) {
								// Si el valor de ingUnd empieza con "Und", lo dividimos por el guion
								let parts = ingUnd.split('-');

								// Obtenemos la primera parte, que será "Und"
								let firstPart = parts[0];

								// Si necesitas la segunda parte (el valor numérico), también puedes hacer:
								let secondPart = parts.length > 1 ? parts[1] : null; // Verificamos que exista una segunda parte

								// Asignamos solo la primera parte a ingUnd
								ingUnd = firstPart;
							}


							var labelUnidad = document.getElementById('labelUnidad');
							labelUnidad.textContent = "Nº " + ingUnd + " for package";


						} else {
							inputNombre.removeAttribute('data-id');
							console.log("Ingredient not found in the list.");
						}
					});
				});

				// Lista de ingredientes
				var listaIngredientes = <?php echo json_encode($listaIngredientes); ?>;

				function filtrarIngredientes() {
					var input, filter, sugerenciasDiv, i, txtValue;
					input = document.getElementById('fNameInputIng');
					filter = input.value.toUpperCase();
					sugerenciasDiv = document.getElementById('sugerencias');
					sugerenciasDiv.innerHTML = ""; // Limpiar las sugerencias
					for (i = 0; i < listaIngredientes.length; i++) {
						txtValue = listaIngredientes[i]['nombre'];
						if (txtValue.toUpperCase().indexOf(filter) > -1) {
							// Mostrar el ingrediente como sugerencia
							var sugerencia = document.createElement('div');
							sugerencia.innerHTML = txtValue;
							sugerencia.setAttribute('onclick', 'seleccionarIngrediente(' + listaIngredientes[i]['id'] + ', "' + txtValue + '", "' + listaIngredientes[i]['unidad'] + '")');

							sugerenciasDiv.appendChild(sugerencia);
						}
					}
				}

				function seleccionarIngrediente(id, nombre, unidad) {
					// Mostrar el nombre del ingrediente seleccionado dentro del campo de texto
					var input = document.getElementById('fNameInputIng');
					input.value = nombre;
					input.setAttribute('data-id', id);

					var units = document.getElementById('units');
					var txtUnits = document.createTextNode(unidad);
					units.appendChild(txtUnits);


					console.log("Ingrediente seleccionado con ID:", id);
					// Ocultar las sugerencias después de seleccionar un ingrediente
					document.getElementById('sugerencias').innerHTML = "";
				}
			</script>

			<div class="form-group">
				<label>Product amount</label>
				<input type="number" id="productamountIng" name="productamountIng" step="0.1" min="0" max="100000" value="<?= $almacenIng->getProductamount()  ?>" class="campo-obligatorio-ing text-right-input" />
				<label type="text" id="units" name="units">
			</div>
			<div class="form-group">

				<label id="labelUnidad">
					<?php if (isset($unitIngredientEdit)) : ?>
						Nº <?= $unitIngredientEdit ?> for package
					<?php else : ?>
						Nº kg for package
					<?php endif; ?>
				</label>
				<div class="form-group">

					<select id="atrValoresTiendaSelect" name="atrValoresTienda[]">
						<!-- Opciones se llenarán dinámicamente -->
					</select>
				</div>
			</div>

			<div class="form-group">
				<label>Production date</label>
				<input type="date" id="fechaElabIng" name="fechaElabIng" value="<?= (new DateTime($almacenIng->getFechaElab()))->format('Y-m-d')  ?>" class="campo-obligatorio-ing" />
			</div>

			<div class="form-group">
				<label>Expiration</label>
				<input type="number" id="caducidadIng" name="caducidadIng" value="<?= $diasParaCaducidad ?>" placeholder="0" class="campo-obligatorio-ing text-right-input" />
				<label> days</label>
			</div>

		</div>

		<div class=" text-left   col-12 col-md-6 ">

			<div class="form-group">
				<label>Packaging</label>
				<?php
				isset($almacenIng) ? $selectedPackaging = $almacenIng->getPackaging() : $selectedPackaging = ''

				?>

				<select name="packagingIng" id="packagingIng" style="max-width:90%;">
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
				isset($almacenIng) ? $selectedWarehouse = $almacenIng->getWarehouse() : $selectedWarehouse = ''

				?>

				<select name="warehouseIng" id="warehouseIng" style="max-width:90%;">
					<option value="Freezer" <?= $selectedWarehouse == 'Freezer' ? 'selected' : '' ?>>Freezer</option>
					<option value="Warehouse" <?= $selectedWarehouse == 'Warehouse' ? 'selected' : '' ?>>Warehouse</option>
					<option value="Final product area" <?= $selectedWarehouse == 'Final product area' ? 'selected' : '' ?>>Final product area</option>
					<option value="Dry" <?= $selectedWarehouse == 'Dry' ? 'selected' : '' ?>>Dry</option>
				</select>
			</div>
			<div class="form-group">
				<label>Cost price</label>
				<input type="number" id="costPriceIng" name="costPriceIng" step="0.01" min="0" max="1000000" value="<?= isset($almacenIng) ? $almacenIng->getCostPrice() : 0 ?>" class="campo-obligatorio-ing text-right-input" readonly />


				<?php
				isset($almacenIng) ? $selectedCostCurrency = $almacenIng->getCostCurrency() : $selectedCostCurrency = ''

				?>

				<!-- Select for costCurrency -->
				<select name="costCurrencyIng" id="costCurrencyIng" style="max-width:90%;">
					<option value="Euro" <?= $selectedCostCurrency == 'Euro' ? 'selected' : '' ?>>Euro</option>
					<option value="Dirham" <?= $selectedCostCurrency == 'Dirham' ? 'selected' : '' ?>>Dirham</option>
					<option value="Yen" <?= $selectedCostCurrency == 'Yen' ? 'selected' : '' ?>>Yen</option>
					<option value="Dolar" <?= $selectedCostCurrency == 'Dolar' ? 'selected' : '' ?>>Dolar</option>
				</select>
			</div>
			<div class="form-group">
				<label>Sale price</label>
				<input type="number" id="salePriceIng" name="salePriceIng" step="0.01" min="0" max="1000000" value="<?= isset($almacenIng) ? $almacenIng->getSalePrice() : 0 ?>" class="campo-obligatorio-ing text-right-input" />

				<?php
				isset($almacenIng) ? $selectedSaleCurrency = $almacenIng->getSaleCurrency() : $selectedSaleCurrency = ''

				?>
				<!-- Select for saleCurrency -->
				<select name="saleCurrencyIng" id="saleCurrencyIng" style="max-width:90%;">
					<option value="Euro" <?= $selectedSaleCurrency == 'Euro' ? 'selected' : '' ?>>Euro</option>
					<option value="Dirham" <?= $selectedSaleCurrency == 'Dirham' ? 'selected' : '' ?>>Dirham</option>
					<option value="Yen" <?= $selectedSaleCurrency == 'Yen' ? 'selected' : '' ?>>Yen</option>
					<option value="Dolar" <?= $selectedSaleCurrency == 'Dolar' ? 'selected' : '' ?>>Dolar</option>
				</select>
			</div>

		</div>

		<div class=" text-center  col-12 col-md-12 ">
			<input type="text" id="ingredientData" name="ingredientData" style="display: none" />

			<hr style="border: 1px solid #ccc;">

			<div class="form-group text-center">
				<button type="submit" name="submitIng" id="submitIng" class="btn btn-primary submitBtn" style="width:20em; margin:0;" disabled><?= $btnText ?></button>
			</div>
		</div>
	</div>

</form>



<script defer>
	

	document.addEventListener('DOMContentLoaded', function() {
		var listaIngredientes = <?php echo json_encode($listaIngredientes); ?>;
		mostrarDatosIngrediente(listaIngredientes);
		var inputProductAmount = document.getElementById('productamountIng');
		var inputName = document.getElementById("fNameInputIng");
		inputName.addEventListener('change', function(){
			var coste = 0;
			var costPrice = document.getElementById('costPriceIng');
			var inputPesoPaquete = document.getElementById('atrValoresTiendaSelect');

			var costeYUnidad = costeUnidadIngredienteSeleccionado(listaIngredientes);
			console.log('costeYUnidad[0][]' + costeYUnidad[0]['costPrice']);
			console.log('inputProductAmount.value' + inputProductAmount.value);
			coste = costeYUnidad[0]['costPrice'] * inputProductAmount.value * inputPesoPaquete.value;
			costPrice.value = coste.toFixed(2);
			ventaSugeridaIng();
		});
		inputProductAmount.addEventListener('change', function() {
			var coste = 0;
			var sale = 0;
			var costPrice = document.getElementById('costPriceIng');
			var salePrice = document.getElementById('salePriceIng');
			var inputPesoPaquete = document.getElementById('atrValoresTiendaSelect');

			var costeYUnidad = costeUnidadIngredienteSeleccionado(listaIngredientes);
			console.log('costeYUnidad[0][]' + costeYUnidad[0]['costPrice']);
			console.log('inputProductAmount.value' + inputProductAmount.value);
			coste = costeYUnidad[0]['costPrice'] * inputProductAmount.value * inputPesoPaquete.value;
			costPrice.value = coste.toFixed(2);

			sale = costeYUnidad[0]['salePrice'] * inputProductAmount.value * inputPesoPaquete.value;
			salePrice.value = sale.toFixed(2);
		//	ventaSugeridaIng();
		});




		var inputPesoPaquete = document.getElementById('atrValoresTiendaSelect');
		inputPesoPaquete.addEventListener('change', function() {

			var costPrice = document.getElementById('costPriceIng');
			var inputProductAmount = document.getElementById('productamountIng');
		
			var salePrice = document.getElementById('salePriceIng');
			var costeYUnidad = costeUnidadIngredienteSeleccionado(listaIngredientes);
			console.log('costeYUnidad[0][]' + costeYUnidad[0]['costPrice']);
			console.log('inputProductAmount.value' + inputProductAmount.value);

			var coste = costeYUnidad[0]['costPrice'] * inputPesoPaquete.value * inputProductAmount.value;
			costPrice.value = coste.toFixed(2);

			sale = costeYUnidad[0]['salePrice'] * inputProductAmount.value * inputPesoPaquete.value;
			salePrice.value = sale.toFixed(2);
			//ventaSugeridaIng();

		});

		// Obtener todos los campos de entrada obligatorios
		var camposObligatorios = document.querySelectorAll('.campo-obligatorio-ing');

		// Obtener el botón de enviar
		var botonEnviar = document.getElementById('submitIng');

		// Agregar un evento input a cada campo de entrada
		camposObligatorios.forEach(function(campo) {
			campo.addEventListener('input', function() {
				// Verificar si todos los campos obligatorios están llenos
				var todosLlenos = Array.from(camposObligatorios).every(function(campo) {
					return campo.value.trim() !== ''; // Verificar si el valor del campo no está vacío después de recortar los espacios en blanco
				});

				// Habilitar o deshabilitar el botón de enviar según si todos los campos obligatorios están llenos
				botonEnviar.disabled = !todosLlenos;
			});
		});
	});
</script>


<script  src="./js/formIngredientes.js" defer></script>