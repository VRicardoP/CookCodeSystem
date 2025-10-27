<?php

declare(strict_types=1);

require_once __DIR__ . '/grupos.php';
require_once __DIR__ . '/../DBConnection.php';
require_once __DIR__ . '/../IDbAccess.php';

class GruposDao implements IDbAccess
{
    public static function getAll(): ?array
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT * FROM grupos");
            $stmt->setFetchMode(
                PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,
                'Grupo'
            );
            $stmt->execute();
            return $stmt->fetchAll();
        } else {
            return null;
        }
    }

    public static function select($id): ?Grupo
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT * FROM grupos WHERE id = :id");
            $stmt->setFetchMode(
                PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,
                'Grupo'
            );
            $stmt->execute(['id' => $id]);
            $grupo = $stmt->fetch();
            if ($grupo) {
                return $grupo;
            }
        }
        return null;
    }

    public static function insert($object): int
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("INSERT INTO grupos (id, nombre) VALUES (:id, :nombre)");
            $stmt->execute([
                'id' => null,
                'nombre' => $object->getNombre()
            ]);
            return $stmt->rowCount(); //Return the number of rows affected
        }
        return 0;
    }

    public static function delete($object): int
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("DELETE FROM grupos WHERE id=:id");
            $stmt->execute(['id' => $object->getId()]);
            return $stmt->rowCount(); //Return the number of rows affected
        }
        return 0;
    }

    public static function update($object): int
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("UPDATE grupos SET nombre=:nombre WHERE id=:id");
            $stmt->execute([
                'id' => $object->getId(),
                'nombre' => $object->getNombre()
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
