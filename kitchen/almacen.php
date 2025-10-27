<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>AdminKitchen</title>

    <!-- Custom fonts for this template-->
    <!-- <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">-->
    <script src="https://kit.fontawesome.com/2e2cc992ca.js" crossorigin="anonymous"></script>
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">AdminKitchen</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="index.html">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Utilities
            </div>
            <!-- Nav Item - Pedidos -->
            <li class="nav-item">
                <a class="nav-link" href="pedidos.html">
                    <i class="fa-solid fa-clipboard-check"></i>
                    <span>Orders</span></a>
            </li>
            <!-- Nav Item - Tickets -->
            <li class="nav-item">
                <a class="nav-link" href="tickets.html">
                    <i class="fa-solid fa-tag"></i>
                    <span>Tickets</span></a>
            </li>
            <!-- Nav Item - Elaborations -->
            <li class="nav-item">
                <a class="nav-link" href="elaborations.html">
                    <i class="fa-solid fa-cutlery"></i>
                    <span>Elaborations</span></a>
            </li>
            <!-- Nav Item - Tags-->
            <li class="nav-item">
                <a class="nav-link" href="elaborations.html">
                    <i class="fa fa-qrcode"></i>
                    <span>Tags</span></a>
            </li>
            <!-- Nav Item - MiniChef-->
            <li class="nav-item">
                <a class="nav-link" href="elaborations.html">
                    <i class="fa fa-desktop"></i>
                    <span>MiniChef</span></a>
            </li>
            <!-- Nav Item - Restaurant -->
            <li class="nav-item">
                <a class="nav-link" href="restaurant.html">
                    <i class="fa-solid fa-shop"></i>
                    <span>Restaurant</span></a>
            </li>

            <!-- Nav Item - Departments -->
            <li class="nav-item">
                <a class="nav-link" href="departments.html">
                    <i class="fa-solid fa-people-group"></i>
                    <span>Departments</span></a>
            </li>

            <!-- Nav Item - Users -->
            <li class="nav-item">
                <a class="nav-link" href="users.html">
                    <i class="fa-solid fa-user"></i>
                    <span>Users</span></a>
            </li>

            
            

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>
        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" name="boto_burguer" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Search -->
                    <form
                        class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                        <div class="input-group">
                            <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..."
                                aria-label="Search" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                                aria-labelledby="searchDropdown">
                                <form class="form-inline mr-auto w-100 navbar-search">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light border-0 small"
                                            placeholder="Search for..." aria-label="Search"
                                            aria-describedby="basic-addon2">
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
                            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-bell fa-fw"></i>
                                <!-- Counter - Alerts -->
                                <span class="badge badge-danger badge-counter">3+</span>
                            </a>
                            <!-- Dropdown - Alerts -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="alertsDropdown">
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
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">Administrator</span>
                                <img class="img-profile rounded-circle"
                                    src="img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
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
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->
<!-- ************************************************************************************************************************ -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <?php
                        $host = "localhost";
                        $username = "root";
                        $password = "";
                        $database = "kitchentag2";
                /* Attempt to connect to MySQL database */
                $link = new mysqli($host, $username, $password, $database);

                // Check connection
                if ($link->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }
               ?>

                    <!-- Page Heading -->
                    <h1 class="h3 mb-2 text-gray-800">Warehouse</h1>
                    <p class="mb-4">Stock at the moment</p>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Ticket control</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <?php
 
                                            echo "<th>ID</th>";
                                            echo "<th>Name</th>";
                                            echo "<th>Product Type</th>";
                                            echo "<th>Packaging</th>";
                                            echo "<th>Amount</th>";
                                            echo "<th>Elaboration date</th>";
                                            echo "<th>Expiration date</th>";
                                            echo "<th>Place</th>";
                                            echo "<th>Cost currency</th>";
                                            echo "<th>Cost</th>";
                                            echo "<th>Price currency</th>";
                                            echo "<th>Price</th>";
                                            echo "<th>Send</th>";
                                            ?>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                    <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Product Type</th>
                                            <th>Packaging</th>
                                            <th>Amount</th>
                                            <th>Elaboration date</th>
                                            <th>Expiration date</th>
                                            <th>Place</th>
                                            <th>Cost currency</th>
                                            <th>Cost</th>
                                            <th>Price currency</th>
                                            <th>Price</th>
                                            <th>Send</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php                                                    
                                        
                                        $query = "SELECT * FROM `almacenelaboraciones`;";
                                                    //echo $query;
                                                    $result = $link->query($query);
                                               
                                                    if ($result->num_rows > 0) {
                                                        while ($row = $result->fetch_assoc()) {   
                                                        echo '<form action=insertarPedidoElab.php method=get>';
                                                        echo '<tr style="background-color: #ff8d0045;">';
                                                        echo "<td><input type='text' name='ID' value=". $row["ID"] .'></td>';
                                                        echo "<td><input type='text' name='fName' value=". $row["fName"] .'></td>';
                                                        echo "<td><input type='text' name='tipoProd' value=". $row["tipoProd"] .'></td>';
                                                        echo "<td><input type='text' name='packaging' value=". $row["packaging"] .'></td>';
                                                        echo "<td><input type='text' name='productAmount' value=". $row["productamount"] .'></td>';
                                                        echo "<td><input type='text' name='fechaElab' value=". $row["fechaElab"] .'></td>';
                                                        echo "<td><input type='text' name='fechaCad' value=". $row["fechaCad"] .'></td>';
                                                        echo "<td><input type='text' name='warehouse' value=". $row["warehouse"] .'></td>';
                                                        echo "<td><input type='text' name='costCurrency' value=". $row["costCurrency"] .'></td>';
                                                        echo "<td><input type='text' name='costPrice' value=". $row["costPrice"] .'></td>';
                                                        echo "<td><input type='text' name='saleCurrency' value=". $row["saleCurrency"] .'></td>';
                                                        echo "<td><input type='text' name='salePrice' value=". $row["salePrice"] .'></td>';
                                                        echo "<td><input type='submit' value='Send'></td>";
                                                        echo "</tr>";
                                                        echo "</form>";
                                                        }
                                                    }
                                        ?>
                                        <?php                                                    
                                        
                                        $query = "SELECT * FROM `almaceningredientes`;";
                                                    //echo $query;
                                                    $result = $link->query($query);
                                               
                                                    if ($result->num_rows > 0) {
                                                        while ($row = $result->fetch_assoc()) {   
                                                            echo '<form action=insertarPedidoIngred.php method=get>';
                                                            echo '<tr style="background-color: #ff8d0045;">';
                                                            echo "<td><input type='text' name='ID' value=". $row["ID"] .'></td>';
                                                            echo "<td><input type='text' name='fName' value=". $row["fName"] .'></td>';
                                                            echo "<td><input type='text' name='tipoProd' value=". $row["tipoProd"] .'></td>';
                                                            echo "<td><input type='text' name='packaging' value=". $row["packaging"] .'></td>';
                                                            echo "<td><input type='text' name='productAmount' value=". $row["productamount"] .'></td>';
                                                            echo "<td><input type='text' name='fechaElab' value=". $row["fechaElab"] .'></td>';
                                                            echo "<td><input type='text' name='fechaCad' value=". $row["fechaCad"] .'></td>';
                                                            echo "<td><input type='text' name='warehouse' value=". $row["warehouse"] .'></td>';
                                                            echo "<td><input type='text' name='costCurrency' value=". $row["costCurrency"] .'></td>';
                                                            echo "<td><input type='text' name='costPrice' value=". $row["costPrice"] .'></td>';
                                                            echo "<td><input type='text' name='saleCurrency' value=". $row["saleCurrency"] .'></td>';
                                                            echo "<td><input type='text' name='salePrice' value=". $row["salePrice"] .'></td>';
                                                            echo "<td><input type='submit' value='Send'></td>";
                                                            echo "</tr>";
                                                            echo "</form>";
                                                        }
                                                    }
                                        ?>
                                                                                <?php                                                    
                                        
                                        $query = "SELECT * FROM `almacenpreelaboraciones`;";
                                                    //echo $query;
                                                    $result = $link->query($query);
                                               
                                                    if ($result->num_rows > 0) {
                                                        while ($row = $result->fetch_assoc()) {   
                                                            echo '<form action=insertarPedidoPreElab.php method=get>';
                                                            echo '<tr style="background-color: #ff8d0045;">';
                                                            echo "<td><input type='text' name='ID' value=". $row["ID"] .'></td>';
                                                            echo "<td><input type='text' name='fName' value=". $row["fName"] .'></td>';
                                                            echo "<td><input type='text' name='tipoProd' value=". $row["tipoProd"] .'></td>';
                                                            echo "<td><input type='text' name='packaging' value=". $row["packaging"] .'></td>';
                                                            echo "<td><input type='text' name='productAmount' value=". $row["productamount"] .'></td>';
                                                            echo "<td><input type='text' name='fechaElab' value=". $row["fechaElab"] .'></td>';
                                                            echo "<td><input type='text' name='fechaCad' value=". $row["fechaCad"] .'></td>';
                                                            echo "<td><input type='text' name='warehouse' value=". $row["warehouse"] .'></td>';
                                                            echo "<td><input type='text' name='costCurrency' value=". $row["costCurrency"] .'></td>';
                                                            echo "<td><input type='text' name='costPrice' value=". $row["costPrice"] .'></td>';
                                                            echo "<td><input type='text' name='saleCurrency' value=". $row["saleCurrency"] .'></td>';
                                                            echo "<td><input type='text' name='salePrice' value=". $row["salePrice"] .'></td>';
                                                            echo "<td><input type='submit' value='Send'></td>";
                                                            echo "</tr>";
                                                            echo "</form>";
                                                        }
                                                    }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2020</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/datatables-demo.js"></script>

</body>

</html>
