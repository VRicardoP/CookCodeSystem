<?php

require_once __DIR__ . '/../models/user.php';
require_once __DIR__ . '/../models/userDao.php';
require_once __DIR__ . '/../models/grupos.php';
require_once __DIR__ . '/../models/gruposDao.php';


// Recoger los datos enviados por AJAX
//$id = $_POST['id'];
$email = $_POST['email'];
$password = $_POST['password'];
$grupo_id = $_POST['grupo_id'];
$name = $_POST['name'];
$phone = $_POST['phone'];
$surname = $_POST['surname'];
$address = $_POST['address'];
$city = $_POST['city'];
$cp = $_POST['cp'];
$country = $_POST['country'];
$province = $_POST['province'];
$image = null;


$user = UserDao::getByEmail($email);

if($user){


  // hashear la contraseña solo si ha sido modificada
  if ($password != '****') {
      $user->setPassword(password_hash($password, PASSWORD_DEFAULT));
  }
  
   
    $user->setGrupo_id($grupo_id);
    $user->setName($name);
    $user->setPhone($phone);
    $user->setImage($image);
    $user->setSurname($surname);
    $user->setAddress($address);
    $user->setCity($city);
    $user->setCp($cp);
    $user->setCountry($country);
    $user->setProvince($province);

    UserDao::update($user);
}
else{
    $user = new User(
      $id = 0,
        $email,
        $name ,
        password_hash($password, PASSWORD_DEFAULT),
        $grupo_id ,
        $phone ,
        $image,
        $surname ,
        $address,
        $city ,
        $cp ,
        $country,
       $province
    );
UserDao::insert($user);
}

?>
