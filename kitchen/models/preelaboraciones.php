<?php

class Preelaboracion {
    private int $ID;
    private string $fName;
    private string $packaging;
    private int $productamount;
    private string $cad;
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
        string $packaging = "",
        int $productamount = 0,
        string $cad = "",
        string $warehouse = "",
        string $costCurrency = "",
        int $costPrice = 0,
        string $saleCurrency = "",
        int $salePrice = 0,
        string $codeContents = "",
        string $image =  null
    ) {
        $this->ID = $ID;
        $this->fName = $fName;
        $this->packaging = $packaging;
        $this->productamount = $productamount;
        $this->cad = $cad;
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
    public function getPackaging(): string {
        return $this->packaging;
    }
    public function getProductamount(): int {
        return $this->productamount;
    }
    public function getCad(): string {
        return $this->cad;
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
    public function setPackaging(string $packaging): void {
        $this->packaging = $packaging;
    }
    public function setProductamount(int $productamount): void {
        $this->productamount = $productamount;
    }
    public function setCad(string $cad): void {
        $this->cad = $cad;
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
