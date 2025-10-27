<?php

declare(strict_types=1);
require_once __DIR__ . '/tagsPreelaboraciones.php';
require_once __DIR__ . '/../DBConnection.php';
require_once __DIR__ . '/../IDbAccess.php';


class TagsPreelaboracionesDao implements IDbAccess
{
    public static function getAll(): ?array
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT * FROM tagspreelaboraciones");
            $stmt->setFetchMode(
                PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,
                'TagsPreelaboraciones'
            );
            $stmt->execute();
            return $stmt->fetchAll();
        } else {
            return null;
        }
    }

    public static function select($id): ?TagsPreelaboraciones
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT * FROM tagspreelaboraciones WHERE IDTag = :id");
            $stmt->setFetchMode(
                PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,
                'TagsPreelaboraciones'
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
            $stmt = $conn->prepare("INSERT INTO tagspreelaboraciones (IDTag, tempDir, email, filename, fName, packaging, productamount, fechaElab, fechaCad, warehouse, costCurrency, costPrice, saleCurrency, salePrice, codeContents, image)
   VALUES (null, :tempDir, :email, :filename, :fName, :packaging, :productamount, :fechaElab, :fechaCad, :warehouse, :costCurrency, :costPrice, :saleCurrency, :salePrice, :codeContents, :image)");
            $stmt->execute([
                'tempDir' => $object->getTempDir(),
                'email' => $object->getEmail(),
                'filename' => $object->getFilename(),
                'fName' => $object->getFName(),
                'packaging' => $object->getPackaging(),
                'productamount' => $object->getProductamount(),
                'fechaElab' => $object->getFechaElab(),
                'fechaCad' => $object->getFechaCad(),
                'warehouse' => $object->getWarehouse(),
                'costCurrency' => $object->getCostCurrency(),
                'costPrice' => $object->getCostPrice(),
                'saleCurrency' => $object->getSaleCurrency(),
                'salePrice' => $object->getSalePrice(),
                'codeContents' => $object->getCodeContents(),
                'image' => $object->getImage(),
            ]);
            return $stmt->rowCount(); //Return the number of rows affected
        }
        return 0;
    }

    public static function delete($object): int
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("DELETE FROM tagspreelaboraciones WHERE IDTag=:id");
            $stmt->execute(['id' => $object->getIDTag()]);
            return $stmt->rowCount(); //Return the number of rows affected
        }
        return 0;
    }

    public static function update($object): int
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("UPDATE tagspreelaboraciones SET tempDir=:tempDir, email=:email, filename=:filename, fName=:fName, packaging=:packaging, productamount=:productamount, fechaElab=:fechaElab, fechaCad=:fechaCad, warehouse=:warehouse, costCurrency=:costCurrency, costPrice=:costPrice, saleCurrency=:saleCurrency, salePrice=:salePrice, codeContents=:codeContents, image=:image WHERE IDTag=:id");
            $stmt->execute([
                'id' => $object->getIDTag(),
                'tempDir' => $object->getTempDir(),
                'email' => $object->getEmail(),
                'filename' => $object->getFilename(),
                'fName' => $object->getFName(),
                'packaging' => $object->getPackaging(),
                'productamount' => $object->getProductamount(),
                'fechaElab' => $object->getFechaElab(),
                'fechaCad' => $object->getFechaCad(),
                'warehouse' => $object->getWarehouse(),
                'costCurrency' => $object->getCostCurrency(),
                'costPrice' => $object->getCostPrice(),
                'saleCurrency' => $object->getSaleCurrency(),
                'salePrice' => $object->getSalePrice(),
                'codeContents' => $object->getCodeContents(),
                'image' => $object->getImage(),
            ]);
            return $stmt->rowCount(); //Return the number of rows affected
        }
        return 0;
    }


    public static function getLastInsertId() {
        $conn = DBConnection::connectDB(); 
        return $conn->lastInsertId();
    }


}
