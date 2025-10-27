<?php

class DBConnection
{
    //Database connection data
    private static $servername = "localhost";
    private static $dbname = "kitchentag";
    private static $username = "root";
    private static $password = "";



    public static function connectDB(?string $dbName = null): ?PDO
    {
        $servername = self::$servername;
        $username = self::$username;
        $password = self::$password;
        $database = $dbName ?? self::$dbname;  // Usar $dbName aquí

        try {
            $conn = new PDO(
                "mysql:host=$servername;dbname=$database;charset=utf8mb4",
                $username,
                $password,
                array(PDO::ATTR_PERSISTENT => true)
            );
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
            return null;
        }
    }
}
