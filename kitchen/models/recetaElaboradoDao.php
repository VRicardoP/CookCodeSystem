<?php

declare(strict_types=1);
require_once __DIR__ . '/recetaElaborado.php';
require_once __DIR__ . '/../DBConnection.php';
require_once __DIR__ . '/../IDbAccess.php';

class RecetaElaboradoDao implements IDbAccess
{
    // Obtener todos los registros
    public static function getAll()
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT * FROM receta_elaborado");
            $stmt->setFetchMode(
                PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,
                'RecetaElaborado'
            );
            $stmt->execute();
            return $stmt->fetchAll(); // Devuelve un array de objetos RecetaElaborado
        }
        return null; // Retornamos null si falla la conexión
    }

    // Seleccionar un registro por ID
    public static function select($id)
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT * FROM receta_elaborado WHERE id = :id");
            $stmt->setFetchMode(
                PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,
                'RecetaElaborado'
            );
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(); // Retorna un objeto RecetaElaborado o false si no encuentra
        }
        return null; // Retornamos null si falla la conexión
    }

    // Insertar un nuevo registro
    public static function insert($object)
    {
        if (!$object instanceof RecetaElaborado) {
            throw new InvalidArgumentException('Se esperaba una instancia de RecetaElaborado');
        }

        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("INSERT INTO receta_elaborado (receta, elaborado, cantidad) 
                                    VALUES (:receta, :elaborado, :cantidad)");
            $stmt->execute([
                'receta' => $object->getRecetaId(),
                'elaborado' => $object->getElaboradoId(),
                'cantidad' => $object->getCantidad(),
            ]);
            return $stmt->rowCount(); // Retornamos el número de filas afectadas
        }
        return 0; // Retornamos 0 si la inserción falla
    }

    // Eliminar un registro
    public static function delete($object)
    {
        if (!$object instanceof RecetaElaborado) {
            throw new InvalidArgumentException('Se esperaba una instancia de RecetaElaborado');
        }

        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("DELETE FROM receta_elaborado WHERE id = :id");
            $stmt->execute(['id' => $object->getID()]);
            return $stmt->rowCount(); // Retornamos el número de filas afectadas
        }
        return 0; // Retornamos 0 si la eliminación falla
    }

    // Actualizar un registro
    public static function update($object)
    {
        if (!$object instanceof RecetaElaborado) {
            throw new InvalidArgumentException('Se esperaba una instancia de RecetaElaborado');
        }

        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("UPDATE receta_elaborado 
                                    SET receta = :receta, elaborado = :elaborado, cantidad = :cantidad 
                                    WHERE id = :id");
            $stmt->execute([
                'receta' => $object->getRecetaId(),
                'elaborado' => $object->getElaboradoId(),
                'cantidad' => $object->getCantidad(),
                'id' => $object->getID(),
            ]);
            return $stmt->rowCount(); // Retornamos el número de filas afectadas
        }
        return 0; // Retornamos 0 si la actualización falla
    }

    // Obtener elaborados asociados a una receta por receta ID
    public static function getElaboradosByRecetaId($recetaId)
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT elaborado, cantidad FROM receta_elaborado WHERE receta = :recetaId");
            $stmt->execute(['recetaId' => $recetaId]);

            // Devuelve un array asociativo con el id y la cantidad
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return []; // Retorna un array vacío si falla la conexión o no hay resultados
    }
}
