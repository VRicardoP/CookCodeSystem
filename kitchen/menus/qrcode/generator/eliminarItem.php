<?php

require __DIR__ . '/../../models/tagsElaboraciones.php';
require_once __DIR__ . '/../../models/tagsElaboracionesDao.php';


require __DIR__ . '/../../models/tagsIngredientes.php';
require_once __DIR__ . '/../../models/tagsIngredientesDao.php';

$id = $_POST['id'];
$type = $_POST['type'];
echo "Id del eliminador:".$id;
if ($type === 'elab') {
    $elaboracion = TagsElaboracionesDao::select($id);
    TagsElaboracionesDao::delete($elaboracion);
} elseif ($type === 'ing') {
    $ingrediente = TagsIngredientesDao::select($id);
    TagsIngredientesDao::delete($ingrediente);
}

echo json_encode(['success' => true]);
?>