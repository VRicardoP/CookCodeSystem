<?php

class ingredientesAlergenos {
    private int $id;
    private int $id_ingrediente;
    private int $id_alergeno;
    

    // Constructor
    public function __construct(
      
        int $id = 0,
        int $id_ingrediente = 0,
        int $id_alergeno = 0
    ) {
        $this->id = $id;
        $this->id_ingrediente = $id_ingrediente;
        $this->id_alergeno = $id_alergeno;
    }

   

    // Métodos Getter
  
  
    function getId(): int {
        return $this->id;
    }
    function getId_ingrediente(): int {
        return $this->id_ingrediente;
    }
    function getId_alergeno(): int {
        return $this->id_alergeno;
    }
   

    // Métodos Setter
   
    function setId(int $id): void {
        $this->id = $id;
    }
    function setId_ingrediente(int $id_ingrediente): void {
        $this->id_ingrediente = $id_ingrediente;
    }
    function setId_alergeno(int $id_alergeno): void {
        $this->id_alergeno = $id_alergeno;
    }
  
}

?>
