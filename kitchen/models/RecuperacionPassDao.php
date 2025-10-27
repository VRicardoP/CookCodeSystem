<?php

declare(strict_types=1);
require_once __DIR__ . '/recuperacionPass.php';
require_once __DIR__ . '/../DBConnection.php';
require_once __DIR__ . '/../IDbAccess.php';


class RecuperacionPassDao implements IDbAccess
{
    public static function getAll(): ?array
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT * FROM recuperacion_password");
            $stmt->setFetchMode(
                PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,
                'RecuperacionPass'
            );
            $stmt->execute();
            return $stmt->fetchAll();
        } else {
            return null;
        }
    }

    
    public static function select($id): ?RecuperacionPass
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT * FROM recuperacion_password WHERE id = :id");
            $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'RecuperacionPass');
            $stmt->execute(['id' => $id]);
            $recuperacionPass = $stmt->fetch();
            if ($recuperacionPass)
                return $recuperacionPass;
        }
        return null;
    }

    public static function insert($object): int
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("INSERT INTO recuperacion_password (email, token) VALUES (:email, :token)");
            $stmt->execute([
                'email' => $object->getEmail(),
                'token' => $object->getToken()
            ]);
            return $stmt->rowCount(); //Return the number of rows affected
        }
        return 0;
    }

    public static function delete($object): int
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("DELETE FROM recuperacion_password WHERE id = :id");
            $stmt->execute(['id' => $object->getId()]);
            return $stmt->rowCount(); //Return the number of rows affected
        }
        return 0;
    }

    public static function update($object): int
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("UPDATE recuperacion_password SET email = :email, token = :token WHERE id = :id");
            $stmt->execute([
                'email' => $object->getEmail(),
                'token' => $object->getToken(),
                'id' => $object->getId()
            ]);
            return $stmt->rowCount(); //Return the number of rows affected
        }
        return 0;
    }


    public static function tokenExists(string $token): bool
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT COUNT(*) FROM recuperacion_password WHERE token = :token");
            $stmt->execute(['token' => $token]);
            $count = $stmt->fetchColumn();
            return $count > 0;
        }
        return false;
    }
    
    public static function getEmailByToken(string $token): ?string
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT email FROM recuperacion_password WHERE token = :token");
            $stmt->execute(['token' => $token]);
            $email = $stmt->fetchColumn();
            return $email !== false ? $email : null;
        }
        return null;
    }
    

}

?>
