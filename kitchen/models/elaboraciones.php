<?php

class Elaboraciones {
    private int $ID;
    private string $fName;
    private int $receta;
    private float $merma;

    private string $packaging;
    private int $productamount;
    private string $fechaElab;
    private string $caducidad;
    private string $warehouse;
    private string $costCurrency;
    private int $costPrice;
    private string $saleCurrency;
    private int $salePrice;
    private string $codeContents;
    private ?string $image;

    // Constructor
    public function __construct(
        int $ID = 0,
        string $fName = "",
        int $receta = 0,
        float $merma = 0,
        string $packaging = "",
        int $productamount = 0,
        string $fechaElab = "",
        string $caducidad = "",
        string $warehouse = "",
        string $costCurrency = "",
        int $costPrice = 0,
        string $saleCurrency = "",
        int $salePrice = 0,
        string $codeContents = "",
        ?string $image = null
    ) {
        $this->ID = $ID;
        $this->fName = $fName;
        $this->receta = $receta;
        $this->merma = $merma;

        $this->packaging = $packaging;
        $this->productamount = $productamount;
        $this->fechaElab = $fechaElab;
        $this->caducidad = $caducidad;
        $this->warehouse = $warehouse;
        $this->costCurrency = $costCurrency;
        $this->costPrice = $costPrice;
        $this->saleCurrency = $saleCurrency;
        $this->salePrice = $salePrice;
        $this->codeContents = $codeContents;
        $this->image = $image;
    }


    // Métodos Getter
    public function getID(): int {
        return $this->ID;
    }
    public function getFName(): string {
        return $this->fName;
    }
    public function getReceta(): int {
        return $this->receta;
    }
    public function getMerma(): float {
        return $this->merma;
    }



    public function getPackaging(): string {
        return $this->packaging;
    }
    public function getProductamount(): int {
        return $this->productamount;
    }
    public function getfechaElab(): string {
        return $this->fechaElab;
    }
    public function getCaducidad(): string {
        return $this->caducidad;
    }
    public function getWarehouse(): string {
        return $this->warehouse;
    }
    public function getCostCurrency(): string {
        return $this->costCurrency;
    }
    public function getCostPrice(): int {
        return $this->costPrice;
    }
    public function getSaleCurrency(): string {
        return $this->saleCurrency;
    }
    public function getSalePrice(): int {
        return $this->salePrice;
    }
    public function getCodeContents(): string {
        return $this->codeContents;
    }
    public function getImage(): ?string {
        return $this->image;
    }


    // Métodos Setter
    public function setID(int $ID): void {
        $this->ID = $ID;
    }
    public function setFName(string $fName): void {
        $this->fName = $fName;
    }
    public function setReceta(int $receta): void {
        $this->receta = $receta;
    }
    public function setMerma(float $merma): void {
        $this->merma = $merma;
    }



    public function setPackaging(string $packaging): void {
        $this->packaging = $packaging;
    }
    public function setProductamount(int $productamount): void {
        $this->productamount = $productamount;
    }
    public function setfechaElab(string $fechaElab): void {
        $this->fechaElab = $fechaElab;
    }
    public function setCaducidad(string $caducidad): void {
        $this->caducidad = $caducidad;
    }
    public function setWarehouse(string $warehouse): void {
        $this->warehouse = $warehouse;
    }
    public function setCostCurrency(string $costCurrency): void {
        $this->costCurrency = $costCurrency;
    }
    public function setCostPrice(int $costPrice): void {
        $this->costPrice = $costPrice;
    }
    public function setSaleCurrency(string $saleCurrency): void {
        $this->saleCurrency = $saleCurrency;
    }
    public function setSalePrice(int $salePrice): void {
        $this->salePrice = $salePrice;
    }
    public function setCodeContents(string $codeContents): void {
        $this->codeContents = $codeContents;
    }

    public function setImage(?string $image): void {
        $this->image = $image;
    }
}
?>
