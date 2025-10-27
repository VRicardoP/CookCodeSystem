<?php define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'bellvera_jI4QZ2Vi');
define('DB_PASSWORD', 'XzEbkwTBQ85JgOkh');
define('DB_NAME', 'bellvera_kitchentag2');
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
$query="SELECT * FROM `almacenelaboraciones`;";
$result = $link->query($query);
$query2 ="SELECT COUNT(*) as cuenta, fName FROM `tagspreelaboraciones` GROUP BY fName;";
$query3 ="SELECT COUNT(*) as cuenta, fName FROM `tagselaboraciones` GROUP BY fName;";
$query4 ="SELECT COUNT(*) as cuenta, fName FROM `tagsingredientes` GROUP BY fName;";
$result3 = $link->query($query3);
$result4 = $link->query($query4);;
$result2 = $link->query($query2);
$contador=0;
while ($row = $result2->fetch_assoc()) {
    $contador= $contador+$row['cuenta'];
}
$result2 = $link->query($query2);
?>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Cook code</title>

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
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
                <div class="sidebar-brand-icon rotate-n-15">
                 
                </div>
                <div class="sidebar-brand-text mx-3"><img src="./img/cookcode.png" height="80" width="225"></div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="index.php">
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
                <a class="nav-link" href="http://localhost/wordpress/wp-admin/admin.php?page=wc-admin&path=%2Fanalytics%2Forders">
                    <i class="fa-solid fa-clipboard-check"></i>
                    <span>Orders</span></a>
            </li>
            <!-- Nav Item - Tickets -->
            <li class="nav-item">
                <a class="nav-link" href="./tickets.php">
                    <i class="fa-solid fa-tag"></i>
                    <span>Tickets</span></a>
            </li>
            <!-- Nav Item - Elaborations -->
                <li class="nav-item">
                <a class="nav-link" href="./ingredientes.php">
                    <i class="fa-solid fa-cutlery"></i>
                <span>Ingredients</span></a>
                </li>
            <!-- Nav Item - Elaborations -->
            <li class="nav-item">
                <a class="nav-link" href="./elab.php">
                    <i class="fa-solid fa-cutlery"></i>
                    <span>Elaborations</span></a>
            </li>
            <!-- Nav Item - PreElaborations -->
            <li class="nav-item">
                <a class="nav-link" href="./preelab.php">
                    <i class="fa-solid fa-cutlery"></i>
                    <span>Preelaborations</span></a>
            </li>
            <!-- Nav Item - Recipes -->
            <li class="nav-item">
                    <a class="nav-link" href="recipes.html">
                     <i class="fa-solid fa-pen-to-square"></i>
                        <span>Recipes</span></a>
                    </li>
            <!-- Nav Item - Tags- CUIDADO CON LOS HARDLINKS-->
            <li class="nav-item">
                <a class="nav-link" href="./qrcode/generator/index.php">
                    <i class="fa fa-qrcode"></i>
                    <span>Tags</span></a>
            </li>
            <!-- Nav Item - MiniChef-->
            <li class="nav-item">
                <a class="nav-link" href="../minichef.html">
                    <i class="fa fa-desktop"></i>
                    <span>MiniChef</span></a>
            </li>
            <!-- Nav Item - Restaurant -->
            <li class="nav-item">
                <a class="nav-link" href="http://localhost/wordpress/tienda/">
                    <i class="fa-solid fa-shop"></i>
                    <span>Shop</span></a>
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
                <!--<form
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
                    </form>-->

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

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
                    </div>

                    <!-- Content Row -->
                    <div class="row">

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Tags (daily)</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $result->num_rows;?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-tag fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Orders (Daily)</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">50</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-clipboard-question fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Earnings (Monthly) Card Example -->
  

                        <!-- Pending Requests Card Example -->
                
                    </div>

                    <!-- Content Row -->
                    <div class="row">

                        <!-- Content Column -->
                        <div class="col-lg-6 mb-4">

                            <!-- Project Card Example -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Preelaborations</h6>
                                </div>
                                <?php if ($result2->num_rows > 0) {
                                    while ($row = $result2->fetch_assoc()) {
                                echo '<div class="card-body">';
                                echo'<h4 class="small font-weight-bold">'.$row['fName'].'<span
                                            class="float-right">';
                                            echo'</span></h4>';
                                echo '<div class="progress mb-4">';
                                $proporcion=100*$row['cuenta']/$contador;
                                echo ' <div class="progress-bar bg-danger" role="progressbar" style="width:'.$proporcion.'%"
                                            aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>';
                                echo '</div>';

                                echo '</div>';
                                 }
                                }?>
                            </div>

                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Elaborations</h6>
                                </div>
                                <?php if ($result3->num_rows > 0) {
                                    while ($row = $result3->fetch_assoc()) {
                                echo '<div class="card-body">';
                                echo'<h4 class="small font-weight-bold">'.$row['fName'].'<span
                                            class="float-right">';
                                            echo'</span></h4>';
                                echo '<div class="progress mb-4">';
                                $proporcion=100*$row['cuenta']/$contador;
                                echo ' <div class="progress-bar bg-danger" role="progressbar" style="width:'.$proporcion.'%"
                                            aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>';
                                echo '</div>';

                                echo '</div>';
                                 }
                                }?>
                            </div>
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Ingredients</h6>
                                </div>
                                <?php if ($result4->num_rows > 0) {
                                    while ($row = $result4->fetch_assoc()) {
                                echo '<div class="card-body">';
                                echo'<h4 class="small font-weight-bold">'.$row['fName'].'<span
                                            class="float-right">';
                                            echo'</span></h4>';
                                echo '<div class="progress mb-4">';
                                $proporcion=100*$row['cuenta']/$contador;
                                echo ' <div class="progress-bar bg-danger" role="progressbar" style="width:'.$proporcion.'%"
                                            aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>';
                                echo '</div>';

                                echo '</div>';
                                 }
                                }?>
                            </div>
                           

                      
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Cook Code</span>
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
    <script src="vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/chart-area-demo.js"></script>
    <script src="js/demo/chart-pie-demo.js"></script>
    <script src="js/demo/chart-bar-demo.js"></script>

</body>

</html>
