<?php

declare(strict_types=1);
require_once __DIR__ . '/stockLotesElab.php';
require_once __DIR__ . '/../DBConnection.php';
require_once __DIR__ . '/../IDbAccess.php';

class StockLotesElabDao implements IDbAccess
{
    public static function getAll(): ?array
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT * FROM stock_lotes_elab");
            $stmt->setFetchMode(
                PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,
                'StockLotesElab'
            );
            $stmt->execute();
            return $stmt->fetchAll();
        } else {
            return null;
        }
    }

    public static function select($id): ?StockLotesElab
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT * FROM stock_lotes_elab WHERE id = :id");
            $stmt->setFetchMode(
                PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,
                'StockLotesElab'
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
                INSERT INTO stock_lotes_elab (receta_id, lote, cantidad, unidades, elaboracion, caducidad, coste, tipo_unidad, cantidad_total)
                VALUES (:receta_id, :lote, :cantidad, :unidades, :elaboracion, :caducidad, :coste, :tipo_unidad, :cantidad_total)
            ");
            $stmt->execute([
                'receta_id' => $object->getRecetaId(),
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
            $stmt = $conn->prepare("DELETE FROM stock_lotes_elab WHERE id = :id");
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
                UPDATE stock_lotes_elab 
                SET receta_id = :receta_id, lote = :lote, cantidad = :cantidad, unidades = :unidades, 
                    elaboracion = :elaboracion, caducidad = :caducidad, coste = :coste, 
                    tipo_unidad = :tipo_unidad, cantidad_total = :cantidad_total
                WHERE id = :id
            ");
            $stmt->execute([
                'id' => $object->getId(),
                'receta_id' => $object->getRecetaId(),
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

    public static function selectByRecetaId($receta_id): ?array
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT * FROM stock_lotes_elab WHERE receta_id = :receta_id AND caducidad > CURDATE();");
            $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'StockLotesElab');
            $stmt->execute(['receta_id' => $receta_id]);
            $results = $stmt->fetchAll();
            
            // Depuración: Verifica si hay resultados
            if (!$results) {
                echo "No se encontraron resultados para el ingrediente: $receta_id\no el producto está caducado";
                exit;
            }
            
            return $results; 
        }
    
        return null; // Devuelve null si no hay resultados
    }
    


    public static function updateLote(StockLotesElab $lote): int
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("
                UPDATE stock_lotes_elab
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
