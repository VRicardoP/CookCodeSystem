<?php

declare(strict_types=1);

require_once __DIR__ . '/precioProducto.php';
require_once __DIR__ . '/../DBConnection.php';
require_once __DIR__ . '/../IDbAccess.php';


class PreciosProductoDAO implements IDbAccess
{


    public static function getAll(): ?array
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT * FROM precios_producto");
            $stmt->setFetchMode(
                PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,
                'PreciosProducto'
            );
            $stmt->execute();
            return $stmt->fetchAll();
        } else {
            return null;
        }
    }

    public static function select($id): ?PreciosProducto
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT * FROM precios_producto WHERE id = :id");
            $stmt->setFetchMode(
                PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,
                'PreciosProducto'
            );
            $stmt->execute(['id' => $id]);
            $precioProducto = $stmt->fetch();
            if ($precioProducto) {
                return $precioProducto;
            }
        }
        return null;
    }

    public static function insert($object): int
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("INSERT INTO precios_producto (producto, unidad, precio, merma) VALUES (:producto, :unidad, :precio, :merma)");
            $stmt->execute([
                'producto' => $object->getProducto(),
                'unidad' => $object->getUnidad(),
                'precio' => $object->getPrecio(),
                'merma' => $object->getMerma() // Nuevo campo
            ]);
            return $stmt->rowCount(); //Return the number of rows affected
        }
        return 0;
    }

    public static function delete($object): int
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("DELETE FROM precios_producto WHERE id=:id");
            $stmt->execute(['id' => $object->getId()]);
            return $stmt->rowCount(); //Return the number of rows affected
        }
        return 0;
    }

    public static function update($object): int
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("UPDATE precios_producto SET producto=:producto, unidad=:unidad, precio=:precio, merma=:merma WHERE id=:id");
            $stmt->execute([
                'id' => $object->getId(),
                'producto' => $object->getProducto(),
                'unidad' => $object->getUnidad(),
                'precio' => $object->getPrecio(),
                'merma' => $object->getMerma() // Nuevo campo
            ]);
            return $stmt->rowCount(); //Return the number of rows affected
        }
        return 0;
    }



    public static function getByProductName(string $productName): ?PreciosProducto
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT * FROM precios_producto WHERE producto = :producto");
            $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'PreciosProducto');
            $stmt->execute(['producto' => $productName]);
            $precioProducto = $stmt->fetch();
            if ($precioProducto) {
                return $precioProducto;
            }
        }
        return null;
    }
}
