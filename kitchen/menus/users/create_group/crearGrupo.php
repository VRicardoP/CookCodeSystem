<?php

require __DIR__ . '/../models/grupos.php';
require_once __DIR__ . '/../models/gruposDao.php';
require __DIR__ . '/../models/gruposPermisos.php';
require_once __DIR__ . '/../models/gruposPermisosDao.php';
require __DIR__ . '/../models/permisos.php';
require_once __DIR__ . '/../models/permisosDao.php';
require_once __DIR__ . '/../DBConnection.php';

/**
 * Elimina todos los permisos asociados a un grupo usando PDO
 */
function deletePermisosGrupo($id)
{
    try {
        $conn = DBConnection::connectDB();
        if (!$conn) {
            throw new Exception("No se pudo establecer la conexión con la base de datos.");
        }

        $stmt = $conn->prepare("DELETE FROM grupos_permisos WHERE grupo_id = ?");
        $stmt->execute([$id]);

    } catch (PDOException $e) {
        error_log("Error al eliminar permisos del grupo: " . $e->getMessage());
        echo "Error al eliminar permisos: " . $e->getMessage();
        exit;
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $ID = $_POST['id'] ?? 0;
    $name = $_POST['name'] ?? '';
    $permisos = $_POST['permisos'] ?? [];

    if ($ID > 0) {
        $grupo = new Grupo($ID, $name);
        GruposDao::update($grupo);
        deletePermisosGrupo($ID);

        foreach ($permisos as $permiso) {
            $grupoPermiso = new GrupoPermiso(0, $ID, $permiso['value']);
            GrupoPermisoDao::insert($grupoPermiso);
        }

    } else {
        $grupo = new Grupo(0, $name);
        GruposDao::insert($grupo);

        $lastId = GruposDao::getLastInsertId();

        foreach ($permisos as $permiso) {
            $grupoPermiso = new GrupoPermiso(0, $lastId, $permiso['value']);
            GrupoPermisoDao::insert($grupoPermiso);
        }
    }
}
