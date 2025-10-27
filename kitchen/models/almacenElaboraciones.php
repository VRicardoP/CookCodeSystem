<?php

class AlmacenElaboraciones {
    private int $ID;
    private string $tipoProd;
    private string $fName;
    private string $packaging;
    private int $productamount;
    private string $fechaElab;
    private string $fechaCad;
    private string $warehouse;
    private string $costCurrency;
    private float $costPrice;
    private string $saleCurrency;
    private float $salePrice;
    private string $codeContents;
    private int $receta_id; 
    private int $rations_package;
    private ?string $fileName;
    private ?string $estado;


  //  private string $imagen;
    // Constructor
    public function __construct(
        int $ID = 0,
        string $tipoProd = "",
        string $fName = "",
        string $packaging = "",
        int $productamount = 0,
        string $fechaElab = "",
        string $fechaCad = "",
        string $warehouse = "",
        string $costCurrency = "",
        float $costPrice = 0,
        string $saleCurrency = "",
        float $salePrice = 0,
        string $codeContents = "",
        int $receta_id = 0,
        int $rations_package = 0,
        string $fileName = '',
        string $estado = '',
    ) {
        $this->ID = $ID;
        $this->tipoProd = $tipoProd;
        $this->fName = $fName;
        $this->packaging = $packaging;
        $this->productamount = $productamount;
        $this->fechaElab = $fechaElab;
        $this->fechaCad = $fechaCad;
        $this->warehouse = $warehouse;
        $this->costCurrency = $costCurrency;
        $this->costPrice = $costPrice;
        $this->saleCurrency = $saleCurrency;
        $this->salePrice = $salePrice;
        $this->codeContents = $codeContents;
        $this->receta_id = $receta_id; 
        $this->rations_package = $rations_package; 
        $this->fileName = $fileName;
        $this->estado = $estado;
    }

    // Métodos Getter
    public function getID(): int {
        return $this->ID;
    }
    public function getTipoProd(): string {
        return $this->tipoProd;
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
    public function getFechaElab(): string {
        return $this->fechaElab;
    }
    public function getFechaCad(): string {
        return $this->fechaCad;
    }
    public function getWarehouse(): string {
        return $this->warehouse;
    }
    public function getCostCurrency(): string {
        return $this->costCurrency;
    }
    public function getCostPrice(): float {
        return $this->costPrice;
    }
    public function getSaleCurrency(): string {
        return $this->saleCurrency;
    }
    public function getSalePrice(): float {
        return $this->salePrice;
    }
    public function getCodeContents(): string {
        return $this->codeContents;
    }
    public function getRecetaId(): int {
        return $this->receta_id; 
    }
    public function getRationsPackage(): int {
        return $this->rations_package; 
    }
    public function getFileName(): ?string {
        return $this->fileName; 
    }
    public function getEstado(): string {
       return $this->estado; 
    }




    // Métodos Setter
    public function setID(int $ID): void {
        $this->ID = $ID;
    }
    public function setTipoProd(string $tipoProd): void {
        $this->tipoProd = $tipoProd;
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
    public function setFechaElab(string $fechaElab): void {
        $this->fechaElab = $fechaElab;
    }
    public function setFechaCad(string $fechaCad): void {
        $this->fechaCad = $fechaCad;
    }
    public function setWarehouse(string $warehouse): void {
        $this->warehouse = $warehouse;
    }
    public function setCostCurrency(string $costCurrency): void {
        $this->costCurrency = $costCurrency;
    }
    public function setCostPrice(float $costPrice): void {
        $this->costPrice = $costPrice;
    }
    public function setSaleCurrency(string $saleCurrency): void {
        $this->saleCurrency = $saleCurrency;
    }
    public function setSalePrice(float $salePrice): void {
        $this->salePrice = $salePrice;
    }
    public function setCodeContents(string $codeContents): void {
        $this->codeContents = $codeContents;
    }
    public function setRecetaId(int $receta_id): void {
        $this->receta_id = $receta_id; 
    }
    public function setRationsPackage(int $rations_package): void {
        $this->rations_package = $rations_package; 
    }
    public function setFileName(?string $fileName): void {
        $this->fileName = $fileName; 
    }
    public function setEstado(string $estado): void {
        $this->estado = $estado; 
    }
}
?>
