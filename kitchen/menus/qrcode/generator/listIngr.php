<?php
require __DIR__ . '/../../models/tagsElaboraciones.php';
require_once __DIR__ . '/../../models/tagsElaboracionesDao.php';

require __DIR__ . '/../../models/tagsPreelaboraciones.php';
require_once __DIR__ . '/../../models/tagsPreelaboracionesDao.php';

require __DIR__ . '/../../models/ingredientes.php';
require_once __DIR__ . '/../../models/ingredientesDao.php';

require __DIR__ . '/../../models/tagselaboraciones_ingredientes.php';
require_once __DIR__ . '/../../models/tagselaboraciones_ingredientesDao.php';

require __DIR__ . '/../../models/tagspreelaboraciones_ingredientes.php';
require_once __DIR__ . '/../../models/tagspreelaboraciones_ingredientesDao.php';

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET["ing_elab"])) {
        $id = $_GET["ing_elab"];
        $tagElaborado = TagsElaboracionesDao::select($id);
        var_dump($tagElaborado->getFName());
        $listIngredientesId = Tagselaboraciones_ingredientsDao::getIngredientByTagElaboracionId($id);
    } elseif (isset($_GET["ing_preelab"])) {
        $id = $_GET["ing_preelab"];
        $tagElaborado = TagsPreelaboracionesDao::select($id);
        $listIngredientesId = TagsPreelaboraciones_ingredientsDao::getIngredientIdsByTagPreelaboracionId($id);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bootstrap.min.css">
	<script src="https://kit.fontawesome.com/2e2cc992ca.js" crossorigin="anonymous"></script>
	<link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
	<!-- Custom styles for this template-->
	<link href="/../../css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        header {
            padding: 10px;
            background-color: #c19e2e;
            margin-bottom: 50px;
        }
        h1 {
            text-align: center;
            margin-bottom: 50px;
        }
        h2 {
            text-align: center;
        }
        h3 {
            margin-top: 50px;
            text-align: center;
        }
        .container {
            display: flex;
            justify-content: center;
           
            height: 100vh;
        }
        .card {
            width: 100%;
            max-width: 300px; /* ajusta el ancho máximo según tus necesidades */
            max-height: 500px;
        }
        a {
            background-color: #eee349;
        }
    </style>
 
    <title>Ingredientes/alergenos</title>
</head>
<body>
<header class="bg-primary">
    <a href='tickets.php' class="btn btn-primary">Tickets</a>
    <h1>Ingredientes/alergenos</h1>
</header>

<div class="container">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h2 class="m-0 font-weight-bold text-primary"><?= isset($tagElaborado) ? $tagElaborado->getFName() : "" ?></h2>
        </div>
        <div class="card-body">
            <div class="container">
                <ul>
                    <?php
                    if(isset($listIngredientesId) && is_array($listIngredientesId) && !empty($listIngredientesId)) {
                        foreach($listIngredientesId as $ingrediente) {
                            echo "<li>Ingrediente: " . $ingrediente['ingrediente'] . " Cantidad: " . $ingrediente['cantidad'] . " " . $ingrediente['unidad'] . "<br> Alergeno: " . $ingrediente['alergeno'] . "</li>";
                        }
                    } else {
                        echo "<li>No hay ingredientes disponibles.</li>";
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>
</div>
</script>
	  <!-- Bootstrap core JavaScript-->
	  <script src="/../../vendor/jquery/jquery.min.js"></script>
    <script src="/../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="/../../vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="/../../js/sb-admin-2.min.js"></script>

</body>
</html>