<?php

declare(strict_types=1);
require_once __DIR__ . '/elaboraciones.php';
require_once __DIR__ . '/../DBConnection.php';
require_once __DIR__ . '/../IDbAccess.php';


class ElaboracionesDao implements IDbAccess
{
    public static function getAll(): ?array
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT * FROM elaboraciones");
            $stmt->setFetchMode(
                PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,
                'Elaboraciones'
            );
            $stmt->execute();
            return $stmt->fetchAll();
        } else {
            return null;
        }
    }


    public static function select($id): ?Elaboraciones
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT * FROM elaboraciones WHERE ID = :id");
            $stmt->setFetchMode(
                PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,
                'Elaboraciones'
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
            $stmt = $conn->prepare("INSERT INTO elaboraciones (ID, fName, receta, merma, packaging, productamount, fechaElab, caducidad, warehouse, costCurrency, costPrice, saleCurrency, salePrice, codeContents, image)
   VALUES (null, :fName, :receta, :merma, :packaging, :productamount, :fechaElab, :caducidad, :warehouse, :costCurrency, :costPrice, :saleCurrency, :salePrice, :codeContents, :image)");
            $stmt->execute([
                'fName' => $object->getFName(),
                'receta' => $object->getReceta(),

                'merma' => $object->getMerma(),

                'packaging' => $object->getPackaging(),
                'productamount' => $object->getProductAmount(),
                'fechaElab' => $object->getFechaElab(),
                'caducidad' => $object->getCaducidad(),
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
            $stmt = $conn->prepare("DELETE FROM elaboraciones WHERE ID=:id");
            $stmt->execute(['id' => $object->getID()]);
            return $stmt->rowCount();
        }
        return 0;
    }

    public static function update($object): int
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("UPDATE elaboraciones SET fName=:fName, packaging=:packaging, productamount=:productamount, fechaElab=:fechaElab, caducidad=:caducidad, warehouse=:warehouse, costCurrency=:costCurrency, costPrice=:costPrice, saleCurrency=:saleCurrency, salePrice=:salePrice, codeContents=:codeContents, image=:image WHERE ID=:id");
            $stmt->execute([
                'id' => $object->getID(),
                'fName' => $object->getFName(),
                'receta' => $object->getReceta(),

                'merma' => $object->getMerma(),


                'packaging' => $object->getPackaging(),
                'productamount' => $object->getProductAmount(),
                'fechaElab' => $object->getFechaElab(),
                'caducidad' => $object->getCaducidad(),
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
