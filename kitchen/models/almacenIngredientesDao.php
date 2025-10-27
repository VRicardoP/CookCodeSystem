<?php

declare(strict_types=1);
require_once __DIR__ . '/almacenIngredientes.php';
require_once __DIR__ . '/../DBConnection.php';
require_once __DIR__ . '/../IDbAccess.php';


class AlmacenIngredientesDao implements IDbAccess
{
    public static function getAll(): ?array
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT * FROM almaceningredientes");
            $stmt->setFetchMode(
                PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,
                'AlmacenIngredientes'
            );
            $stmt->execute();
            return $stmt->fetchAll();
        } else {
            return null;
        }
    }

    public static function select($id): ?AlmacenIngredientes
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT * FROM almaceningredientes WHERE ID = :id");
            $stmt->setFetchMode(
                PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,
                'AlmacenIngredientes'
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
            $stmt = $conn->prepare("INSERT INTO almaceningredientes (ID, tipoProd, fName, packaging, productamount, fechaElab, fechaCad, warehouse, costCurrency, costPrice, saleCurrency, salePrice, codeContents, ingrediente_id, cantidad_paquete, estado)
   VALUES (null, :tipoProd, :fName, :packaging, :productamount, :fechaElab, :fechaCad, :warehouse, :costCurrency, :costPrice, :saleCurrency, :salePrice, :codeContents, :ingrediente_id, :cantidad_paquete, :estado)");
            $stmt->execute([
                'tipoProd' => $object->getTipoProd(),
                'fName' => $object->getFName(),
                'packaging' => $object->getPackaging(),
                'productamount' => $object->getProductAmount(),
                'fechaElab' => $object->getFechaElab(),
                'fechaCad' => $object->getFechaCad(),
                'warehouse' => $object->getWarehouse(),
                'costCurrency' => $object->getCostCurrency(),
                'costPrice' => $object->getCostPrice(),
                'saleCurrency' => $object->getSaleCurrency(),
                'salePrice' => $object->getSalePrice(),
                'codeContents' => $object->getCodeContents(),
                'ingrediente_id' => $object->getIngredienteId(),
                'cantidad_paquete' => $object->getCantidadPaquete(),
                'estado' => $object->getEstado(),
            ]);
            return $stmt->rowCount();
        }
        return 0;
    }

    public static function delete($object): int
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("DELETE FROM almaceningredientes WHERE ID=:id");
            $stmt->execute(['id' => $object->getID()]);
            return $stmt->rowCount();
        }
        return 0;
    }

    public static function update($object): int
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("UPDATE almaceningredientes SET tipoProd=:tipoProd, fName=:fName, packaging=:packaging, productamount=:productamount, fechaElab=:fechaElab, fechaCad=:fechaCad, warehouse=:warehouse, costCurrency=:costCurrency, costPrice=:costPrice, saleCurrency=:saleCurrency, salePrice=:salePrice, codeContents=:codeContents, ingrediente_id=:ingrediente_id, cantidad_paquete=:cantidad_paquete, estado=:estado WHERE ID=:id");
            $stmt->execute([
                'id' => $object->getID(),
                'tipoProd' => $object->getTipoProd(),
                'fName' => $object->getFName(),
                'packaging' => $object->getPackaging(),
                'productamount' => $object->getProductAmount(),
                'fechaElab' => $object->getFechaElab(),
                'fechaCad' => $object->getFechaCad(),
                'warehouse' => $object->getWarehouse(),
                'costCurrency' => $object->getCostCurrency(),
                'costPrice' => $object->getCostPrice(),
                'saleCurrency' => $object->getSaleCurrency(),
                'salePrice' => $object->getSalePrice(),
                'codeContents' => $object->getCodeContents(),
                'ingrediente_id' => $object->getIngredienteId(),
                'cantidad_paquete' => $object->getCantidadPaquete(),
                'estado' => $object->getEstado(),
            ]);
            return $stmt->rowCount(); //Return the number of rows affected
        }
        return 0;
    }   

    public static function deleteByIngredienteId($ingrediente_id): int
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("DELETE FROM almaceningredientes WHERE ingrediente_id=:ingrediente_id");
            $stmt->execute(['ingrediente_id' => $ingrediente_id]);
            return $stmt->rowCount(); //Return the number of rows affected
        }
        return 0;
    }

    public static function getLastInsertId(): int
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            return (int) $conn->lastInsertId();  // Obtener y retornar el ID del último insert
        }
        return 0;  // Retorna 0 si no se pudo obtener el ID
    }


}
