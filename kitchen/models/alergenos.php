<?php

class Alergeno {
    private int $id;
    private string $nombre_ingles;

    // Constructor
    public function __construct(int $id = 0, string $nombre = "") {
        $this->id = $id;
        $this->nombre_ingles = $nombre;
    }

    // Método Getter

    public function getId(): string {
        return $this->id;
    }

    public function getNombre(): string {
        return $this->nombre_ingles;
    }

    // Método Setter
    public function setNombre(string $nombre): void {
        $this->nombre_ingles = $nombre;
    }
}

?>
