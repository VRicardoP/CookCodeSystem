<?php

declare(strict_types=1);

require_once __DIR__ . '/gruposPermisos.php';
require_once __DIR__ . '/../DBConnection.php';
require_once __DIR__ . '/../IDbAccess.php';

class GrupoPermisoDao implements IDbAccess
{
    public static function getAll(): ?array
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT * FROM grupos_permisos");
            $stmt->setFetchMode(
                PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,
                'GrupoPermiso'
            );
            $stmt->execute();
            return $stmt->fetchAll();
        } else {
            return null;
        }
    }

    public static function select($id): ?GrupoPermiso
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT * FROM grupos_permisos WHERE id = :id");
            $stmt->setFetchMode(
                PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,
                'GrupoPermiso'
            );
            $stmt->execute(['id' => $id]);
            $grupoPermiso = $stmt->fetch();
            if ($grupoPermiso) {
                return $grupoPermiso;
            }
        }
        return null;
    }

    public static function insert($object): int
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("INSERT INTO grupos_permisos (id, grupo_id, permiso_id) VALUES (:id, :grupo_id, :permiso_id)");
            $stmt->execute([
                'id' => null,
                'grupo_id' => $object->getGrupoId(),
                'permiso_id' => $object->getPermisoId()
            ]);
            return $stmt->rowCount(); //Return the number of rows affected
        }
        return 0;
    }

    public static function delete($object): int
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("DELETE FROM grupos_permisos WHERE id=:id");
            $stmt->execute(['id' => $object->getId()]);
            return $stmt->rowCount(); //Return the number of rows affected
        }
        return 0;
    }

    public static function update($object): int
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("UPDATE grupos_permisos SET grupo_id=:grupo_id, permiso_id=:permiso_id WHERE id=:id");
            $stmt->execute([
                'id' => $object->getId(),
                'grupo_id' => $object->getGrupoId(),
                'permiso_id' => $object->getPermisoId()
            ]);
            return $stmt->rowCount(); //Return the number of rows affected
        }
        return 0;
    }



    public static function getByGroupId(int $grupo_id): ?array
{
    $conn = DBConnection::connectDB();
    if (!is_null($conn)) {
        $stmt = $conn->prepare("SELECT * FROM grupos_permisos WHERE grupo_id = :grupo_id");
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'GrupoPermiso');
        $stmt->execute(['grupo_id' => $grupo_id]);
        return $stmt->fetchAll();
    }
    return null;
}


public static function deleteByGroupId(int $grupo_id): int
{
    $conn = DBConnection::connectDB();
    if (!is_null($conn)) {
        $stmt = $conn->prepare("DELETE FROM grupos_permisos WHERE grupo_id=:grupo_id");
        $stmt->execute(['grupo_id' => $grupo_id]);
        return $stmt->rowCount(); //Return the number of rows affected
    }
    return 0;
}

public static function getPermisosByGroupId(int $grupo_id): array
{
    $conn = DBConnection::connectDB();
    $idPermisos = [];

    if (!is_null($conn)) {
        $stmt = $conn->prepare("SELECT permiso_id FROM grupos_permisos WHERE grupo_id = :grupo_id");
        $stmt->execute(['grupo_id' => $grupo_id]);
        $permisos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($permisos) {
            foreach ($permisos as $permiso) {
                $idPermisos[] = $permiso['permiso_id'];
            }
        }
    }
    return $idPermisos;
}

}
?>
