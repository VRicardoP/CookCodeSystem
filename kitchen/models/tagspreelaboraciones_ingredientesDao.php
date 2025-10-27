<?php

declare(strict_types=1);
require_once __DIR__ . '/tagsPreelaboraciones_ingredientes.php';
require_once __DIR__ . '/../DBConnection.php';
require_once __DIR__ . '/../IDbAccess.php';

class TagsPreelaboraciones_ingredientsDao implements IDbAccess
{
    public static function getAll(): ?array
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT * FROM tagspreelaboraciones_ingredients");
            $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'TagsPreelaboraciones_ingredients');
            $stmt->execute();
            return $stmt->fetchAll();
        } else {
            return null;
        }
    }

    public static function select($id): ?TagsPreelaboraciones_ingredients
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT * FROM tagspreelaboraciones_ingredients WHERE tag_preelaboracion_id = :id");
            $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'TagsPreelaboraciones_ingredients');
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
            $stmt = $conn->prepare("INSERT INTO tagspreelaboraciones_ingredients (tag_preelaboracion_id, ingrediente, cantidad, unidad) VALUES (:tag_preelaboracion_id, :ingrediente, :cantidad, :unidad)");
            $stmt->execute([
                'tag_preelaboracion_id' => $object->getTag_preelaboracion_id(),
                'ingrediente' => $object->getIngrediente(),
                'cantidad' => $object->getCantidad(),
                'unidad' => $object->getUnidad()
            ]);
            return $stmt->rowCount(); // Return the number of rows affected
        }
        return 0;
    }

    public static function delete($object): int
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("DELETE FROM tagspreelaboraciones_ingredients WHERE tag_preelaboracion_id = :tag_preelaboracion_id");
            $stmt->execute(['tag_preelaboracion_id' => $object->getTag_preelaboracion_id()]);
            return $stmt->rowCount(); // Return the number of rows affected
        }
        return 0;
    }

    public static function update($object): int
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("UPDATE tagspreelaboraciones_ingredients SET ingrediente = :ingrediente, cantidad = :cantidad, unidad = :unidad WHERE tag_preelaboracion_id = :tag_preelaboracion_id");
            $stmt->execute([
                'ingrediente' => $object->getIngrediente(),
                'cantidad' => $object->getCantidad(),
                'unidad' => $object->getUnidad(),
                'tag_preelaboracion_id' => $object->getTag_preelaboracion_id()
            ]);
            return $stmt->rowCount(); // Return the number of rows affected
        }
        return 0;
    }


    public static function getIngredientIdsByTagPreelaboracionId(int $tagPreelaboracionId): ?array
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT ingrediente FROM tagspreelaboraciones_ingredients WHERE tag_preelaboracion_id = :tag_preelaboracion_id");
            $stmt->execute(['tag_preelaboracion_id' => $tagPreelaboracionId]);
            $ingredient = $stmt->fetchAll(PDO::FETCH_COLUMN);
            return $ingredient !== false ? $ingredient : null;
        }
        return null;
    }
}

?>
