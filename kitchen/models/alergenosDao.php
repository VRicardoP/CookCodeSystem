<?php

declare(strict_types=1);
require_once __DIR__ . '/alergenos.php'; 
require_once __DIR__ . '/../DBConnection.php';
require_once __DIR__ . '/../IDbAccess.php';

class AlergenoDao implements IDbAccess
{
    public static function getAll(): ?array
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT * FROM alergenos");
            $stmt->setFetchMode(
                PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,
                'Alergeno'
            );
            $stmt->execute();
            return $stmt->fetchAll();
        } else {
            return null;
        }
    }

    
    public static function select($id): ?Alergeno
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT * FROM alergenos WHERE id = :id");
            $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Alergeno');
            $stmt->execute(['id' => $id]);
            $alergeno = $stmt->fetch();
            if ($alergeno)
                return $alergeno;
        }
        return null;
    }

    public static function insert($object): int
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("INSERT INTO alergenos (nombre) VALUES (:nombre)");
            $stmt->execute(['nombre' => $object->getNombre()]);
            return $stmt->rowCount(); //Return the number of rows affected
        }
        return 0;
    }

    public static function delete($object): int
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("DELETE FROM alergenos WHERE id = :id");
            $stmt->execute(['id' => $object->getId()]);
            return $stmt->rowCount(); //Return the number of rows affected
        }
        return 0;
    }

    public static function update($object): int
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("UPDATE alergenos SET nombre = :nombre WHERE id = :id");
            $stmt->execute([
                'nombre' => $object->getNombre(),
                'id' => $object->getId()
            ]);
            return $stmt->rowCount(); //Return the number of rows affected
        }
        return 0;
    }
}
?>
