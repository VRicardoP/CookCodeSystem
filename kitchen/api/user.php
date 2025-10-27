<?php

// require __DIR__ . '/../models/user.php';
// require_once __DIR__ . '/../models/userDao.php';

// // Función para devolver una respuesta JSON
// function sendJsonResponse($data) {
//     header('Content-Type: application/json');
//     echo json_encode($data);
//     exit;
// }

// // Endpoint para obtener datos de usuario por ID
// if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
//     $id = $_GET['id'];

//     $user = UserDao::select($id);
//     if ($user) {
//         // Convertir el objeto $user a un array asociativo
//         $userData = [
//             'id' => $user->getId(),
//             'name' => $user->getName(),
//             'email' => $user->getEmail(),
//             'password' => $user->getPassword(),
//           //  'grupo_id' => $user->getGrupo_id(),
//             'phone' => $user->getPhone(),
//          //   'image' => $user->getImage(),
//             'surname' => $user->getSurname(),
//             'address' => $user->getAddress(),
//             'city' => $user->getCity(),
//             'cp' => $user->getCp(),
//             'country' => $user->getCountry(),
//             'province' => $user->getProvince()
          
//             // Añade aquí las demás propiedades del usuario
//         ];

//         sendJsonResponse($userData);
//     } else {
//         sendJsonResponse(['error' => 'User not found']);
//     }
// }

// Endpoint para obtener todos los usuarios
// if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['all'])) {
//     $users = UserDao::getAll();

//     // Convertir los objetos User a un array de arrays asociativos
//     $userDataArray = [];
//     foreach ($users as $user) {
//         $userDataArray[] = [
//             'id' => $user->getId(),
//             'name' => $user->getName(),
//             'email' => $user->getEmail(),
//             'password' => $user->getPassword(),
//           //  'grupo_id' => $user->getGrupo_id(),
//             'phone' => $user->getPhone(),
//          //   'image' => $user->getImage(),
//             'surname' => $user->getSurname(),
//             'address' => $user->getAddress(),
//             'city' => $user->getCity(),
//             'cp' => $user->getCp(),
//             'country' => $user->getCountry(),
//             'province' => $user->getProvince()
//             // Añade aquí las demás propiedades del usuario
//         ];
//     }

//     sendJsonResponse($userDataArray);
// }

?>