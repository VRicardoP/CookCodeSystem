<?php

class StockIngKitchen {
    private int $id;
  
    private int $ingredient_id;
    private float $stock;
    private ?float $stock_ecommerce;
   

    // Constructor
    public function __construct(
        int $id = 0,
       
        int $ingredient_id = 0,
        float $stock = 0.0
    ) {
        $this->id = $id;
       
        $this->ingredient_id = $ingredient_id;
        $this->stock = $stock;
    }

    // Getters and Setters

    public function getId(): int {
        return $this->id;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

  
   

    public function getIngredientId(): int {
        return $this->ingredient_id;
    }

    public function setIngredientId(int $ingredient_id): void {
        $this->ingredient_id = $ingredient_id;
    }

    public function getStock(): float {
        return $this->stock;
    }
    public function setStock(float $stock): void {
        $this->stock = $stock;
    }

    public function getStockEcommerce(): ?float {
        return $this->stock_ecommerce;
    }
    public function setStockEcommerce(?float $stock_ecommerce): void {
        $this->stock_ecommerce = $stock_ecommerce;
    }
}
?>
