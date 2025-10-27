<?php

declare(strict_types=1);
require_once __DIR__ . '/recetas.php';
require_once __DIR__ . '/../DBConnection.php';
require_once __DIR__ . '/../IDbAccess.php';

class RecetasDao implements IDbAccess
{
    public static function getAll(): ?array
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT * FROM recetas");
            $stmt->setFetchMode(
                PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,
                'Recetas'
            );
            $stmt->execute();
            return $stmt->fetchAll();
        } else {
            return null;
        }
    }

    public static function select($id): ?Recetas
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT * FROM recetas WHERE ID = :id");
            $stmt->setFetchMode(
                PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,
                'Recetas'
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
            $stmt = $conn->prepare("INSERT INTO recetas (ID, tipo, receta, instrucciones, produce, cantidad_producida, tipo_cantidad, num_raciones, imagen, peso, expira_dias, empaquetado, localizacion, descripcion_corta, categoria) 
            VALUES (null, :tipo, :receta, :instrucciones, :produce, :cantidad_producida, :tipo_cantidad, :num_raciones, :imagen, :peso, :expira_dias, :empaquetado, :localizacion, :descripcion_corta, :categoria)");

            $stmt->execute([
                'tipo' => $object->getTipo(), // Ahora está en su posición correcta
                'receta' => $object->getReceta(),
                'instrucciones' => $object->getInstrucciones(),
                'produce' => null,
                'cantidad_producida' => $object->getCantidadProducida(),
                'tipo_cantidad' => $object->getTipoCantidad(),
                'num_raciones' => $object->getNumRaciones(),
                'imagen' => $object->getImagen(),
                'peso' => $object->getPeso(),
                'expira_dias' => $object->getCaducidad(),
                'empaquetado' => $object->getEmpaquetado(),
                'localizacion' => $object->getLocalizacion(),
                'descripcion_corta' => $object->getDescripcionCorta(),
                'categoria' => $object->getCategoria(),
            ]);

            return $stmt->rowCount(); // Retorna el número de filas afectadas
        }
        return 0;
    }

    public static function delete($object): int
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("DELETE FROM recetas WHERE ID=:id");
            $stmt->execute(['id' => $object->getID()]);
            return $stmt->rowCount(); //Return the number of rows affected
        }
        return 0;
    }

    public static function update($object): int
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("UPDATE recetas SET receta=:receta, instrucciones=:instrucciones, produce=:produce, cantidad_producida=:cantidad_producida, tipo_cantidad=:tipo_cantidad, num_raciones=:num_raciones, imagen=:imagen, peso=:peso, expira_dias=:expira_dias, empaquetado=:empaquetado, localizacion=:localizacion, descripcion_corta=:descripcion_corta, categoria=:categoria  WHERE ID=:id");
            $stmt->execute([
                'receta' => $object->getReceta(),
                'instrucciones' => $object->getInstrucciones(),
                'produce' => $object->getProduce(),
                'cantidad_producida' => $object->getCantidadProducida(),
                'tipo_cantidad' => $object->getTipoCantidad(),
                'num_raciones' => $object->getNumRaciones(),
                'imagen' => $object->getImagen(),
                'peso' => $object->getPeso(),
                'expira_dias' => $object->getCaducidad(),
                'empaquetado' => $object->getEmpaquetado(),
                'localizacion' => $object->getLocalizacion(),
                'descripcion_corta' => $object->getDescripcionCorta(),
                'id' => $object->getID(),
                'categoria' => $object->getCategoria(),
            ]);
            return $stmt->rowCount(); //Return the number of rows affected
        }
        return 0;
    }

    public static function selectByName(string $recipeName): ?array
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT * FROM recetas WHERE receta LIKE :receta");
            $stmt->setFetchMode(
                PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,
                'Recetas'
            );
            $stmt->execute(['receta' =>  $recipeName ]);
            return $stmt->fetchAll();
        } else {
            return null;
        }
    }


    public static function selectNameById(int $idReceta): ?string
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT receta FROM recetas WHERE ID = :id");
            $stmt->execute(['id' => $idReceta]);
            $nombreReceta = $stmt->fetchColumn();
            if ($nombreReceta) {
                return $nombreReceta;
            }
        }
        return null;
    }

    public static function getElaborados(): ?array
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT * FROM recetas WHERE tipo = 'Elaborado'");
            $stmt->setFetchMode(
                PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,
                'Recetas'
            );
            $stmt->execute();
            return $stmt->fetchAll();
        } else {
            return null;
        }
    }

    public static function getPreelaborados(): ?array
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT * FROM recetas WHERE tipo = 'Pre-Elaborado'");
            $stmt->setFetchMode(
                PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,
                'Recetas'
            );
            $stmt->execute();
            return $stmt->fetchAll();
        } else {
            return null;
        }
    }
}
