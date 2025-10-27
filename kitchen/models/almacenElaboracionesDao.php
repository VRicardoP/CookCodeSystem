<?php

declare(strict_types=1);
require_once __DIR__ . '/almacenElaboraciones.php';
require_once __DIR__ . '/../DBConnection.php';
require_once __DIR__ . '/../IDbAccess.php';

class AlmacenElaboracionesDao implements IDbAccess
{
    public static function getAll(): ?array
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT * FROM almacenelaboraciones");
            $stmt->setFetchMode(
                PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,
                'AlmacenElaboraciones'
            );
            $stmt->execute();
            return $stmt->fetchAll();
        } else {
            return null;
        }
    }

    public static function select($id): ?AlmacenElaboraciones
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT * FROM almacenelaboraciones WHERE id = :id");
            $stmt->setFetchMode(
                PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,
                'AlmacenElaboraciones'
            );
            $stmt->execute(['id' => $id]);
            $almacenElab = $stmt->fetch();
            if ($almacenElab)
                return $almacenElab;
        }
        return null;
    }

    public static function insert($object): int
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("INSERT INTO almacenelaboraciones (ID, tipoProd, fName, packaging, productamount, fechaElab, fechaCad, warehouse, costCurrency, costPrice, saleCurrency, salePrice, codeContents, receta_id, rations_package,fileName, estado)
   VALUES (:ID, :tipoProd, :fName, :packaging, :productamount, :fechaElab, :fechaCad, :warehouse, :costCurrency, :costPrice, :saleCurrency, :salePrice, :codeContents, :receta_id, :rations_package, :fileName, :estado)");
            $stmt->execute([
                'ID' => null,
                'tipoProd' => $object->getTipoProd(),
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
                'receta_id' => $object->getRecetaId(),
                'rations_package' => $object->getRationsPackage(),
                 'fileName' => $object->getFileName(),
                 'estado' => $object->getEstado()
            ]);
            return $stmt->rowCount(); //Return the number of rows affected
        }
        return 0;
    }

    public static function delete($object): int
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("DELETE FROM almacenelaboraciones WHERE ID=:ID");
            $stmt->execute(['ID' => $object->getID()]);
            return $stmt->rowCount(); //Return the number of rows affected
        }
        return 0;
    }

    public static function update($object): int
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("UPDATE almacenelaboraciones SET 
                tipoProd=:tipoProd, fName=:fName, packaging=:packaging, productamount=:productamount, fechaElab=:fechaElab, fechaCad=:fechaCad, warehouse=:warehouse, costCurrency=:costCurrency, costPrice=:costPrice, saleCurrency=:saleCurrency, salePrice=:salePrice, codeContents=:codeContents, receta_id=:receta_id, rations_package=:rations_package, estado=:estado
                WHERE ID=:ID");
            $stmt->execute([
                'ID' => $object->getID(),
                'tipoProd' => $object->getTipoProd(),
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
                'receta_id' => $object->getRecetaId(),
                'rations_package' => $object->getRationsPackage(),
                'estado' => $object->getEstado()  
            ]);
            return $stmt->rowCount(); //Return the number of rows affected
        }
        return 0;
    }



     // Nueva función para eliminar por receta_id
     public static function deleteByRecetaId($receta_id): int
     {
         $conn = DBConnection::connectDB();
         if (!is_null($conn)) {
             $stmt = $conn->prepare("DELETE FROM almacenelaboraciones WHERE receta_id=:receta_id");
             $stmt->execute(['receta_id' => $receta_id]);
             return $stmt->rowCount(); //Return the number of rows affected
         }
         return 0;
     }


     // Función para obtener el ID del último registro insertado
    public static function getLastInsertId(): ?int
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            return (int) $conn->lastInsertId();
        }
        return null;
    }
}
?>
