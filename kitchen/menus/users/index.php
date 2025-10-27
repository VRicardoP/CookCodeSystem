<?php
include './../../includes/session.php';
require_once __DIR__ . '/../../DBConnection.php';

$link = "<a href='login.php'>Login</a>";

$err = false;
$usernameErr = "";

$user = new User();

$grupos = GruposDao::getAll();

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET["edit_usr"])) {
        $id = $_GET["edit_usr"];
        $redirect_url = "register.php?id=" . urlencode($id);
        header("Location: $redirect_url");
        exit();
    } elseif (isset($_GET["del_usr"])) {
        $id = $_GET["del_usr"];
        $listUsers = UserDao::getAll();
        foreach ($listUsers as $user) {
            if ($id == $user->getId()) {
                UserDao::delete($user);
                header("Location: userList.php");
                exit();
            }
        }
    }
}
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
    <link href=" https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css" rel="stylesheet" type="text/css">

    <link href="./../../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="./../../css/tables.css" rel="stylesheet">
    <link href="./../../css/navs.css" rel="stylesheet">
</head>

<body id="page-top">

    <?php

    include './../../includes/navs.php';


    foreach ($nombresPermiso as $nombrePermiso) {
        if ($nombrePermiso == "root") {
            insertarTopNav('../users/create', './../../svg/add_user.svg', 'Create User');
            insertarTopNav('../users/create_group', './../../svg/group.svg', 'Create group');
        }
    }
    ?>
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">

            <div class="container-fluid">
                <div class="card shadow mb-4">
                    <div class="card-header">
                        <h6 class="m-0 font-weight-bold text-primary">Users</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <form name="userList" action="" enctype="multipart/form-data" method="get">
                                <table class="display" id="dataUser">
                                    <thead>
                                        <tr>
                                            <?php

                                            echo "<th>ID</th>";
                                            echo "<th>Name</th>";
                                            echo "<th>Surname</th>";
                                            echo "<th>Email</th>";
                                            echo "<th>Phone</th>";
                                            echo "<th>City</th>";
                                            echo "<th>Country</th>";
                                            echo "<th>Group</th>";

                                            foreach ($nombresPermiso as $nombrePermiso) {
                                                if ($nombrePermiso == "root") {


                                                    echo "<th>Actions</th>";
                                                }
                                            }

                                            ?>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php
                                        try {
                                            // Usar la clase DBConnection para obtener la conexión PDO
                                            $conn = DBConnection::connectDB();

                                            if ($conn) {
                                                $queryPieData7Days = "SELECT * FROM `users`";
                                                $stmt = $conn->query($queryPieData7Days);

                                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                    echo '<tr id="row-' . $row["id"] . '">';
                                                    echo '<td>' . $row["id"] . '</td>';
                                                    echo '<td>' . $row["name"] . '</td>';
                                                    echo '<td>' . $row["surname"] . '</td>';
                                                    echo '<td>' . $row["email"] . '</td>';
                                                    echo '<td>' . $row["phone"] . '</td>';
                                                    echo '<td>' . $row["city"] . '</td>';
                                                    echo '<td>' . $row["country"] . '</td>';
                                                    $grup = GruposDao::select($row["grupo_id"]);

                                                    echo '<td>' . $grup->getNombre() . '</td>';
                                                    foreach ($nombresPermiso as $nombrePermiso) {
                                                        if ($nombrePermiso == "root") {
                                                            echo "<td class='action_button'>
                            <button type='submit' name='edit_usr' id='edit_usr' class='btn-primary rounded' value='" . $row["id"] . "'>
                                <img src='./../../svg/edit.svg' alt='Edit' title='Edit'>
                            </button>
                            <button type='button' name='del_usr' id='del_usr' class='btn-danger rounded' onclick='confirmarDeleteUser(" . $row["id"] . ")'>
                                <img src='./../../svg/delete_button.svg' alt='Delete' title='Delete'>
                            </button>
                        </td>";
                                                        }
                                                    }
                                                    echo '</tr>';
                                                }
                                            }
                                        } catch (PDOException $e) {
                                            echo "Error: " . $e->getMessage();
                                        }
                                        ?>
                                    </tbody>
                                </table>

                            </form>
                        </div>
                    </div>

                </div>



                <div class="card shadow  mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Groups</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <form name="groupList" action="" enctype="multipart/form-data" method="get">
                                <table class="display" id="dataGroup">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Perms</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php
                                        try {
                                            // Usar la clase DBConnection para obtener la conexión PDO
                                            $conn = DBConnection::connectDB();

                                            if ($conn) {
                                                // Consulta principal para obtener los grupos
                                                $queryPieData7Days = "SELECT * FROM `grupos`";
                                                $stmt = $conn->query($queryPieData7Days);

                                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                    echo '<tr id="row-' . $row["id"] . '">';
                                                    echo '<td>' . $row["id"] . '</td>';
                                                    echo '<td>' . $row["nombre"] . '</td>';

                                                    // Consulta para obtener los permisos del grupo
                                                    $namePermisosGroup = "";
                                                    $queryPermisosGroup = "SELECT permiso_id FROM `grupos_permisos` WHERE grupo_id = :grupo_id";
                                                    $stmtPermisosGroup = $conn->prepare($queryPermisosGroup);
                                                    $stmtPermisosGroup->bindParam(':grupo_id', $row["id"], PDO::PARAM_INT);
                                                    $stmtPermisosGroup->execute();

                                                    while ($rowPermisosGroup = $stmtPermisosGroup->fetch(PDO::FETCH_ASSOC)) {
                                                        $queryPermisos = "SELECT nombre FROM `permisos` WHERE id = :permiso_id";
                                                        $stmtPermisos = $conn->prepare($queryPermisos);
                                                        $stmtPermisos->bindParam(':permiso_id', $rowPermisosGroup["permiso_id"], PDO::PARAM_INT);
                                                        $stmtPermisos->execute();

                                                        $rowPermisos = $stmtPermisos->fetch(PDO::FETCH_ASSOC);
                                                        if ($rowPermisos) {
                                                            $namePermisosGroup .= $rowPermisos["nombre"] . ", ";
                                                        }
                                                    }

                                                    // Eliminar la coma y el espacio final
                                                    $namePermisosGroup = rtrim($namePermisosGroup, ', ');
                                                    echo '<td>' . $namePermisosGroup . '</td>';

                                                    echo "<td class='action_button'>
                    <button type='submit' name='edit_group' id='edit_group' class='btn-primary rounded' value='" . $row["id"] . "'>
                        <img src='./../../svg/edit.svg' alt='Edit' title='Edit'>
                    </button>
                    <button type='submit' name='del_group' id='del_group' class='btn-danger rounded' onclick='confirmarDeleteGroup(" . $row["id"] . ")'>
                        <img src='./../../svg/delete_button.svg' alt='Delete' title='Delete'>
                    </button>
                </td>";

                                                    echo '</tr>';
                                                }
                                            }
                                        } catch (PDOException $e) {
                                            echo "Error: " . $e->getMessage();
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </form>
                        </div>
                    </div>


                </div>








            </div>

        </div>

    </div>

    </div>

    <script>
        function deleteUser(id) {
            console.log("id: " + id);

            let dataToSend = {
                id: id,
            };

            $.ajax({
                url: './../../controllers/eliminarUser.php',
                type: 'POST',
                data: dataToSend,
                success: function(response) {
                    console.log(response);
                    // Elimina la fila de la tabla correspondiente
                    $('#row-' + id).remove();
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }

        function confirmarDeleteUser(id) {
            if (window.confirm("¿Estás seguro de que quieres eliminarlo?")) {
                deleteUser(id);
            }
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

    <!-- Page level custom scripts -->
    <script src="./../../js/demo/datatables-demo.js"></script>

</body>

</html>