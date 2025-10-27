<?php



require __DIR__ . '/../models/RecuperacionPass.php';
require_once __DIR__ . '/../models/RecuperacionPassDao.php';

require __DIR__ . '/../models/user.php';
require_once __DIR__ . '/../models/userDao.php';


// Verificar si se han enviado los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar si se han recibido los campos necesarios
    if (isset($_POST['token'], $_POST['password'], $_POST['confirm_password'])) {
        $token = $_POST['token'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        // Validar la contraseña y la confirmación de la contraseña
        if ($password !== $confirm_password) {
            // Las contraseñas no coinciden, mostrar un mensaje de error
            echo "Las contraseñas no coinciden.";
            exit; // Detener la ejecución del script
        }

        // Verificar si el token es válido en la base de datos
        $token_es_valido = RecuperacionPassDao::tokenExists($token);

        // Si el token es válido, actualizar la contraseña del usuario
        if ($token_es_valido) {
           $emailUser = RecuperacionPassDao::getEmailByToken($token);

           $user = UserDao::getByEmail($emailUser);


           $user->setPassword($password);
           $user->setPassword(password_hash($user->getPassword(), PASSWORD_DEFAULT));

           UserDao::update($user);

            // Redirigir al usuario a la página de inicio de sesión después de restablecer la contraseña
            header("Location: login.php");
            exit; // Detener la ejecución del script
        } else {
            // Si el token no es válido, mostrar un mensaje de error
            echo "El token no es válido.";
        }
    } else {
        // Si no se recibieron todos los campos necesarios, mostrar un mensaje de error
        echo "Faltan datos del formulario.";
    }
} else {
    // Si se accede a este script directamente sin enviar los datos del formulario, redirigir al usuario a la página de inicio de sesión
    header("Location: login.php");
    exit; // Detener la ejecución del script
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
</head>

<body>

    <h2>Reset Password</h2>

    <!-- Formulario para resetear la contraseña -->
    <form action="procesar_reset_password.php" method="post">
        <div>
            <label for="token">Token:</label>
            <input type="text" id="token" name="token" required>
        </div>
        <div>
            <label for="password">New Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div>
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
        </div>
        <div>
            <button type="submit">Reset Password</button>
        </div>
    </form>

</body>

</html>
