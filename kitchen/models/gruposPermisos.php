<?php

class GrupoPermiso {
    private int $id;
    private int $grupo_id;
    private int $permiso_id;


    public function __construct(
        int $id = 0,
        int $grupo_id = 0,
        int $permiso_id = 0
    ) {
        $this->id = $id;
        $this->grupo_id = $grupo_id;
        $this->permiso_id = $permiso_id;
    }


    public function getId() {
        return $this->id;
    }

    public function setId(int $id) {
        $this->id = $id;
    }

    public function getGrupoId() {
        return $this->grupo_id;
    }

    public function setGrupoId(int $grupo_id) {
        $this->grupo_id = $grupo_id;
    }

    public function getPermisoId() {
        return $this->permiso_id;
    }

    public function setPermisoId(int $permiso_id) {
        $this->permiso_id = $permiso_id;
    }
}
?>
