<?php
require __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/UserDao.php';

require __DIR__ . '/../models/RecuperacionPass.php';
require_once __DIR__ . '/../models/RecuperacionPassDao.php';

// Incluir la clase PHPMailer
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


$msgEnvioEmail = "";

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recopilar la dirección de correo electrónico del formulario
    $email = $_POST['email'];
    $emailValid = false;
    // Verificar si la dirección de correo electrónico existe en la base de datos

    $userList = UserDao::getAll();

    foreach ($userList as $user) {
        if ($user->getEmail() == $email) {
            $emailValid = true;
        }
    }

    if ($emailValid) {
        // Generar un token de restablecimiento de contraseña
        $token = bin2hex(random_bytes(16));

        $recPass = new RecuperacionPass();
        $recPass->setEmail($email);
        $recPass->setToken($token);
        // Guardar el token en la base de datos junto con la dirección de correo electrónico del usuario
        RecuperacionPassDao::insert($recPass);

        // Enviar el correo electrónico con PHPMailer
        $mail = new PHPMailer(true);
        try {
            // Configurar el servidor SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'cooksystem959@gmail.com'; //  correo electrónico de Gmail
            $mail->Password = 'rtio ahnx rivu oqsb'; //  contraseña de Gmail
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Configurar remitente y destinatario
            $mail->setFrom('cooksystem959@gmail.com');
            $mail->addAddress($email);

            // Contenido del correo electrónico
            $mail->isHTML(true);
            $mail->Subject = 'Password reset';
            $mail->Body = 'Click the link below to reset your password: <a href="http://localhost/kitchen/login/reset-password.php?token=' . $token . '">Restore password</a>';

            // Enviar el correo electrónico
            $mail->send();

            // Muestra un mensaje al usuario indicando que se ha enviado el correo electrónico
            $msgEnvioEmail = "<span style='background-color: #d4edda; font-size: 14px; '>Your password recovery email has been sent. Please check your inbox, and if you don't see it, kindly check your spam folder as well.</span> <br>";

        } catch (Exception $e) {
            $msgEnvioEmail = "<span style='background-color: #f8d7da; font-size: 14px; '>Failed to send email: {$mail->ErrorInfo}</span> <br>";
            var_dump($mail);
        }
    } else {
        $msgEnvioEmail = "<span style='background-color: #f8d7da; font-size: 14px; '>Email does not exist</span> <br>";
    }
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

    <title>Forgot Password</title>

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
                                        <h1 class="h4 text-gray-900 mb-2">Forgot Your Password?</h1>
                                        <p class="mb-4">We get it, stuff happens. Just enter your email address below
                                            and we'll send you a link to reset your password!</p>
                                    </div>
                                    <form action="forgot-password.php" method="post">
                                        <div class="form-group">
                                            <input type="email" class="form-control form-control-user" id="exampleInputEmail" aria-describedby="emailHelp" placeholder="Enter Email Address..." name="email">
                                        </div>
                                       
                                        <input  class="btn btn-primary btn-user btn-block" type="submit" value="Reset Password">

                                    </form>
                                    <?php 
                                        echo $msgEnvioEmail;
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

</body>

</html>
