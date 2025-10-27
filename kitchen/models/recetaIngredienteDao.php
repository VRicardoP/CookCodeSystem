<?php

declare(strict_types=1);
require_once __DIR__ . '/recetaIngrediente.php';
require_once __DIR__ . '/../DBConnection.php';
require_once __DIR__ . '/../IDbAccess.php';

class RecetaIngredienteDao implements IDbAccess
{
    // Obtener todos los registros
    public static function getAll() // No especificamos tipo de retorno
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT * FROM receta_ingrediente");
            $stmt->setFetchMode(
                PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,
                'RecetaIngrediente'
            );
            $stmt->execute();
            return $stmt->fetchAll();  // Devuelve un array de objetos
        } else {
            return null;  // Retornamos null si falla la conexión
        }
    }

    // Seleccionar un registro por ID
    public static function select($id) // Eliminamos el tipo de parámetro
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT * FROM receta_ingrediente WHERE id = :id");
            $stmt->setFetchMode(
                PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,
                'RecetaIngrediente'
            );
            $stmt->execute(['id' => $id]);
            $recetaIngrediente = $stmt->fetch();
            if ($recetaIngrediente) {
                return $recetaIngrediente;  // Retornamos el objeto RecetaIngrediente
            }
        }
        return null;  // Retornamos null si no se encuentra el registro
    }

    // Insertar un nuevo registro
    public static function insert($object) // Eliminamos el tipo de parámetro
    {
        if (!$object instanceof RecetaIngrediente) {
            throw new InvalidArgumentException('Se esperaba una instancia de RecetaIngrediente');
        }

        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("INSERT INTO receta_ingrediente (receta, ingrediente, cantidad) 
                                    VALUES (:receta, :ingrediente, :cantidad)");
            $stmt->execute([
                'receta' => $object->getRecetaId(),
                'ingrediente' => $object->getIngredienteId(),
                'cantidad' => $object->getCantidad(),
            ]);
            return $stmt->rowCount(); // Retornamos el número de filas afectadas
        }
        return 0;  // Retornamos 0 si la inserción falla
    }

    // Eliminar un registro
    public static function delete($object) // Eliminamos el tipo de parámetro
    {
        if (!$object instanceof RecetaIngrediente) {
            throw new InvalidArgumentException('Se esperaba una instancia de RecetaIngrediente');
        }

        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("DELETE FROM receta_ingrediente WHERE id=:id");
            $stmt->execute(['id' => $object->getID()]);
            return $stmt->rowCount(); // Retornamos el número de filas afectadas
        }
        return 0;  // Retornamos 0 si la eliminación falla
    }

    // Actualizar un registro
    public static function update($object) // Eliminamos el tipo de parámetro
    {
        if (!$object instanceof RecetaIngrediente) {
            throw new InvalidArgumentException('Se esperaba una instancia de RecetaIngrediente');
        }

        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("UPDATE receta_ingrediente 
                                    SET receta=:receta, ingrediente=:ingrediente, cantidad=:cantidad 
                                    WHERE id=:id");
            $stmt->execute([
                'receta' => $object->getRecetaId(),
                'ingrediente' => $object->getIngredienteId(),
                'cantidad' => $object->getCantidad(),
                'id' => $object->getID(),
            ]);
            return $stmt->rowCount(); // Retornamos el número de filas afectadas
        }
        return 0;  // Retornamos 0 si la actualización falla
    }

    public static function getIngredientesByRecetaId($recetaId): array
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT ingrediente, cantidad FROM receta_ingrediente WHERE receta = :recetaId");
            $stmt->execute(['recetaId' => $recetaId]);
    
            // Devuelve un array asociativo con el id del ingrediente y la cantidad
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return []; // Retorna un array vacío si falla la conexión o no hay resultados
    }
    

    public static function selectByRecetaAndIngrediente($recetaId, $ingredienteId): ?RecetaIngrediente
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare(
                "SELECT * FROM receta_ingrediente WHERE receta = :recetaId AND ingrediente = :ingredienteId"
            );
            $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'RecetaIngrediente');
            $stmt->execute([
                'recetaId' => $recetaId,
                'ingredienteId' => $ingredienteId,
            ]);
            $recetaIngrediente = $stmt->fetch();
            if ($recetaIngrediente) {
                return $recetaIngrediente;  // Retornamos el objeto RecetaIngrediente si se encuentra
            }
        }
        return null;  // Retornamos null si no se encuentra el registro
    }

  // Eliminar por recetaId
  public static function deleteByRecetaId($recetaId): int
  {
      $conn = DBConnection::connectDB();
      if (!is_null($conn)) {
          $stmt = $conn->prepare("DELETE FROM receta_ingrediente WHERE receta = :recetaId");
          $stmt->execute(['recetaId' => $recetaId]);
          return $stmt->rowCount();  // Retornamos el número de filas afectadas
      }
      return 0;  // Retornamos 0 si la eliminación falla
  }
}
