<?php
echo "<!-- formPreelaborado.php cargado -->";
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../../../DBConnection.php';

/* Attempt to connect to MySQL database */
$link = DBConnection::connectDB();
require_once __DIR__ . '/../../../models/recetasDao.php';

// Llamamos a la base de datos y guardamos todos los preelaborados en un array ($listaPre)

$query = "SELECT * FROM `recetas` WHERE `tipo` = 'Pre-Elaborado'";

$resultPre = $link->query($query);
$listaPre = array();
while ($row = $resultPre->fetch(PDO::FETCH_ASSOC)) {
	$listaPre[] = array(
		'id' => $row['id'],
		'imagen' => $row['imagen'],
		'nombre' => $row['receta'],
		'raciones' => $row['num_raciones'],
		'caducidad' => $row['expira_dias'],
		'empaquetado' => $row['empaquetado'],
		'localizacion' => $row['localizacion'],
		'num_raciones' => $row['num_raciones'],
	);
}

// Guardamos todos los ingredientes de las recetas en un array ($listaPreIng)

$queryPreIng = "SELECT * FROM `receta_ingrediente`";
$resultPreIng = $link->query($queryPreIng);
$listaPreIng = array();
while ($row = $resultPreIng->fetch(PDO::FETCH_ASSOC)) {
	$listaPreIng[] = array(
		'receta_id' => $row['receta'],
		'ingrediente_id' => $row['ingrediente'],
		'cantidad' => $row['cantidad'],
	);
}

// Guardamos todos los ingredientes generales para usar sus datos ($listaIng)

$queryIng = "SELECT * FROM `ingredients`";
$resultIng = $link->query($queryIng);
$listaIng = array();
while ($row = $resultIng->fetch(PDO::FETCH_ASSOC)) {
	$listaIng[] = array(
		'id' => $row['ID'],
		'nombre' => $row['fName'],
		'merma' => $row['merma'],
		'precio' => $row['costPrice'],
	);
}

// Llamamos a la función de getPreelaborados de RecetasDao para obtener todos los preelaborados, usaremos esta variable para definir los preelaborados definidos en el selector del formulario
$allPre = RecetasDao::getPreelaborados();

?>

<h1><strong>Elaboration</strong></h1>

<form method="POST" id="formPre" enctype="multipart/form-data" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
	<div class="shadow-lg border rounded row justify-content-center m-5">
		<!-- Cabecera del formulario (Imagen) -->
		<div class="col-12 col-md-12">
			<div class="form-group">
				<div id="contenedorimagenCh">
					<img id="imagenPre" src="./../../../svg/image.svg" alt="Imagen del Preelaborado">
				</div>
			</div>
		</div>

		<!-- Parte izquierda del formulario -->
		<div class="text-left col-12 col-md-6">
			<div class="form-group">
				<label for="nombrePre">Product name</label>
				<input type="text" id="nombrePre" name="nombrePre" list="listPre" class="obligatorioPre"/>
				<datalist id="listPre">
					<?php
						foreach ($allPre as $pre){
							echo "<option value='" . $pre->getReceta() ."'>";
						}
					?>
				</datalist>
			</div>
			<div class="form-group">
				<label for="cantidadPre">Number of packages</label>
				<input type="number" id="cantidadPre" name="cantidadPre" step="0.1" min="0" max="100000" value="1" class="obligatorioPre text-right-input"/>
			</div>
			<div class="form-group">
				<label for="racionesPre">Nº rations for package</label>
				<input type="number" id="racionesPre" name="racionesPre" step="0.1" min="0" max="100000" value="1" class="obligatorioPre text-right-input"/>
			</div>
			<div class="form-group">
				<label for="fechaElabPre">Production date</label>
				<input type="date" id="fechaElabPre" name="fechaElabPre" value="<?= date('Y-m-d') ?>"/>
			</div>
			<div class="form-group">
				<label for="caducidadPre">Expiration</label>
				<input type="number" id="caducidadPre" name="caducidadPre" value="0" class="obligatorioPre text-right-input" readonly/>
				<label for="">days</label>
			</div>
		</div>

		<!-- Parte derecha del formulario -->
		<div class="text-left col-12 col-md-6">
			<div class="form-group">
				<label for="empaquetadoPre">Packaging</label>
				<select name="empaquetadoPre" id="empaquetadoPre">
					<option value="Bag">Bag</option>
					<option value="Pack">Pack</option>
					<option value="Box">Box</option>
					<option value="Bottle">Bottle</option>
					<option value="Can">Can</option>
				</select>
			</div>
			<div class="form-group">
				<label for="localizacionPre">Select localization of product</label>
				<select name="localizacionPre" id="localizacionPre">
					<option value="Freezer">Freezer</option>
					<option value="Warehouse">Warehouse</option>
					<option value="Final product area">Final product area</option>
					<option value="Dry">Dry</option>
				</select>
			</div>
			<div class="form-group">
				<label for="precioCostPre">Cost price</label>
				<input type="number" id="precioCostPre" name="precioCostPre" step="0.01" min="0" max="100000" value="0" class="obligatorioPre text-right-input" readonly />
				<select name="monedaCost" id="monedaCost" style="max-width:90%;">
					<option value="Euro">Euro</option>
					<option value="Dirham">Dirham</option>
					<option value="Yen">Yen</option>
					<option value="Dollar">Dollar</option>
				</select>
			</div>
			<div class="form-group">
				<label for="precioVentaPre">Sale price</label>
				<input type="number" id="precioVentaPre" name="precioVentaPre" step="0.01" min="0" max="100000" value="0" class="obligatorioPre text-right-input" />
				<select name="monedaVenta" id="monedaVenta" style="max-width:90%;">
					<option value="Euro">Euro</option>
					<option value="Dirham">Dirham</option>
					<option value="Yen">Yen</option>
					<option value="Dollar">Dollar</option>
				</select>
			</div>
		</div>

		<!-- Parte inferior del formulario (Botón) -->
		<div class="text-center col-12 col-md-12">
			<hr style="border: 1px solid #ccc;">
			<div class="form-group text-center">
				<input type="hidden" name="idPre" id="idPre" value="0"/>
				<button type="submit" id="submitPre" name="submitPre" class="btn btn-primary submitBtn" style="width:20rem; margin:0" disabled>Save</button>
			</div>
		</div>
	</div>
</form>


<!-- Enlace al archivo JS -->
<script src="js/formPreelaborado.js" defer></script>

<script defer>
	var listaPre = <?= json_encode($listaPre) ?>;
	var listaPreIng = <?= json_encode($listaPreIng) ?>;
	var listaIng = <?= json_encode($listaIng) ?>;
	
	mostrarDatosPreelaborados(listaPre, listaPreIng, listaIng);
	activarBoton();
</script>