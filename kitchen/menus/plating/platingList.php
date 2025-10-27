<?php

require __DIR__ . '/../models/user.php';
require_once __DIR__ . '/../models/userDao.php';

require __DIR__ . '/../models/grupos.php';
require_once __DIR__ . '/../models/gruposDao.php';

require __DIR__ . '/../models/permisos.php';
require_once __DIR__ . '/../models/permisosDao.php';


require __DIR__ . '/../models/gruposPermisos.php';
require_once __DIR__ . '/../models/gruposPermisosDao.php';

$userses = new User();
$grupo = new Grupo();
session_start();

if (isset($_SESSION['user_id'])) {

    $id = $_SESSION['user_id'];
    $userSession = UserDao::select($_SESSION['user_id']);
    $grupoSession = GruposDao::select($userSession->getGrupo_id());
} else {
    //No authenticated user
    header('Location: ./../login/login.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Custom fonts for this template-->
  <script src="https://kit.fontawesome.com/2e2cc992ca.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
   
   
    <link href="./../css/navs.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/platingList.css">
   
    <script defer type="module" src="./js/platingList.js"></script>
    <title>Cook code</title>
    <link rel="icon" type="image/png" href="./../../img/logo.png">
</head>

<body>
<?php include '../templates/navs.php'; ?>
<?php insertarTopNav('platingForm.php', './../svg/orders_Black.svg', 'Plating form'); ?>
   

    <main>
        <h2 id="mainTitle">Dishes</h2>
       
        <section id="receipts-container">
          
        <dish-list></dish-list>
         
        </section>
       
    </main>
</body>

</html>