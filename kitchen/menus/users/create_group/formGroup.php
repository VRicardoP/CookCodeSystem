<?php


require __DIR__ . '/../models/gruposPermisos.php';
require_once __DIR__ . '/../models/gruposPermisosDao.php';

require __DIR__ . '/../models/user.php';
require_once __DIR__ . '/../models/userDao.php';

require __DIR__ . '/../models/grupos.php';
require_once __DIR__ . '/../models/gruposDao.php';

require __DIR__ . '/../models/permisos.php';
require_once __DIR__ . '/../models/permisosDao.php';


$userses = new User();
$grupo = new Grupo();
session_start();
$permisoRoot = false;
$currentGroupId = 0;




if (isset($_SESSION['user_id'])) {

    $id = $_SESSION['user_id'];
    $userSession = UserDao::select($_SESSION['user_id']);
    $grupoSession = GruposDao::select($userSession->getGrupo_id());
    $currentGroupId = $userSession->getGrupo_id();
} else {
    //No authenticated user
    header('Location: ./../login/login.php');
}

$idPermisos = array();

$idPermisos = GrupoPermisoDao::getPermisosByGroupId($currentGroupId);

$nombresPermiso = array();

foreach ($idPermisos as $idPermiso) {

	$nombresPermiso[] = PermisoDao::getPermisoNombreById($idPermiso);
}


$redirect_url =  "/kitchen/dashboard";
foreach ($nombresPermiso as $nombrePermiso) {
    if ($nombrePermiso == "root") {
        $permisoRoot = true;
    }
}

if (!$permisoRoot) {
    header("Location: $redirect_url");
}


$link = "<a href='login.php'>Login</a>";

$err = false;
$usernameErr = "";

$title = "Create group";
$btnText = "Register";

$group = new Grupo(
    $id = 0,
    $name = ""
);

$grupoPermisos = new GrupoPermiso(
    $id = 0,
    $grupo_id = 0,
    $permiso_id = 0
);

$listPermGroup = [];

$permisos = PermisoDao::getAll();

$grupos = GruposDao::getAll();


if (isset($_GET['id'])) {
    // Obtener el ID de la URL
    $id = $_GET['id'];

    $listPermGroup = GrupoPermisoDao::getByGroupId($id);

    $group =  GruposDao::select($id);

    $title = "Edite group";
    $btnText = "Editer";
}

?>


<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Register group</title>

    <!-- Custom fonts for this template-->
    <!-- <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">-->
    <script src="https://kit.fontawesome.com/2e2cc992ca.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="./../css/navs.css" rel="stylesheet">
    <style>
        span {
            color: black;
        }

        table {
            margin: 0 auto;
            border-collapse: collapse;
        }
    </style>
</head>

<body id="page-top">
    <?php include './../templates/navs.php'; ?>

    <div id="content-wrapper" class="d-flex flex-column ">

        <div class="container-fluid text-center align-center ">

            <h3><strong><?= $title ?></strong></h3>
            <form method="post" id="registro" action="formGroup.php" enctype="multipart/form-data">
                <div class="shadow-lg border rounded row justify-content-center m-5 p-2">

                    <div class="form-group text-center  col-md-12">
                        <!-- Campo oculto para enviar el ID mediante GET -->
                        <input type="hidden" name="grupo_id" id="grupo_id" value="<?php echo htmlspecialchars($group->getId()); ?>">
                        <div class="form-group mb-5">

                            <label for="name" class="mt-4"> Group name </label>
                            <input type="text" id="name" class="username_input " name="name" value="<?= $group->getNombre() ?>" /> </br>
                        </div>
                        <div class="form-group align-center">

                            <table>
                                <tr>
                                    <th colspan="6"></th>
                                    <label>Access level</label>
                                </tr>
                                <?php
                                $totalPermisos = count($permisos);
                                $mitad = ceil($totalPermisos / 2); // Dividir los permisos en dos filas
                                ?>

                                <tr>
                                    <?php
                                    // Generar los checkboxes para la primera mitad de los permisos
                                    for ($i = 0; $i < $mitad; $i++) {
                                        $permiso = $permisos[$i];
                                        $isChecked = false;
                                        foreach ($listPermGroup as $permGroup) {
                                            if ($permiso->getId() == $permGroup->getPermisoId()) {
                                                $isChecked = true;
                                                break;
                                            }
                                        }
                                        echo '<td >';
                                        echo '<span> ' . $permiso->getNombre() . '  </span>';
                                        echo '</td>';
                                        echo '<td data-id="' . $permiso->getId() . '" class="px-3 ">';
                                        echo '<input class="check-permiso" type="checkbox" name="permisos[]" value="' . $permiso->getId() . '"' . ($isChecked ? ' checked' : '') . '>';
                                        echo '</td>';
                                    }
                                    ?>
                                </tr>
                                <tr>
                                    <?php
                                    // Generar los checkboxes para la segunda mitad de los permisos
                                    for ($i = $mitad; $i < $totalPermisos; $i++) {
                                        $permiso = $permisos[$i];
                                        $isChecked = false;
                                        foreach ($listPermGroup as $permGroup) {
                                            if ($permiso->getId() == $permGroup->getPermisoId()) {
                                                $isChecked = true;
                                                break;
                                            }
                                        }
                                        echo '<td >';
                                        echo '<span> ' . $permiso->getNombre() . '  </span>';
                                        echo '</td>';
                                        echo '<td data-id="' . $permiso->getId() . '" class="px-3">';
                                        echo '<input class="check-permiso" type="checkbox" name="permisos[]" value="' . $permiso->getId() . '"' . ($isChecked ? ' checked' : '') . '>';
                                        echo '</td>';
                                    }
                                    ?>
                                </tr>
                            </table>

                        </div>

                    </div>

                    <div class="col-md-12">
                        <hr style=" border: 1px solid #ccc;">
                        <input type="submit" class="btn btn-primary submitBtn" style="width:20em; margin:0;" name="submit" id="register_button" value=<?= $btnText ?> disabled>
                    </div>

                </div>
            </form>
        </div>

    </div>

    <script src="formGroup.js"></script>
    <!-- Bootstrap core JavaScript-->
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../js/sb-admin-2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

   
</body>

</html>