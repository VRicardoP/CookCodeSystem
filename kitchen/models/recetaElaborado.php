<?php

class RecetaElaborado {
    public ?int $id;
    public int $receta;
    public int $elaborado;
    public float $cantidad;
   


    // Constructor
    public function __construct(
        ?int $id = 0,
        int $receta = 0,
        int $elaborado = 0,
        float $cantidad = 0,
        
    ) {
        $this->id = $id;
        $this->receta = $receta;
        $this->elaborado = $elaborado;
        $this->cantidad = $cantidad;
     
    }

    // Métodos Getter
    public function getID(): int {
        return $this->id;
    }
    public function getRecetaId(): int{
        return $this->receta;
    }
    public function getElaboradoId(): int {
        return $this->elaborado;
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
    public function setElaboradoId(int $elaborado): void {
        $this->elaborado = $elaborado;
    }
    public function setCantidad(?float $cantidad): void {
        $this->cantidad = $cantidad;
    }
   
}
?>
