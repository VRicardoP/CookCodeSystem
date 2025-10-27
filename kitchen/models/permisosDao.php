<?php

declare(strict_types=1);

require_once __DIR__ . '/permisos.php';
require_once __DIR__ . '/../DBConnection.php';
require_once __DIR__ . '/../IDbAccess.php';

class PermisoDao implements IDbAccess
{
    public static function getAll(): ?array
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT * FROM permisos");
            $stmt->setFetchMode(
                PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,
                'Permiso'
            );
            $stmt->execute();
            return $stmt->fetchAll();
        } else {
            return null;
        }
    }

    public static function select($id): ?Permiso
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT * FROM permisos WHERE id = :id");
            $stmt->setFetchMode(
                PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,
                'Permiso'
            );
            $stmt->execute(['id' => $id]);
            $permiso = $stmt->fetch();
            if ($permiso) {
                return $permiso;
            }
        }
        return null;
    }

    public static function insert($object): int
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("INSERT INTO permisos (nombre, shopWeb, systemTag, dashboardProd, restaurant, dashboardGen, shopBackoffice) VALUES (:nombre, :shopWeb, :systemTag, :dashboardProd, :restaurant, :dashboardGen, :shopBackoffice)");
            $stmt->execute([
                'nombre' => $object->getNombre(),
                'shopWeb' => $object->getShopWeb(),
                'systemTag' => $object->getSystemTag(),
                'dashboardProd' => $object->getDashboardProd(),
                'restaurant' => $object->getRestaurant(),
                'dashboardGen' => $object->getDashboardGen(),
                'shopBackoffice' => $object->getShopBackoffice()
            ]);
            return $stmt->rowCount(); //Return the number of rows affected
        }
        return 0;
    }
    

    public static function delete($object): int
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("DELETE FROM permisos WHERE id=:id");
            $stmt->execute(['id' => $object->getId()]);
            return $stmt->rowCount(); //Return the number of rows affected
        }
        return 0;
    }

    public static function update($object): int
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("UPDATE permisos SET nombre=:nombre, shopWeb=:shopWeb, systemTag=:systemTag, dashboardProd=:dashboardProd, restaurant=:restaurant, dashboardGen=:dashboardGen, shopBackoffice=:shopBackoffice WHERE id=:id");
            $stmt->execute([
                'id' => $object->getId(),
                'nombre' => $object->getNombre(),
                'shopWeb' => $object->getShopWeb(),
                'systemTag' => $object->getSystemTag(),
                'dashboardProd' => $object->getDashboardProd(),
                'restaurant' => $object->getRestaurant(),
                'dashboardGen' => $object->getDashboardGen(),
                'shopBackoffice' => $object->getShopBackoffice()
            ]);
            return $stmt->rowCount(); //Return the number of rows affected
        }
        return 0;
    }



    public static function getPermisoNombreById(int $id): ?string
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT nombre FROM permisos WHERE id = :id");
            $stmt->execute(['id' => $id]);
            $nombrePermiso = $stmt->fetchColumn();
            if ($nombrePermiso) {
                return $nombrePermiso;
            }
        }
        return null;
    }


}
?>
