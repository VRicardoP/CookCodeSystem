
<?php

//declare(strict_types=1);
require_once __DIR__ . '/ingredientesAlergenos.php';
require_once __DIR__ . '/../DBConnection.php';
require_once __DIR__ . '/../IDbAccess.php';


class IngredientesAlergenosDao implements IDbAccess
{
    public static function getAll(): ?array
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT * FROM ingredientesalergenos");
            $stmt->setFetchMode(
                PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,
                'ingredientesAlergenos'
            );
            $stmt->execute();
            return $stmt->fetchAll();
        } else {
            return null;
        }
    }

    public static function select($id): ?ingredientesAlergenos
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT * FROM ingredientesalergenos WHERE id = :id");
            $stmt->setFetchMode(
                PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,
                'ingredientesAlergenos'
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
            $stmt = $conn->prepare("INSERT INTO ingredientesalergenos ( id_ingrediente, id_alergeno)
   VALUES ( :id_ingrediente, :id_alergeno)");
            $stmt->execute([
             
                'id_ingrediente' => $object->getId_ingrediente(),
                'id_alergeno' => $object->getId_alergeno(),
               
            ]);
            return $stmt->rowCount();
        }
        return 0;
    }

    public static function delete($object): int
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("DELETE FROM ingredientesalergenos WHERE id=:id");
            $stmt->execute(['id' => $object->getID()]);
            return $stmt->rowCount();
        }
        return 0;
    }

    public static function update($object): int
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("UPDATE ingredientesalergenos SET id_ingrediente=:id_ingrediente, id_alergeno=:id_alergeno WHERE id=:id");
            $stmt->execute([
                'id' => $object->getId(),
                'id_ingrediente' => $object->getId_ingrediente(),
                'id_alergeno' => $object->getId_alergeno(),
            ]);
            return $stmt->rowCount();
        }
        return 0;
    }

    public static function selectByIngredienteYAlergeno($id_ingrediente, $id_alergeno): ?ingredientesAlergenos
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT * FROM ingredientesalergenos WHERE id_ingrediente = :id_ingrediente AND id_alergeno = :id_alergeno");
            $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'ingredientesAlergenos');
            $stmt->execute([
                'id_ingrediente' => $id_ingrediente,
                'id_alergeno' => $id_alergeno
            ]);
            $result = $stmt->fetch();
            return $result ?: null;
        }
        return null;
    }

    public static function selectByIngrediente($id_ingrediente): ?ingredientesAlergenos
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT * FROM ingredientesalergenos WHERE id_ingrediente = :id_ingrediente ");
            $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'ingredientesAlergenos');
            $stmt->execute([
                'id_ingrediente' => $id_ingrediente,
               
            ]);
            $result = $stmt->fetch();
            return $result ?: null;
        }
        return null;
    }
    
}
