<?php
require __DIR__ . '/../../../models/almacenElaboraciones.php';
require_once __DIR__ . '/../../../models/almacenElaboracionesDao.php';
require_once __DIR__ . '/../../../DBConnection.php';

// Conectar con PDO usando DBConnection
$conn = DBConnection::connectDB();
if (!$conn) {
    die("Error de conexión a la base de datos.");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Cook Code</title>
    <link rel="icon" type="image/png" href="./../../../img/logo.png">
    <!-- Custom fonts for this template-->
    <script src="https://kit.fontawesome.com/2e2cc992ca.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="./../../../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="./../../../css/navs.css" rel="stylesheet">
    <link href="./../../../css/tables.css" rel="stylesheet">
    <style>
        /* Estilos generales para la tabla */
        table {
            width: 100%;
            border-collapse: collapse;
            /* Para evitar espacios entre celdas */
        }

        th,
        td {
            padding: 12px 15px;
            /* Espaciado dentro de las celdas */
            text-align: left;
            /* Alinear el texto a la izquierda */
            border: 1px solid #ddd;
            /* Borde gris claro para las celdas */
        }

        /* Estilos para los encabezados de la tabla */
        th {
            background-color: #4e73df;
            /* Color de fondo del encabezado */
            color: white;
            /* Color del texto */
            font-weight: bold;
            /* Negrita en los encabezados */
        }

        /* Fila de la tabla con fondo alterno para mejorar la legibilidad */
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
            /* Color de fondo al pasar el ratón sobre una fila */
        }

        /* Estilos para la tabla cuando no hay datos */
        .text-center {
            text-align: center;
            font-style: italic;
        }

        /* Para el contenedor de la tabla (si quieres agregar un borde o sombra) */
        .table-responsive {
            overflow-x: auto;
            /* Hacer que la tabla sea desplazable horizontalmente en pantallas pequeñas */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            /* Sombra sutil alrededor de la tabla */
            border-radius: 8px;
            /* Bordes redondeados */
        }

        /* Estilo general de la card donde se encuentra la tabla */
        .card {
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            /* Sombra suave */
        }

        .card-header {
            background-color: #4e73df;
            color: white;
            font-weight: bold;
        }

        /* Ajustes adicionales para las celdas de fecha y coste */
        td {
            font-size: 14px;
        }

        td:nth-child(4),
        td:nth-child(5) {
            text-align: center;
            /* Centrar las fechas */
        }

        td:nth-child(6) {
            text-align: right;
            /* Alinear el coste a la derecha */
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
      //  'qr' => ['url' => './../../qrcode/generator/tickets.php', 'icon' => './../../../svg/qr_code.svg', 'text' => 'QR'],
        'ecommerce' => ['url' => 'http://localhost:8080/ecommerce', 'icon' => './../../../svg/tpv.svg', 'text' => 'E-commerce'],
        'restaurant' => ['url' => 'http://localhost:8080/restaurant/public/restaurants.html', 'icon' => './../../../svg/restaurant.svg', 'text' => 'Restaurant'],
        'elaborations' => ['url' => './../../elaborations', 'icon' => './../../../svg/recipes.svg', 'text' => 'Elaborations'],
        'Ing/Recipes' => ['url' => './../../ing_recetas', 'icon' => './../../../svg/recipe_white.svg', 'text' => 'Ing/Recipes'],
        'Plating' => ['url' => './../../plating', 'icon' => './../../../svg/plato_blanco.svg', 'text' => 'Dish composition'],
        'Mise en Place' => ['url' => './../../miseEnPlace', 'icon' => './../../../svg/miseEnPlace.svg', 'text' => 'Mise en Place'],
        'stock' => ['url' => './../../stock', 'icon' => './../../../svg/stock.svg', 'text' => 'Order tracking'],
        'suppliers' => ['url' => './../../suppliers', 'icon' => './../../../svg/orders.svg', 'text' => 'Suppliers'],
        'economic' => ['url' => '#', 'icon' => './../../../svg/graph.svg', 'text' => 'Economic'],




    ];

    include './../../../includes/session.php';
    include './../../../includes/navs.php';
    insertarTopNav('./../', './../../../svg/orders_Black.svg', 'Stock');
    insertarTopNav('./../addStock', './../../../svg/orders_Black.svg', 'Add ing');
    insertarTopNav('./../addStockElab', './../../../svg/orders_Black.svg', 'Add elab');
    ?>

    <div id="content-wrapper" class="d-flex flex-column">
        <div class="container-fluid">
            <h1 class="h3 mb-4 text-gray-800">Batch Stock List</h1>

            <!-- Tabla para mostrar los datos -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Stock ing</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Batch</th>
                                    <th>Ingredient</th>
                                    <th>Packaged</th>
                                    <th>Production</th>
                                    <th>Expiration</th>
                                    <th>Cost</th>
                                    <th>Quantity</th>

                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                  $sql = "SELECT sl.id, i.fName AS ingrediente, sl.lote, sl.cantidad, sl.unidades, 
                                       sl.elaboracion, sl.caducidad, sl.coste, sl.tipo_unidad, sl.cantidad_total
                                FROM stock_lotes_ing sl
                                INNER JOIN ingredients i ON sl.ingrediente_id = i.id";
                        $stmt = $conn->query($sql);
                        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                if ($rows) {
                            foreach ($rows as $row) {
                                $cantidadTotal = $row['cantidad_total'];
                                $caducidad = $row['caducidad'];
                                $estadoStock = '';

                                if ($cantidadTotal <= 0) {
                                    $estadoStock = '&#x2b55; Out of Stock';
                                } else {
                                    $fechaActual = date("Y-m-d");
                                    $fechaCaducidad = date("Y-m-d", strtotime($caducidad));
                                    $estadoStock = ($fechaCaducidad < $fechaActual) ? '&#x274c; Expired' : '&#x2714; Available';
                                }

                                echo "<tr>
                                    <td>{$row['id']}</td>
                                    <td>{$row['lote']}</td>
                                    <td>{$row['ingrediente']}</td>
                                    <td>{$row['cantidad']} {$row['tipo_unidad']} x {$row['unidades']}U</td>
                                    <td>" . date("d/m/Y", strtotime($row['elaboracion'])) . "</td>
                                    <td>" . date("d/m/Y", strtotime($row['caducidad'])) . "</td>
                                    <td>{$row['coste']}</td>
                                    <td>{$cantidadTotal}</td>
                                    <td>{$estadoStock}</td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='9' class='text-center'>No data available</td></tr>";
                        }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Stock elab</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Batch</th>
                                    <th>Processed</th>
                                    <th>Packaged</th>
                                    <th>Production</th>
                                    <th>Expiration</th>
                                    <th>Cost</th>
                                    <th>Quantity</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                             <?php
$sql = "SELECT sl.id, i.receta AS receta, sl.lote, sl.cantidad, sl.unidades, 
               sl.elaboracion, sl.caducidad, sl.coste, sl.tipo_unidad, sl.cantidad_total
        FROM stock_lotes_elab sl
        INNER JOIN recetas i ON sl.receta_id = i.id";

$stmt = $conn->query($sql);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($rows) {
    foreach ($rows as $row) {
        $cantidadTotal = $row['cantidad_total'];
        $caducidad = $row['caducidad'];

        // Verificar estado del stock
        if ($cantidadTotal <= 0) {
            $estadoStock = '&#x2b55; Out of Stock';
        } else {
            $fechaActual = date("Y-m-d");
            $fechaCaducidad = date("Y-m-d", strtotime($caducidad));
            $estadoStock = ($fechaCaducidad < $fechaActual) ? '&#x274c; Expired' : '&#x2714; Available';
        }

        // Mostrar fila
        echo "<tr>";
        echo "<td>{$row['id']}</td>";
        echo "<td>{$row['lote']}</td>";
        echo "<td>{$row['receta']}</td>";
        echo "<td>{$row['unidades']}U</td>";

        $fecha_elaboracion = date("d/m/Y", strtotime($row['elaboracion']));
        $fecha_caducidad = date("d/m/Y", strtotime($row['caducidad']));

        echo "<td>{$fecha_elaboracion}</td>";
        echo "<td>{$fecha_caducidad}</td>";
        echo "<td>{$row['coste']}</td>";
        echo "<td>{$cantidadTotal}</td>";
        echo "<td>{$estadoStock}</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='9' class='text-center'>No data available</td></tr>";
}
?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>