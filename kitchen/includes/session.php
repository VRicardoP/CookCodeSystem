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

if (isset($_SESSION['user_id'])) {
    $id = $_SESSION['user_id'];
    $userSession = UserDao::select($_SESSION['user_id']);
    $grupoSession = GruposDao::select($userSession->getGrupo_id());
} else {
    //No authenticated user
    if($redirectionNoUser){

        header('Location:'. $redirectionNoUser );

    }else{

        header('Location:./../../login/login.php');
    }
    
}
?>