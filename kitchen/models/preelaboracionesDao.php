<?php

declare(strict_types=1);
require_once __DIR__ . '/preelaboraciones.php';
require_once __DIR__ . '/../DBConnection.php';
require_once __DIR__ . '/../IDbAccess.php';

class PreelaboracionesDao implements IDbAccess
{
    public static function getAll(): ?array
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT * FROM preelaboraciones");
            $stmt->setFetchMode(
                PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,
                'Preelaboraciones'
            );
            $stmt->execute();
            return $stmt->fetchAll();
        } else {
            return null;
        }
    }

    public static function select($id): ?Preelaboracion
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT * FROM preelaboraciones WHERE id = :id");
            $stmt->setFetchMode(
                PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,
                'Preelaboracion'
            );
            $stmt->execute(['id' => $id]);
            $contact = $stmt->fetch();
            if ($contact)
                return $contact;
        }
        return null;
    }

    public static function insert($object): int
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("INSERT INTO preelaboraciones (ID, fName, packaging, productamount, cad, warehouse, costCurrency, costPrice, saleCurrency, salePrice, codeContents, image)
   VALUES (null, :fName, :packaging, :productamount, :cad, :warehouse, :costCurrency, :costPrice, :saleCurrency, :salePrice, :codeContents, :image)");
            $stmt->execute([
                'fName' => $object->getFName(),
                'packaging' => $object->getPackaging(),
                'productamount' => $object->getProductAmount(),
                'cad' => $object->getcad(),
                'warehouse' => $object->getWarehouse(),
                'costCurrency' => $object->getCostCurrency(),
                'costPrice' => $object->getCostPrice(),
                'saleCurrency' => $object->getSaleCurrency(),
                'salePrice' => $object->getSalePrice(),
                'codeContents' => $object->getCodeContents(),
                'image' => $object->getImage(),
            ]);
            return $stmt->rowCount();
        }
        return 0;
    }

    public static function delete($object): int
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("DELETE FROM preelaboraciones WHERE ID=:id");
            $stmt->execute(['id' => $object->getID()]);
            return $stmt->rowCount();
        }
        return 0;
    }

    public static function update($object): int
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("UPDATE preelaboraciones SET fName=:fName, packaging=:packaging, productamount=:productamount, cad=:cad, warehouse=:warehouse, costCurrency=:costCurrency, costPrice=:costPrice, saleCurrency=:saleCurrency, salePrice=:salePrice, codeContents=:codeContents, image=:image WHERE ID=:id");
            $stmt->execute([
                'id' => $object->getID(),
                'fName' => $object->getFName(),
                'packaging' => $object->getPackaging(),
                'productamount' => $object->getProductAmount(),
                'cad' => $object->getCad(),
                'warehouse' => $object->getWarehouse(),
                'costCurrency' => $object->getCostCurrency(),
                'costPrice' => $object->getCostPrice(),
                'saleCurrency' => $object->getSaleCurrency(),
                'salePrice' => $object->getSalePrice(),
                'codeContents' => $object->getCodeContents(),
                'image' => $object->getImage(),
            ]);
            return $stmt->rowCount();
        }
        return 0;
    }

    
    public static function getLastInsertId() {
        $conn = DBConnection::connectDB(); 
        return $conn->lastInsertId();
    }
}

