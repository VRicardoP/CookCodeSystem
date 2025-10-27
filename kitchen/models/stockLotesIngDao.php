<?php

declare(strict_types=1);
require_once __DIR__ . '/stockLotesIng.php';
require_once __DIR__ . '/../DBConnection.php';
require_once __DIR__ . '/../IDbAccess.php';

class StockLotesIngDao implements IDbAccess
{
    public static function getAll(): ?array
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT * FROM stock_lotes_ing");
            $stmt->setFetchMode(
                PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,
                'StockLotesIng'
            );
            $stmt->execute();
            return $stmt->fetchAll();
        } else {
            return null;
        }
    }

    public static function select($id): ?StockLotesIng
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT * FROM stock_lotes_ing WHERE id = :id");
            $stmt->setFetchMode(
                PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,
                'StockLotesIng'
            );
            $stmt->execute(['id' => $id]);
            $result = $stmt->fetch();
            if ($result)
                return $result;
        }
        return null;
    }

    public static function insert($object): int
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("
                INSERT INTO stock_lotes_ing (ingrediente_id, lote, cantidad, unidades, elaboracion, caducidad, coste, tipo_unidad, cantidad_total)
                VALUES (:ingrediente_id, :lote, :cantidad, :unidades, :elaboracion, :caducidad, :coste, :tipo_unidad, :cantidad_total)
            ");
            $stmt->execute([
                'ingrediente_id' => $object->getIngredientId(),
                'lote' => $object->getLote(),
                'cantidad' => $object->getCantidad(),
                'unidades' => $object->getUnidades(),
                'elaboracion' => $object->getElaboracion(),
                'caducidad' => $object->getCaducidad(),
                'coste' => $object->getCoste(),
                'tipo_unidad' => $object->getTipoUnidad(),
                'cantidad_total' => $object->getCantidadTotal()
            ]);
            return $stmt->rowCount();
        }
        return 0;
    }

    public static function delete($object): int
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("DELETE FROM stock_lotes_ing WHERE id = :id");
            $stmt->execute(['id' => $object->getId()]);
            return $stmt->rowCount();
        }
        return 0;
    }

    public static function update($object): int
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("
                UPDATE stock_lotes_ing 
                SET ingrediente_id = :ingrediente_id, lote = :lote, cantidad = :cantidad, unidades = :unidades, 
                    elaboracion = :elaboracion, caducidad = :caducidad, coste = :coste, 
                    tipo_unidad = :tipo_unidad, cantidad_total = :cantidad_total
                WHERE id = :id
            ");
            $stmt->execute([
                'id' => $object->getId(),
                'ingrediente_id' => $object->getIngredientId(),
                'lote' => $object->getLote(),
                'cantidad' => $object->getCantidad(),
                'unidades' => $object->getUnidades(),
                'elaboracion' => $object->getElaboracion(),
                'caducidad' => $object->getCaducidad(),
                'coste' => $object->getCoste(),
                'tipo_unidad' => $object->getTipoUnidad(),
                'cantidad_total' => $object->getCantidadTotal()
            ]);
            return $stmt->rowCount();
        }
        return 0;
    }

    public static function selectByIngredientId($ingredient_id): ?array
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT * FROM stock_lotes_ing WHERE ingrediente_id = :ingrediente_id AND caducidad > CURDATE();");
            $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'StockLotesIng');
            $stmt->execute(['ingrediente_id' => $ingredient_id]);
            $results = $stmt->fetchAll();
            
            // Depuración: Verifica si hay resultados
            if (!$results) {
                // echo "No se encontraron resultados para el ingrediente: $ingredient_id\no el producto está caducado";
                // exit;

                return null; // Devuelve null si no hay resultados
            }
            
            return $results; // Devuelve un array de objetos StockLotesIng
        }
    
        return null; // Devuelve null si no hay resultados
    }
    


    public static function updateLote(StockLotesIng $lote): int
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("
                UPDATE stock_lotes_ing
                SET cantidad_total = :cantidad_total
                WHERE id = :id
            ");
            $stmt->execute([
                'cantidad_total' => $lote->getCantidadTotal(),
                'id' => $lote->getId()
            ]);
            return $stmt->rowCount();
        }
        return 0;
    }
    


    public static function descontarStock(array $lotes, float $cantidadDescontar): array
    {
        // Ordenar los lotes por fecha de elaboración (más antigua primero)
        usort($lotes, function ($a, $b) {
            return strtotime($a->getElaboracion()) <=> strtotime($b->getElaboracion());
        });

        foreach ($lotes as $lote) {
            $cantidadTotal = $lote->getCantidadTotal();

            // Si el stock para descontar es mayor o igual al stock del lote actual
            if ($cantidadDescontar >= $cantidadTotal) {
                $cantidadDescontar -= $cantidadTotal; // Resta todo el stock del lote
                $lote->setCantidadTotal(0); // Deja el lote con cantidad total en 0

                // Actualiza el lote en la base de datos
                self::updateLote($lote);
            } else {
                // Si el stock para descontar es menor que el stock del lote actual
                $lote->setCantidadTotal($cantidadTotal - $cantidadDescontar);
                $cantidadDescontar = 0; // Todo el stock para descontar ya se ha procesado

                // Actualiza el lote en la base de datos
                self::updateLote($lote);
                break; // Salir del bucle
            }
        }

        // Devolver los lotes actualizados
        return $lotes;
    }
}
