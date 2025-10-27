<?php

declare(strict_types=1);
require_once __DIR__ . '/tagsIngredientes.php';
require_once __DIR__ . '/../DBConnection.php';
require_once __DIR__ . '/../IDbAccess.php';


class TagsIngredientesDao implements IDbAccess
{
    public static function getAll(): ?array
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT * FROM tagsingredientes");
            $stmt->setFetchMode(
                PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,
                'TagsIngredientes'
            );
            $stmt->execute();
            return $stmt->fetchAll();
        } else {
            return null;
        }
    }

    public static function select($id): ?TagsIngredientes
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT * FROM tagsingredientes WHERE IDTag = :id");
            $stmt->setFetchMode(
                PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,
                'TagsIngredientes'
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
            $stmt = $conn->prepare("INSERT INTO tagsingredientes (IDTag, tempDir, email, filename, fName, packaging, productamount, fechaElab, fechaCad, warehouse, costCurrency, costPrice, saleCurrency, salePrice, codeContents, image, ingrediente_id, cantidad_paquete)
   VALUES (null, :tempDir, :email, :filename, :fName, :packaging, :productamount, :fechaElab, :fechaCad, :warehouse, :costCurrency, :costPrice, :saleCurrency, :salePrice, :codeContents, :image, :ingrediente_id, :cantidad_paquete)");
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
                'ingrediente_id' => $object->getIngredienteId(),
                'cantidad_paquete' => $object->getCantidadPaquete(),
            ]);
            return $stmt->rowCount(); //Return the number of rows affected
        }
        return 0;
    }

    public static function delete($object): int
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("DELETE FROM tagsingredientes WHERE IDTag=:id");
            $stmt->execute(['id' => $object->getIDTag()]);
            return $stmt->rowCount(); //Return the number of rows affected
        }
        return 0;
    }

    public static function update($object): int
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("UPDATE tagsingredientes SET tempDir=:tempDir, email=:email, filename=:filename, fName=:fName, packaging=:packaging, productamount=:productamount, fechaElab=:fechaElab, fechaCad=:fechaCad, warehouse=:warehouse, costCurrency=:costCurrency, costPrice=:costPrice, saleCurrency=:saleCurrency, salePrice=:salePrice, codeContents=:codeContents, image=:image, ingrediente_id=:ingrediente_id, cantidad_paquete=:cantidad_paquete WHERE IDTag=:id");
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
                'ingrediente_id' => $object->getIngredienteId(),
                'cantidad_paquete' => $object->getCantidadPaquete(),
            ]);
            return $stmt->rowCount(); //Return the number of rows affected
        }
        return 0;
    }

    public static function getLastInsertId() {
        $conn = DBConnection::connectDB(); 
        return $conn->lastInsertId();
    }


    public static function deleteByIngredienteId($ingrediente_id): int
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("DELETE FROM tagsingredientes WHERE ingrediente_id=:ingrediente_id");
            $stmt->execute(['ingrediente_id' => $ingrediente_id]);
            return $stmt->rowCount(); //Return the number of rows affected
        }
        return 0;
    }
}
