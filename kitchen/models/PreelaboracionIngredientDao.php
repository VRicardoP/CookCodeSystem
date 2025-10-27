<?php

declare(strict_types=1);

require_once __DIR__ . '/PreelaboracionIngredient.php';
require_once __DIR__ . '/../DBConnection.php';
require_once __DIR__ . '/../IDbAccess.php';

class PreelaboracionIngredientDao implements IDbAccess
{
    public static function getAll(): ?array
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT * FROM preelaboraciones_ingredients");
            $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'PreelaboracionIngredient');
            $stmt->execute();
            return $stmt->fetchAll();
        } else {
            return null;
        }
    }

    public static function select($id): ?PreelaboracionIngredient
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT * FROM preelaboraciones_ingredients WHERE id = :id");
            $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'PreelaboracionIngredient');
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
            $stmt = $conn->prepare("INSERT INTO preelaboraciones_ingredients (preelaboracion_id, ingrediente, cantidad, unidad, alergeno) VALUES (:preelaboracion_id, :ingrediente, :cantidad, :unidad, :alergeno)");
            $stmt->execute([
                'preelaboracion_id' => $object->getPreelaboracionId(),
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
            $stmt = $conn->prepare("DELETE FROM preelaboraciones_ingredients WHERE id = :id");
            $stmt->execute(['id' => $object->getId()]);
            return $stmt->rowCount(); // Return the number of rows affected
        }
        return 0;
    }

    public static function update($object): int
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("UPDATE preelaboraciones_ingredients SET preelaboracion_id = :preelaboracion_id, ingrediente = :ingrediente, cantidad = :cantidad, unidad = :unidad, alergeno = :alergeno WHERE id = :id");
            $stmt->execute([
                'id' => $object->getId(),
                'preelaboracion_id' => $object->getPreelaboracionId(),
                'ingrediente' => $object->getIngrediente(),
                'cantidad' => $object->getCantidad(),
                'unidad' => $object->getUnidad(),
                'alergeno' => $object->getAlergeno()
            ]);
            return $stmt->rowCount(); // Return the number of rows affected
        }
        return 0;
    }

    public static function getIngredientsByPreelaboracionId(int $preelaboracionId): ?array
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT * FROM preelaboraciones_ingredients WHERE preelaboracion_id = :preelaboracion_id");
            $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'PreelaboracionIngredient');
            $stmt->execute(['preelaboracion_id' => $preelaboracionId]);
            $ingredients = $stmt->fetchAll();
            return $ingredients !== false ? $ingredients : null;
        }
        return null;
    }
}
