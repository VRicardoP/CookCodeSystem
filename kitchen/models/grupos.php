<?php

class Grupo {
    private int $id;
    private string $nombre;



// Constructor
public function __construct(
    int $id = 0,
    
   
    string $nombre = "",
   
   
) {
    $this->id = $id;
    $this->nombre = $nombre;
  
   
}



    public function getId() {
        return $this->id;
    }

    public function setId(int $id) {
        $this->id = $id;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function setNombre(string $nombre) {
        $this->nombre = $nombre;
    }
}
?>
