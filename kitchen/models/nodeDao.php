<?php

declare(strict_types=1);
require_once __DIR__ . '/Node.php';
require_once __DIR__ . '/../DBConnection.php';
require_once __DIR__ . '/../IDbAccess.php';


class NodeDao implements IDbAccess
{
    public static function getAll(): ?array
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT * FROM node");
            $stmt->setFetchMode(
                PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,
                'Node'
            );
            $stmt->execute();
            return $stmt->fetchAll();
        } else {
            return null;
        }
    }

    public static function select($id): ?Node
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT * FROM node WHERE id = :id");
            $stmt->setFetchMode(
                PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,
                'Node'
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
            $stmt = $conn->prepare("INSERT INTO node (id, method, noun, unit, quantity, standard_deviation, frequency)
   VALUES (null, :method, :noun, :unit, :quantity, :standard_deviation, :frequency)");
            $stmt->execute([
                'method' => $object->getMethod(),
                'noun' => $object->getNoun(),
                'unit' => $object->getUnit(),
                'quantity' => $object->getQuantity(),
                'standard_deviation' => $object->getStandardDeviation(),
                'frequency' => $object->getFrequency(),
            ]);
            return $stmt->rowCount();
        }
        return 0;
    }

    public static function delete($object): int
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("DELETE FROM node WHERE id=:id");
            $stmt->execute(['id' => $object->getId()]);
            return $stmt->rowCount();
        }
        return 0;
    }

    public static function update($object): int
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("UPDATE node SET method=:method, noun=:noun, unit=:unit, quantity=:quantity, standard_deviation=:standard_deviation, frequency=:frequency WHERE id=:id");
            $stmt->execute([
                'id' => $object->getId(),
                'method' => $object->getMethod(),
                'noun' => $object->getNoun(),
                'unit' => $object->getUnit(),
                'quantity' => $object->getQuantity(),
                'standard_deviation' => $object->getStandardDeviation(),
                'frequency' => $object->getFrequency(),
            ]);
            return $stmt->rowCount();
        }
        return 0;
    }
}
