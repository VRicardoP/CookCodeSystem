<?php
require __DIR__ . '/../models/userDao.php';

if (isset($_POST['email'])) {
    $id = $_POST['id'];
    $email = $_POST['email'];
    $user = UserDao::getByEmail($email);

    if ($user) {
        echo json_encode(['exists' => true]);

        if($user->getId() == $id){
            echo json_encode(['exists' => false]);
        }

    } 
    else {
        echo json_encode(['exists' => false]);
    }
}
?>
