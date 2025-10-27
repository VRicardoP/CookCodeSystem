<?php

declare(strict_types=1);

require_once __DIR__ . '/ElaboracionIngredient.php';
require_once __DIR__ . '/../DBConnection.php';
require_once __DIR__ . '/../IDbAccess.php';

class ElaboracionIngredientDao implements IDbAccess
{
    public static function getAll(): ?array
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT * FROM elaboraciones_ingredients");
            $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'ElaboracionIngredient');
            $stmt->execute();
            return $stmt->fetchAll();
        } else {
            return null;
        }
    }

    public static function select($id): ?ElaboracionIngredient
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT * FROM elaboraciones_ingredients WHERE id = :id");
            $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'ElaboracionIngredient');
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
            $stmt = $conn->prepare("INSERT INTO elaboraciones_ingredients (elaboracion_id, ingrediente, cantidad, unidad, alergeno) VALUES (:elaboracion_id, :ingrediente, :cantidad, :unidad, :alergeno)");
            $stmt->execute([
                'elaboracion_id' => $object->getElaboracionId(),
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
            $stmt = $conn->prepare("DELETE FROM elaboraciones_ingredients WHERE id = :id");
            $stmt->execute(['id' => $object->getId()]);
            return $stmt->rowCount(); // Return the number of rows affected
        }
        return 0;
    }

    public static function update($object): int
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("UPDATE elaboraciones_ingredients SET elaboracion_id = :elaboracion_id, ingrediente = :ingrediente, cantidad = :cantidad, unidad = :unidad, alergeno = :alergeno WHERE id = :id");
            $stmt->execute([
                'id' => $object->getId(),
                'elaboracion_id' => $object->getElaboracionId(),
                'ingrediente' => $object->getIngrediente(),
                'cantidad' => $object->getCantidad(),
                'unidad' => $object->getUnidad(),
                'alergeno' => $object->getAlergeno()
            ]);
            return $stmt->rowCount(); // Return the number of rows affected
        }
        return 0;
    }

    public static function getIngredientsByElaboracionId(int $elaboracionId): ?array
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT * FROM elaboraciones_ingredients WHERE elaboracion_id = :elaboracion_id");
            $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'ElaboracionIngredient');
            $stmt->execute(['elaboracion_id' => $elaboracionId]);

            // Verificar si se encontraron resultados
            $ingredients = $stmt->fetchAll();
            if ($ingredients !== false) {
                // Se encontraron resultados, crear instancias de ElaboracionIngredient
                $ingredientObjects = [];
                foreach ($ingredients as $ingredientData) {
                    // Crear una instancia de ElaboracionIngredient con los datos obtenidos
                    $ingredient = new ElaboracionIngredient(
                        $ingredientData->getId(),
                        $ingredientData->getElaboracionId(),
                        $ingredientData->getIngrediente(),
                        $ingredientData->getCantidad(),
                        $ingredientData->getUnidad(),
                        $ingredientData->getAlergeno(),
                        $ingredientData->getFechaCreacion(),
                        $ingredientData->getFechaModificacion()
                    );
                    // Agregar el objeto ElaboracionIngredient al array
                    $ingredientObjects[] = $ingredient;
                }
                return $ingredientObjects;
            } else {
                // No se encontraron resultados, devolver null
                return null;
            }
        }
        return null;
    }
}
