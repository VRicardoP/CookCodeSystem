<?php
require_once __DIR__ . '/../DBConnection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre = $_POST['nombre'] ?? '';
    $instrucciones = $_POST['instrucciones'] ?? '';

    $imagen = $_FILES['imagen']['name'] ?? '';
    $target_dir = "./../img_dishes/";

    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $target_file = $target_dir . basename($imagen);

    if (move_uploaded_file($_FILES['imagen']['tmp_name'], $target_file)) {
        $connKitchen = DBConnection::connectDB();
        if (!$connKitchen) {
            die("Error al conectar con la base de datos KitchenTag");
        }

        try {
            $connKitchen->beginTransaction();

            // Insertar plato
            $stmtPlato = $connKitchen->prepare("INSERT INTO platos (nombre, imagen, instrucciones) VALUES (?, ?, ?)");
            $stmtPlato->execute([$nombre, $target_file, $instrucciones]);
            $id_plato = $connKitchen->lastInsertId();

            // Insertar platos preelaborados
            if (!empty($_POST['platos_elaborados']['nombre']) && !empty($_POST['platos_elaborados']['cantidad'])) {
                $platosNombres = $_POST['platos_elaborados']['nombre'];
                $platosCantidades = $_POST['platos_elaborados']['cantidad'];

                $stmtPreelaborado = $connKitchen->prepare("INSERT INTO platos_preelaborados (id_plato, nombre, cantidad) VALUES (?, ?, ?)");
                foreach ($platosNombres as $index => $nombreElaborado) {
                    $cantidadElaborado = $platosCantidades[$index];
                    if (!empty($nombreElaborado) && is_numeric($cantidadElaborado)) {
                        $stmtPreelaborado->execute([$id_plato, $nombreElaborado, $cantidadElaborado]);
                    }
                }
            }

            // Insertar ingredientes
            if (!empty($_POST['ingredientes']['nombre']) && !empty($_POST['ingredientes']['cantidad']) && !empty($_POST['ingredientes']['unidad'])) {
                $ingredientesNombres = $_POST['ingredientes']['nombre'];
                $ingredientesCantidades = $_POST['ingredientes']['cantidad'];
                $ingredientesUnidades = $_POST['ingredientes']['unidad'];

                $stmtIngrediente = $connKitchen->prepare("INSERT INTO platos_ingrediente (id_plato, nombre, cantidad, unidad) VALUES (?, ?, ?, ?)");
                foreach ($ingredientesNombres as $index => $nombreIngrediente) {
                    $cantidadIngrediente = $ingredientesCantidades[$index];
                    $unidadIngrediente = $ingredientesUnidades[$index];
                    if (!empty($nombreIngrediente) && is_numeric($cantidadIngrediente) && !empty($unidadIngrediente)) {
                        $stmtIngrediente->execute([$id_plato, $nombreIngrediente, $cantidadIngrediente, $unidadIngrediente]);
                    }
                }
            }

            $connKitchen->commit();

            // Ahora conexión a Restaurant (similar, pero nueva conexión PDO)
            // Si quieres usar la misma clase DBConnection para otra BD, tendrías que modificarla o crear otra.

            // Aquí haré conexión manual para Restaurant usando PDO directamente:

            $dsnRestaurant = "mysql:host=localhost;dbname=restaurant;charset=utf8mb4";
            $usernameRestaurant = "root";
            $passwordRestaurant = "";

            $connRestaurant = new PDO($dsnRestaurant, $usernameRestaurant, $passwordRestaurant, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_PERSISTENT => true,
            ]);

            $connRestaurant->beginTransaction();

            // Ajustar ruta imagen para Restaurant (ajusta la URL a tu servidor)
            $pathImage = "http://localhost:8080/kitchen/menus/plating/" . basename($target_file);

            $stmtPlatoRest = $connRestaurant->prepare("INSERT INTO platos (id, nombre, imagen, instrucciones) VALUES (?, ?, ?, ?)");
            $stmtPlatoRest->execute([$id_plato, $nombre, $pathImage, $instrucciones]);

            if (!empty($platosNombres) && !empty($platosCantidades)) {
                $stmtPreelaboradoRest = $connRestaurant->prepare("INSERT INTO platos_preelaborados (id_plato, nombre, cantidad) VALUES (?, ?, ?)");
                foreach ($platosNombres as $index => $nombreElaborado) {
                    $cantidadElaborado = $platosCantidades[$index];
                    if (!empty($nombreElaborado) && is_numeric($cantidadElaborado)) {
                        $stmtPreelaboradoRest->execute([$id_plato, $nombreElaborado, $cantidadElaborado]);
                    }
                }
            }

            if (!empty($ingredientesNombres) && !empty($ingredientesCantidades) && !empty($ingredientesUnidades)) {
                $stmtIngredienteRest = $connRestaurant->prepare("INSERT INTO platos_ingrediente (id_plato, nombre, cantidad, unidad) VALUES (?, ?, ?, ?)");
                foreach ($ingredientesNombres as $index => $nombreIngrediente) {
                    $cantidadIngrediente = $ingredientesCantidades[$index];
                    $unidadIngrediente = $ingredientesUnidades[$index];
                    if (!empty($nombreIngrediente) && is_numeric($cantidadIngrediente) && !empty($unidadIngrediente)) {
                        $stmtIngredienteRest->execute([$id_plato, $nombreIngrediente, $cantidadIngrediente, $unidadIngrediente]);
                    }
                }
            }

            $connRestaurant->commit();

            echo "Plato guardado exitosamente en ambas bases de datos.";

        } catch (Exception $e) {
            if ($connKitchen && $connKitchen->inTransaction()) {
                $connKitchen->rollBack();
            }
            if ($connRestaurant && $connRestaurant->inTransaction()) {
                $connRestaurant->rollBack();
            }
            echo "Error al guardar el plato: " . $e->getMessage();
        }
    } else {
        echo "Error al subir la imagen.";
    }
}
?>
