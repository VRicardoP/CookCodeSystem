<?php

declare(strict_types=1);
require_once __DIR__ . '/user.php';
require_once __DIR__ . '/../DBConnection.php';
require_once __DIR__ . '/../IDbAccess.php';


class UserDao implements IDbAccess
{
    public static function getAll(): ?array
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT * FROM users");
            $stmt->setFetchMode(
                PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,
                'User'
            );
            $stmt->execute();
            return $stmt->fetchAll();
        } else {
            return null;
        }
    }


    public static function select($id): ?User
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            // The user input is automatically quoted, so there is no risk of a SQL injection attack.
            $stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
            $stmt->setFetchMode(
                PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,
                'User'
            );
            $stmt->execute(['id' => $id]);
            $user = $stmt->fetch();
            if ($user)
                return $user;
        }
        return null;
    }

    public static function insert($object): int
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("INSERT INTO users (id, email, password, grupo_id, name, phone, image, surname, address, city, cp, country, province)
                VALUES (:id, :email, :password, :grupo_id, :name, :phone, :image, :surname, :address, :city, :cp, :country, :province)");
            $stmt->execute([
                'id' => null,
                'email' => $object->getEmail(),
                'password' => $object->getPassword(),
                'grupo_id' => $object->getGrupo_id(),
                'name' => $object->getName(),
                'phone' => $object->getPhone(),
                'image' => $object->getImage(),
                'surname' => $object->getSurname(),
                'address' => $object->getAddress(),
                'city' => $object->getCity(),
                'cp' => $object->getCp(),
                'country' => $object->getCountry(),
                'province' => $object->getProvince(),
            ]);
            return $stmt->rowCount(); //Return the number of rows affected
        }
        return 0;
    }
    

    public static function delete($object): int
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("DELETE FROM users WHERE id=:id");
            $stmt->execute(['id' => $object->getId()]);
            return $stmt->rowCount(); //Return the number of rows affected
        }
        return 0;
    }

    public static function update($object): int
{
    $conn = DBConnection::connectDB();
    if (!is_null($conn)) {
        $stmt = $conn->prepare("UPDATE users SET email=:email, password=:password, grupo_id=:grupo_id, phone=:phone, image=:image, surname=:surname, address=:address, city=:city, cp=:cp, country=:country, province=:province WHERE id=:id");
        $stmt->execute([
            'id' => $object->getId(),
            'email' => $object->getEmail(),
            'password' => $object->getPassword(),
            'grupo_id' => $object->getGrupo_id(),
            'phone' => $object->getPhone(),
            'image' => $object->getImage(),
            'surname' => $object->getSurname(),
            'address' => $object->getAddress(),
            'city' => $object->getCity(),
            'cp' => $object->getCp(),
            'country' => $object->getCountry(),
            'province' => $object->getProvince(),
        ]);
        return $stmt->rowCount(); //Return the number of rows affected
    }
    return 0;
}
    


    public static function searchByEmail($object): int
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT email FROM users WHERE email=:email");
            $stmt->execute(['email' => $object->getEmail()]);
            return $stmt->rowCount();
        }
        return 0;
    }


    public static function checkCredential($clearPassword, $object): bool|int
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT * FROM users WHERE email=:email");
            $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'User');
            $stmt->execute(['email' => $object->getEmail()]);
            $user = $stmt->fetch();
            if ($user && password_verify($clearPassword, $user->getPassword())) {
                //Returns the id user if successful
                return $user->getId();
            } else {
                return false;
            }
        }
        return false;
    }


    public static function getByEmail(string $email): ?User
{
    $conn = DBConnection::connectDB();
    if (!is_null($conn)) {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'User');
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();
        if ($user) {
            return $user;
        }
    }
    return null;
}

}
