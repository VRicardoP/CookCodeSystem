<?php
require_once __DIR__ . '/../../DBConnection.php';

try {
    // Obtener conexión PDO
    $conn = DBConnection::connectDB();
    
    if ($conn) {
        // Consultas SQL
        $queryIng = "SELECT * FROM `ingredients`";
        $queryPrep = "SELECT * FROM `recetas` WHERE `tipo` = 'Pre-Elaborado'";
        $queryElab = "SELECT * FROM `recetas` WHERE `tipo` = 'Elaborado'";
        $queryRecIng = "SELECT * FROM `receta_ingrediente`";

        // Ejecutar consultas y obtener resultados
        $ingredientes = $conn->query($queryIng)->fetchAll(PDO::FETCH_ASSOC);
        $preelaborados = $conn->query($queryPrep)->fetchAll(PDO::FETCH_ASSOC);
        $elaborados = $conn->query($queryElab)->fetchAll(PDO::FETCH_ASSOC);
        $listaRecIng = $conn->query($queryRecIng)->fetchAll(PDO::FETCH_ASSOC);
        
       
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en" style="overflow: hidden;">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Custom fonts for this template-->
  <script src="https://kit.fontawesome.com/2e2cc992ca.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="./../../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="./../../css/navs.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/platingList.css">
   
    <script defer type="module" src="./js/platingList.js"></script>
    <title>Cook code</title>
    <link rel="icon" type="image/png" href="./../../img/logo.png">
    <script src="js/mostrarPlatos.js"></script>
</head>

<body>
<?php include './../../includes/session.php'; ?>
<?php include './../../includes/navs.php'; ?>

    <main>
        <select name="Plato" id="tipo_plato" style="width: 200px; padding: 10px; padding-left:20px; padding-right:10px;   font-size: 18px; border-radius: 25px; border: 3px solid #007934;">
            <option value="ing">Ingredients</option>
            <option value="pre">Pre-Prepared</option>
            <option value="elab">Elaborations</option>
        </select>
          
        <div id="lista" style="witdh: 100%; margin-top: 30px; margin-bottom: 60px"></div>

       <!-- <dish-list></dish-list> -->
    </main>
</body>
<script src="js/mostrarPlatos.js"></script>

<script>
    let ing = <?php echo json_encode($ingredientes); ?>;
    let pre = <?php echo json_encode($preelaborados); ?>;
    let elab = <?php echo json_encode($elaborados); ?>;
    let recIng = <?php echo json_encode($listaRecIng); ?>;
</script>

<script src="js/mostrarPlatos.js"></script>
</html>