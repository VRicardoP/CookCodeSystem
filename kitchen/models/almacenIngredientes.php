<?php

class AlmacenIngredientes {
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
    private int $ingrediente_id;
    private float $cantidad_paquete;
    private ?string $estado;

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
        int $ingrediente_id = 0,
        float $cantidad_paquete = 0,
        string $estado = "",
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
        $this->ingrediente_id = $ingrediente_id;
        $this->cantidad_paquete = $cantidad_paquete;
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
    public function getIngredienteId(): int {
        return $this->ingrediente_id;
    }
    public function getCantidadPaquete(): float {
        return $this->cantidad_paquete;
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
    public function setIngredienteId(int $ingrediente_id): void {
        $this->ingrediente_id = $ingrediente_id;
    }
    public function setCantidadPaquete(float $cantidad_paquete): void {
        $this->cantidad_paquete = $cantidad_paquete;
    }
    public function setEstado(string $estado): void {
        $this->estado = $estado;
    }
}
?>
