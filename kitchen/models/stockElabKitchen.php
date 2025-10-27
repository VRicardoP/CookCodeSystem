<?php

class StockElabKitchen {
    private int $id;
  
    private int $receta_id;
    private float $stock;
   
   

    // Constructor
    public function __construct(
        int $id = 0,
       
        int $receta_id = 0,
        float $stock = 0.0
    ) {
        $this->id = $id;
       
        $this->receta_id = $receta_id;
        $this->stock = $stock;
    }

    // Getters and Setters

    public function getId(): int {
        return $this->id;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

  
   

    public function getRecetaId(): int {
        return $this->receta_id;
    }

    public function setRecetaId(int $receta_id): void {
        $this->receta_id = $receta_id;
    }

    public function getStock(): float {
        return $this->stock;
    }
    public function setStock(float $stock): void {
        $this->stock = $stock;
    }

   
}
?>
