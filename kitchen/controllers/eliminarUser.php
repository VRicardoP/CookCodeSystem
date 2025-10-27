<?php

require __DIR__ . '/../models/user.php';
require_once __DIR__ . '/../models/userDao.php';

$id = $_POST['id'];

$user = userDao::select($id);
userDao::delete($user);

echo json_encode(['success' => true]);
