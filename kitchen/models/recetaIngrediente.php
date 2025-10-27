<?php

class RecetaIngrediente {
    public ?int $id;
    public int $receta;
    public int $ingrediente;
    public float $cantidad;
   


    // Constructor
    public function __construct(
        ?int $id = 0,
        int $receta = 0,
        int $ingrediente = 0,
        float $cantidad = 0,
        
    ) {
        $this->id = $id;
        $this->receta = $receta;
        $this->ingrediente = $ingrediente;
        $this->cantidad = $cantidad;
     
    }

    // Métodos Getter
    public function getID(): int {
        return $this->id;
    }
    public function getRecetaId(): int{
        return $this->receta;
    }
    public function getIngredienteId(): int {
        return $this->ingrediente;
    }
    public function getCantidad(): float {
        return $this->cantidad;
    }
   

    // Métodos Setter
    public function setID(int $id): void {
        $this->id = $id;
    }
    public function setRecetaId(int $receta): void {
        $this->receta = $receta;
    }
    public function setIngredienteId(int $ingrediente): void {
        $this->ingrediente = $ingrediente;
    }
    public function setCantidad(?float $cantidad): void {
        $this->cantidad = $cantidad;
    }
   
}
?>
