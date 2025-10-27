<?php

declare(strict_types=1);
require_once __DIR__ . '/tagselaboraciones_ingredientes.php';
require_once __DIR__ . '/../DBConnection.php';
require_once __DIR__ . '/../IDbAccess.php';

class Tagselaboraciones_ingredientsDao implements IDbAccess
{
    public static function getAll(): ?array
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT * FROM tagselaboraciones_ingredients");
            $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Tagselaboraciones_ingredients');
            $stmt->execute();
            return $stmt->fetchAll();
        } else {
            return null;
        }
    }

    public static function select($id): ?Tagselaboraciones_ingredients
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT * FROM tagselaboraciones_ingredients WHERE tag_elaboracion_id = :id");
            $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Tagselaboraciones_ingredients');
            $stmt->execute(['id' => $id]);
            $object = $stmt->fetch();
            if ($object) {
                return $object;
            }
        }
        return null;
    }

    public static function insert($object): int
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("INSERT INTO tagselaboraciones_ingredients (tag_elaboracion_id, ingrediente, cantidad, unidad, alergeno) VALUES (:tag_elaboracion_id, :ingrediente, :cantidad, :unidad, :alergeno)");
            $stmt->execute([
                'tag_elaboracion_id' => $object->getTag_elaboracion_id(),
                'ingrediente' => $object->getIngrediente(),
                'cantidad' => $object->getCantidad(),
                'unidad' => $object->getUnidad(),
                'alergeno' => $object->getAlergeno()
            ]);
            return $stmt->rowCount(); // Return the number of rows affected
        }
        return 0;
    }

    public static function delete($object): int
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("DELETE FROM tagselaboraciones_ingredients WHERE tag_elaboracion_id = :tag_elaboracion_id");
            $stmt->execute(['tag_elaboracion_id' => $object->getTag_elaboracion_id()]);
            return $stmt->rowCount(); // Return the number of rows affected
        }
        return 0;
    }

    public static function update($object): int
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("UPDATE tagselaboraciones_ingredients SET ingrediente = :ingrediente, cantidad = :cantidad, unidad = :unidad WHERE tag_elaboracion_id = :tag_elaboracion_id");
            $stmt->execute([
                'ingrediente' => $object->getIngrediente(),
                'cantidad' => $object->getCantidad(),
                'unidad' => $object->getUnidad(),
                'tag_elaboracion_id' => $object->getTag_elaboracion_id()
            ]);
            return $stmt->rowCount(); // Return the number of rows affected
        }
        return 0;
    }


    public static function getIngredientByTagElaboracionId(int $tagElaboracionId): ?array
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT ingrediente, cantidad, unidad, alergeno FROM tagselaboraciones_ingredients WHERE tag_elaboracion_id = :tag_elaboracion_id");
            $stmt->execute(['tag_elaboracion_id' => $tagElaboracionId]);
            $ingredientInfo = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $ingredientInfo !== false ? $ingredientInfo : null;
        }
        return null;
    }

}
