<?php

require __DIR__ . '/../../models/almacenElaboraciones.php';
require_once __DIR__ . '/../../models/almacenElaboracionesDao.php';

require __DIR__ . '/../../models/almacenIngredientes.php';
require_once __DIR__ . '/../../models/almacenIngredientesDao.php';

require_once __DIR__ . '/../../DBConnection.php';

$f = "visit.php";
if (!file_exists($f)) {
    touch($f);
    $handle = fopen($f, "w");
    fwrite($handle, 0);
    fclose($handle);
}

include('./../qrcode/generator/libs/phpqrcode/qrlib.php');

$logout = "./../login/logout.php";

// ✅ Conexión con la base de datos de empresa_usuarios
try {
    $connEmpresa = new PDO(
        "mysql:host=localhost;dbname=empresa_usuarios",
        "root",
        "",
        array(PDO::ATTR_PERSISTENT => true)
    );
    $connEmpresa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection to empresa_usuarios failed: " . $e->getMessage());
}

// Consulta empresa
$queryEmpresa = "SELECT * FROM empresa WHERE ID = 1;";
$stmtEmpresa = $connEmpresa->prepare($queryEmpresa);
$stmtEmpresa->execute();
$rowEmpresa = $stmtEmpresa->fetch(PDO::FETCH_ASSOC);

if ($rowEmpresa) {
    $nombreEmpresa = $rowEmpresa["nombre"];
    $direccionEmpresa = $rowEmpresa["direccion"];
    $codigoPostalEmpresa = $rowEmpresa["codigo_postal"];
    $ciudadEmpresa = $rowEmpresa["ciudad"];
    $paisEmpresa = $rowEmpresa["pais"];
    $imagenEmpresa = $rowEmpresa["imagen"];
}

// ✅ Conexión principal usando DBConnection (a "kitchentag")
$conn = DBConnection::connectDB();
if (!$conn) {
    die("Error de conexión con la base de datos kitchentag.");
}

$tipo = "";

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["elab"])) {
    $id = $_GET['elab'];
    $tipo = "elab";

    $tag = AlmacenElaboracionesDao::select($id);
    $codeContents = $tag->getCodeContents();
    $cantidad = $tag->getProductamount();

    $tempDir = "temp/";
    $fileName = $tag->getID();
    QRcode::png($codeContents, $tempDir . $fileName . '.png', QR_ECLEVEL_M, 3, 4);
    $unidadIngrediente = "";

} else if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["tag_ing"])) {
    $id = $_GET['tag_ing'];
    $tipo = "tag_ing";

    $tag = AlmacenIngredientesDao::select($id);
    $cantidad = $tag->getProductamount();

    $codeContents = $tag->getCodeContents();
    $tempDir = "temp/";
    $fileName = $tag->getID();
    QRcode::png($codeContents, $tempDir . $fileName . '.png', QR_ECLEVEL_M, 3, 4);
}

include 'consultasBdPrint.php';
?>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Cook code</title>
    <link rel="icon" type="image/png" href="./../../img/logo.png">
    <!-- Custom fonts for this template-->
    <!-- <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">-->
    <script src="https://kit.fontawesome.com/2e2cc992ca.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="./../../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="./../../css/navs.css" rel="stylesheet">
    <link href="./../../css/elaborations/print.css" rel="stylesheet">
<style>

    
/* Button Styling */
.btn-primary {
    background-color: #258f8ff7;
    color: #fff;
    border: none;
    padding: 8px 12px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.btn-primary:hover {
    background-color: #0056b3;
}
</style>
</head>

<body id="page-top">
    <?php include './../../includes/session.php'; ?>
    <?php include './../../includes/navs.php'; ?>
    <?php insertarTopNav('./', './../../svg/orders_Black.svg', 'Tags list'); ?>

    <?php insertarTopNav('./create', './../../svg/recipe_Black.svg', 'Create'); ?>

    <div id="content-wrapper" class="d-flex flex-column">


        <div class="content">
            <div class="content_tag ">

                <div class="left-part ">
                    <div>
                        <!-- Imagen de empresa -->

                        <img src="./../../img/cookcodeblack.jpg" style="width: 230px; height:80px" />
                    </div>





                    <div class="div-direction-row">


                        <!-- Dirrección de empresa -->


                        <div class="address">
                            <span>
                                <?php echo isset($nombreEmpresa)  ? $nombreEmpresa : '#######' ?>

                            </span><br>
                            <span>
                                <?php echo isset($direccionEmpresa)  ? $direccionEmpresa : '#######' ?>
                            </span><br>
                            <span>
                                <?php echo isset($codigoPostalEmpresa)  ? $codigoPostalEmpresa : '#######' ?>
                                <?php echo isset($ciudadEmpresa)  ? $ciudadEmpresa : '#######' ?>
                                <?php echo isset($paisEmpresa)  ? $paisEmpresa : '#######' ?>



                            </span>
                        </div>


                    </div>
                    <hr>
                    <div class="div-name">
                        <?php
                        echo '<h5><strong>';
                        echo isset($nameElaborado) ? $nameElaborado : '########';
                        echo '</strong></h5>';
                        ?>

                    </div>


                </div>
                <div class="right-part ">
                    <div class="div_conser">
                        <p class="title_conser">Storage/Conservación:</p>
                        <div>
                            <div class="language-ellipse" lang="en"></div>
                            <span class="text_small"><?php echo isset($warehouseElaborado) ? $warehouseElaborado : '#####'; ?></span>

                            <div class="language-ellipse" lang="es"></div>
                            <span class="text_small"><?php echo isset($traducciones['espanol']) ? $traducciones['espanol'] : '#####'; ?></span><br>

                        </div>


                    </div>

                    <div class="div_ing">
                        <p class="title_ing">Ingredients/Ingredientes:</p>
                        <div class="content_ing">
                            <div class="language-ellipse" lang="en"></div>
                            <?php
                            $i = 0;


                            if (isset($ingredientesIngles) && is_array($ingredientesIngles) && !empty($ingredientesIngles)) {
                                foreach ($ingredientesIngles as $ing) {

                                    if (isset($alergenosNameIngles[$i]) && $alergenosNameIngles[$i] != "None") {
                                        echo '<span class="text_small" style="font-weight: 1000;">';
                                        echo $ing . '(' . $alergenosNameIngles[$i] . ')';
                                    } else {
                                        echo '<span class="text_small">';
                                        echo $ing;
                                    }

                                    // Si este no es el último ingrediente, añadir un guion
                                    if ($i < $totalIngredientes - 1) {
                                        echo '-';
                                        echo '</span>';
                                    } else {
                                        echo '</span>';
                                    }


                                    $i++;
                                }
                            } else {
                                echo '<span class="text_small">';
                                echo "#####";
                                echo '</span>';
                            }


                            ?>



                            <div class="language-ellipse" lang="es"></div>
                            <?php
                            $i = 0;

                            if (
                                isset($ingredientes) && is_array($ingredientes) && !empty($ingredientes)
                                && isset($alergenosName) && is_array($alergenosName) && !empty($alergenosName)
                            ) {

                                $totalIngredientes = count($ingredientes);
                                $totalAlergenos = count($alergenosName);

                                // Verificar si los dos arrays tienen la misma longitud
                                //  if ($totalIngredientes === $totalAlergenos) {
                                foreach ($ingredientes as $ing) {
                                    if ($alergenosName[$i] != "Ninguno") {
                                        echo '<span class="text_small" style="font-weight: 1000;">';
                                        echo $ing . '(' . $alergenosName[$i] . ')';
                                    } else {
                                        echo '<span class="text_small">';
                                        echo $ing;
                                    }

                                    // Si este no es el último ingrediente, añadir un guion
                                    if ($i < $totalIngredientes - 1) {
                                        echo '-';
                                        echo '</span>';
                                    } else {
                                        echo '</span>';
                                    }

                                    $i++;
                                }
                                //    } else {
                                // Manejar el caso donde los arrays no tienen la misma longitud
                                //      echo "Los arrays de ingredientes y alérgenos no tienen la misma longitud.";
                                // }
                            } else {
                                echo '<span class="text_small">';
                                echo "#####";
                                echo '</span>';
                            }
                            ?>
                        </div>

                    </div>
                    <div class="table-container">



                        <?php

                        if (!isset($filename)) {
                            $filename = "author";
                        }
                        ?>

                        <div class="qrframe " id="qr" style="border:2px solid black; ">
                            <?php echo '<img src="temp/' . (isset($tag) ? $tag->getID() : "") . '.png" style="width:100%; ">'; ?>
                        </div>






                        <div class="exp_lote">


                            <span><strong> Weight of/Peso Neto minimo:</strong>



                                <?php



                                echo isset($pesoNeto) ? $pesoNeto  : '#######';


                                echo isset($unidadIngrediente) ?





                                    $unidadIngrediente  : ' Kg' ?>

                            </span>
                            <br>

                            <span>

                                <?php

                                if (isset($caducidadElaborado)) {
                                    $caducidadElaboradoFecha = new DateTime($caducidadElaborado);
                                    $formatoCaducidad = $caducidadElaboradoFecha->format('d-m-Y');

                                    echo '<strong>Exp.Date/ F.Caducidad: </strong>' . $formatoCaducidad;
                                } else {

                                    echo '<strong>Exp.Date/ F.Caducidad: </strong> ######';
                                }
                                ?>
                            </span><br>
                            <span><strong>Batch/ Lote: </strong><?php echo isset($idElaborado) ? $idElaborado : '#######' ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <button class="btn-primary" onclick="imprimir()">
                Print<img src="./../../svg/printing.svg" alt="Print">
            </button>

        </div>
    </div>

    <script>
        function imprimir() {
            window.print();
        }
    </script>


    <!-- Bootstrap core JavaScript-->
    <script src="./../../vendor/jquery/jquery.min.js"></script>
    <script src="./../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="./../../vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="./../../js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="./../../vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="./../../vendor/datatables/dataTables.bootstrap4.min.js"></script>


</body>

</html>