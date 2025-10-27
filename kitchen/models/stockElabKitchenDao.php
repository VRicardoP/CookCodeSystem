<?php

declare(strict_types=1);
require_once __DIR__ . '/stockElabKitchen.php';
require_once __DIR__ . '/../DBConnection.php';
require_once __DIR__ . '/../IDbAccess.php';

class StockElabKitchenDao implements IDbAccess
{
    public static function getAll(): ?array
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT * FROM stock_elab_kitchen");
            $stmt->setFetchMode(
                PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,
                'StockElabKitchen'
            );
            $stmt->execute();
            return $stmt->fetchAll();
        } else {
            return null;
        }
    }

    public static function select($id): ?StockElabKitchen
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT * FROM stock_elab_kitchen WHERE id = :id");
            $stmt->setFetchMode(
                PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,
                'StockElabKitchen'
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
                INSERT INTO stock_elab_kitchen (id, receta_id, stock)
                VALUES (NULL, :receta_id, :stock)
            ");
            $stmt->execute([
             
                'receta_id' => $object->getRecetaId(),
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
            $stmt = $conn->prepare("DELETE FROM stock_elab_kitchen WHERE id = :id");
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
                UPDATE stock_elab_kitchen 
                SET  receta_id = :receta_id , stock = :stock
                WHERE id = :id
            ");
            $stmt->execute([
                'id' => $object->getId(),
                'receta_id' => $object->getRecetaId(),
                'stock' => $object->getStock(),
               
            ]);
            return $stmt->rowCount();
        }
        return 0;
    }

    public static function selectByRecetaId($receta_id): ?StockElabKitchen
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT * FROM stock_elab_kitchen WHERE receta_id = :receta_id");
            $stmt->setFetchMode(
                PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,
                'StockElabKitchen'
            );
            $stmt->execute(['receta_id' => $receta_id]);
            $result = $stmt->fetch();
            if ($result)
                return $result;
        }
        return null;
    }

    public static function deleteByRecetaId($receta_id): int
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("DELETE FROM stock_elab_kitchen WHERE receta_id=:receta_id");
            $stmt->execute(['receta_id' => $receta_id]);
            return $stmt->rowCount(); //Return the number of rows affected
        }
        return 0;
    }
}
?>
