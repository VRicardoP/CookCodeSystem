<?php

declare(strict_types=1);
require_once __DIR__ . '/tagsElaboraciones.php';
require_once __DIR__ . '/../DBConnection.php';
require_once __DIR__ . '/../IDbAccess.php';

class TagsElaboracionesDao implements IDbAccess
{
    public static function getAll(): ?array
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT * FROM tagselaboraciones");
            $stmt->setFetchMode(
                PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,
                'TagsElaboraciones'
            );
            $stmt->execute();
            return $stmt->fetchAll();
        } else {
            return null;
        }
    }

    public static function select($id): ?TagsElaboraciones
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT * FROM tagselaboraciones WHERE IDTag = :id");
            $stmt->setFetchMode(
                PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,
                'TagsElaboraciones'
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
            $stmt = $conn->prepare("INSERT INTO tagselaboraciones (IDTag, tempDir, email, filename, fName, packaging, productamount, fechaElab, fechaCad, warehouse, costCurrency, costPrice, saleCurrency, salePrice, codeContents, image, receta_id, rations_package)
   VALUES (null, :tempDir, :email, :filename, :fName, :packaging, :productamount, :fechaElab, :fechaCad, :warehouse, :costCurrency, :costPrice, :saleCurrency, :salePrice, :codeContents, :image, :receta_id, :rations_package)");
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
                'receta_id' => $object->getRecetaId(),
                'rations_package' => $object->getRationsPackage()  
            ]);
            return $stmt->rowCount(); //Return the number of rows affected
        }
        return 0;
    }

    
    
    public static function delete($object): int
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("DELETE FROM tagselaboraciones WHERE IDTag=:id");
            $stmt->execute(['id' => $object->getIDTag()]);
            return $stmt->rowCount(); //Return the number of rows affected
        }
        return 0;
    }

    public static function update($object): int
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("UPDATE tagselaboraciones SET tempDir=:tempDir, email=:email, filename=:filename, fName=:fName, packaging=:packaging, productamount=:productamount, fechaElab=:fechaElab, fechaCad=:fechaCad, warehouse=:warehouse, costCurrency=:costCurrency, costPrice=:costPrice, saleCurrency=:saleCurrency, salePrice=:salePrice, codeContents=:codeContents, image=:image, receta_id=:receta_id, rations_package=:rations_package WHERE IDTag=:id");
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
                'receta_id' => $object->getRecetaId(),
                'rations_package' => $object->getRationsPackage()  
            ]);
            return $stmt->rowCount(); //Return the number of rows affected
        }
        return 0;
    }


    public static function getLastInsertId() {
        $conn = DBConnection::connectDB(); 
        return $conn->lastInsertId();
    }

    public static function deleteByRecetaId($receta_id): int
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("DELETE FROM tagselaboraciones WHERE receta_id=:receta_id");
            $stmt->execute(['receta_id' => $receta_id]);
            return $stmt->rowCount(); //Return the number of rows affected
        }
        return 0;
    }
}
