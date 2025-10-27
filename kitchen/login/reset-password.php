<?php

require __DIR__ . '/../models/RecuperacionPass.php';
require_once __DIR__ . '/../models/RecuperacionPassDao.php';

require __DIR__ . '/../models/user.php';
require_once __DIR__ . '/../models/userDao.php';



?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Reset password</title>

    <!-- Custom fonts for this template-->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        #miDiv {
            background-image: url('../img/img-login.jpg');
            background-size: cover;
            background-position: right;
        }
    </style>
</head>

<body class="bg-gradient-primary">

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div id="miDiv" class="col-lg-6 d-none d-lg-block bg-password-image"></div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-2">
                                            Reset password</h1>
                                        <p class="mb-4">Please enter your new password below. Make sure it is safe and easy to remember.</p>
                                    </div>

                                    <?php

                                    // Verificar si se ha recibido un token válido en la URL
                                    if (isset($_GET['token'])) {
                                        $token = $_GET['token'];

                                        // Verificar si el token existe en la base de datos

                                        $token_es_valido = RecuperacionPassDao::tokenExists($token);
                                        // Si el token es válido, mostrar el formulario para restablecer la contraseña
                                        if ($token_es_valido) {
                                            echo '
                                            <form method="post" action="procesar-reset-password.php">
                                                <input type="hidden" name="token" value="' . $token . '">
                                               
                                                <input type="password" class="form-control form-control-user" id="password" name="password" placeholder="New Password..."><br>
                                              
                                                <input type="password" class="form-control form-control-user" id="confirm_password" name="confirm_password" placeholder="Confirm Password..."><br>
                                                <input class="btn btn-primary btn-user btn-block submitBtn" type="submit" value="Restablecer contraseña" disabled>
                                            </form>';
                                        } else {
                                            // Si el token no es válido, mostrar un mensaje de error
                                            echo "<span style='background-color: #f8d7da; font-size: 14px; '>The token is invalid.</span>";
                                        }
                                    } else {
                                        // Si no se proporcionó ningún token en la URL, mostrar un mensaje de error
                                        echo "<span style='background-color: #f8d7da; font-size: 14px; '>The password reset link is invalid.</span>";
                                    }




                                    ?>




                                    <hr>
                                   
                                    <div class="text-center">
                                        <a class="small" href="login.php">Already have an account? Login!</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../js/sb-admin-2.min.js"></script>

    <script src="./js/reset-password.js"></script>

</body>

</html>