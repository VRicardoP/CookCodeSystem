<?php

declare(strict_types=1);
require_once __DIR__ . '/stockIngKitchen.php';
require_once __DIR__ . '/../DBConnection.php';
require_once __DIR__ . '/../IDbAccess.php';

class StockIngKitchenDao implements IDbAccess
{
    public static function getAll(): ?array
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT * FROM stock_ing_kitchen");
            $stmt->setFetchMode(
                PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,
                'StockIngKitchen'
            );
            $stmt->execute();
            return $stmt->fetchAll();
        } else {
            return null;
        }
    }

    public static function select($id): ?StockIngKitchen
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT * FROM stock_ing_kitchen WHERE id = :id");
            $stmt->setFetchMode(
                PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,
                'StockIngKitchen'
            );
            $stmt->execute(['id' => $id]);
            $result = $stmt->fetch();
            if ($result)
                return $result;
        }
        return null;
    }

    public static function insert($object): int
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("
                INSERT INTO stock_ing_kitchen (id, ingredient_id, stock)
                VALUES (NULL, :ingredient_id, :stock)
            ");
            $stmt->execute([
             
                'ingredient_id' => $object->getIngredientId(),
                'stock' => $object->getStock()
            ]);
            return $stmt->rowCount();
        }
        return 0;
    }

    public static function delete($object): int
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("DELETE FROM stock_ing_kitchen WHERE id = :id");
            $stmt->execute(['id' => $object->getId()]);
            return $stmt->rowCount();
        }
        return 0;
    }

    public static function update($object): int
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("
                UPDATE stock_ing_kitchen 
                SET  ingredient_id = :ingredient_id , stock = :stock, stock_ecommerce = :stock_ecommerce
                WHERE id = :id
            ");
            $stmt->execute([
                'id' => $object->getId(),
                'ingredient_id' => $object->getIngredientId(),
                'stock' => $object->getStock(),
                'stock_ecommerce' => $object->getStockEcommerce(),
            ]);
            return $stmt->rowCount();
        }
        return 0;
    }

    public static function selectByIngredientId($ingredient_id): ?StockIngKitchen
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT * FROM stock_ing_kitchen WHERE ingredient_id = :ingredient_id");
            $stmt->setFetchMode(
                PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,
                'StockIngKitchen'
            );
            $stmt->execute(['ingredient_id' => $ingredient_id]);
            $result = $stmt->fetch();
            if ($result)
                return $result;
        }
        return null;
    }


    public static function deleteByIngredienteId($ingrediente_id): int
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("DELETE FROM stock_ing_kitchen WHERE ingredient_id=:ingredient_id");
            $stmt->execute(['ingredient_id' => $ingrediente_id]);
            return $stmt->rowCount(); //Return the number of rows affected
        }
        return 0;
    }
}
?>
