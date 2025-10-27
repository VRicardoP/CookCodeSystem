<?php

declare(strict_types=1);
require_once __DIR__ . '/unit.php';
require_once __DIR__ . '/../DBConnection.php';
require_once __DIR__ . '/../IDbAccess.php';


class UnitDao implements IDbAccess
{
    public static function getAll(): ?array
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT * FROM unit");
            $stmt->setFetchMode(
                PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,
                'Unit'
            );
            $stmt->execute();
            return $stmt->fetchAll();
        } else {
            return null;
        }
    }

    
    public static function select($id): ?Unit
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT * FROM unit WHERE unit = :unit");
            $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Unit');
            $stmt->execute(['unit' => $id]);
            $unit = $stmt->fetch();
            if ($unit)
                return $unit;
        }
        return null;
    }

    public static function insert($object): int
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("INSERT INTO unit (unit) VALUES (:unit)");
            $stmt->execute(['unit' => $object->getUnit()]);
            return $stmt->rowCount(); //Return the number of rows affected
        }
        return 0;
    }

    public static function delete($object): int
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("DELETE FROM unit WHERE unit = :unit");
            $stmt->execute(['unit' => $object->getUnit()]);
            return $stmt->rowCount(); //Return the number of rows affected
        }
        return 0;
    }

    public static function update($object): int
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("UPDATE unit SET unit = :unit WHERE unit = :old_unit");
            $stmt->execute([
                'unit' => $object->getUnit(),
                'old_unit' => $object->getOldUnit() // Si necesitas actualizar el valor de unit, debes proporcionar el valor antiguo
            ]);
            return $stmt->rowCount(); //Return the number of rows affected
        }
        return 0;
    }


   


}
