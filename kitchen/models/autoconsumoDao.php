<?php

declare(strict_types=1);
require_once __DIR__ . '/autoconsumo.php'; 
require_once __DIR__ . '/../DBConnection.php';
require_once __DIR__ . '/../IDbAccess.php';

class AutoconsumoDao implements IDbAccess
{
    public static function getAll() {
        $db = DBConnection::connectDB();
        $query = $db->prepare("SELECT id, name, cantidad, fecha_consumo, coste FROM autoconsumo");
        $query->execute();
        
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
    
        $autoconsumos = [];
    
        foreach ($result as $row) {
            // Asegúrate de pasar los 4 parámetros necesarios
            $autoconsumos[] = new Autoconsumo($row['id'], $row['name'], $row['cantidad'], $row['fecha_consumo'], $row['coste']);
        }
    
        return $autoconsumos;
    }
    public static function select($id): ?Autoconsumo
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT * FROM autoconsumo WHERE id = :id");
            $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Autoconsumo');
            $stmt->execute(['id' => $id]);
            $autoconsumo = $stmt->fetch();
            if ($autoconsumo)
                return $autoconsumo;
        }
        return null;
    }

    public static function insert($object): int
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("INSERT INTO autoconsumo (name, cantidad, fecha_consumo, coste) VALUES (:name, :cantidad, :fecha_consumo, :coste)");
            $stmt->execute([
                'name' => $object->getName(),
                'cantidad' => $object->getCantidad(),
                'fecha_consumo' => $object->getFechaConsumo(),
                'coste' => $object->getCoste()
            ]);
            return $stmt->rowCount(); //Return the number of rows affected
        }
        return 0;
    }

    public static function delete($object): int
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("DELETE FROM autoconsumo WHERE id = :id");
            $stmt->execute(['id' => $object->getId()]);
            return $stmt->rowCount(); //Return the number of rows affected
        }
        return 0;
    }

    public static function update($object): int
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("UPDATE autoconsumo SET name = :name, cantidad = :cantidad, fecha_consumo = :fecha_consumo, coste = :coste WHERE id = :id");
            $stmt->execute([
                'name' => $object->getName(),
                'cantidad' => $object->getCantidad(),
                'fecha_consumo' => $object->getFechaConsumo(),
                'coste' => $object->getCoste(),
                'id' => $object->getId()
            ]);
            return $stmt->rowCount(); //Return the number of rows affected
        }
        return 0;
    }
}
?>
