<?php

class Ingredient {
    private int $ID;
    private string $fName;
    private float $merma;
    private string $packaging;
    private int $cantidad;
    private string $unidad;
    private ?string $fechaElab;
    private ?string $fechaCad;
    private string $warehouse;
    private ?string $costCurrency;
    private float $costPrice;
    private ?string $saleCurrency;
    private ?int $salePrice;
    private ?string $codeContents;
    private ?string $image;
    private ?int $expira_dias;
    private ?float $peso;
    private ?string $atr_name_tienda;
    private ?string $atr_valores_tienda;
    private ?string $descripcion_corta;
    private ?string $clasificacion_ing;

    // Constructor
    public function __construct(
        int $ID = 0,
        string $fName = "",
        float $merma = 0.0,
        string $packaging = "",
        int $cantidad = 0,
        string $unidad = "",
        string $fechaElab = "",
        string $fechaCad = "",
        string $warehouse = "",
        string $costCurrency = "",
        float $costPrice = 0,
        string $saleCurrency = "",
        int $salePrice = 0,
        string $codeContents = "",
        ?string $image = null,
        ?int $expira_dias = 0,
        ?string $descripcion_corta = null,
        ?string $clasificacion_ing = null
    ) {
        $this->ID = $ID;
        $this->fName = $fName;
        $this->merma = $merma;
        $this->packaging = $packaging;
        $this->cantidad = $cantidad;
        $this->unidad = $unidad;
        $this->fechaElab = $fechaElab;
        $this->fechaCad = $fechaCad;
        $this->warehouse = $warehouse;
        $this->costCurrency = $costCurrency;
        $this->costPrice = $costPrice;
        $this->saleCurrency = $saleCurrency;
        $this->salePrice = $salePrice;
        $this->codeContents = $codeContents;
        $this->image = $image;
        $this->expira_dias = $expira_dias;
        $this->descripcion_corta = $descripcion_corta;
        $this->clasificacion_ing = $clasificacion_ing;
      
       

    }

    // Métodos Getter
    public function getID(): int {
        return $this->ID;
    }
    public function getFName(): string {
        return $this->fName;
    }
    public function getMerma(): float {
        return $this->merma;
    }
    public function getPackaging(): string {
        return $this->packaging;
    }
    public function getCantidad(): int {
        return $this->cantidad;
    }
    public function getUnidad(): string {
        return $this->unidad;
    }
    public function getFechaElab(): ?string {
        return $this->fechaElab;
    }
    public function getFechaCad(): ?string {
        return $this->fechaCad;
    }
    public function getWarehouse(): string {
        return $this->warehouse;
    }
    public function getCostCurrency(): ?string {
        return $this->costCurrency;
    }
    public function getCostPrice(): float {
        return $this->costPrice;
    }
    public function getSaleCurrency(): ?string {
        return $this->saleCurrency;
    }
    public function getSalePrice(): ?int {
        return $this->salePrice;
    }
    public function getCodeContents(): ?string {
        return $this->codeContents;
    }
    public function getImage(): ?string {
        return $this->image;
    }
    public function getCaducidad(): ?int {
        return $this->expira_dias;
    }
    public function getPeso(): ?float {
        return $this->peso;
    }
    public function getAtrNameTienda(): ?string {
        return $this->atr_name_tienda;
    }
    public function getAtrValoresTienda(): ?string {
        return $this->atr_valores_tienda;
    }
    public function getDescripcionCorta(): ?string {
        return $this->descripcion_corta;
    }
    public function getClasificacionIng(): ?string {
        return $this->clasificacion_ing;
    }

   

    // Métodos Setter
    public function setID(int $ID): void {
        $this->ID = $ID;
    }
    public function setFName(string $fName): void {
        $this->fName = $fName;
    }
    public function setMerma(float $merma): void {
        $this->merma = $merma;
    }
    public function setPackaging(string $packaging): void {
        $this->packaging = $packaging;
    }
    public function setCantidad(int $cantidad): void {
        $this->cantidad = $cantidad;
    }
    public function setUnidad(string $unidad): void {
        $this->unidad = $unidad;
    }
    public function setFechaElab(?string $fechaElab): void {
        $this->fechaElab = $fechaElab;
    }
    public function setFechaCad(?string $fechaCad): void {
        $this->fechaCad = $fechaCad;
    }
    public function setWarehouse(string $warehouse): void {
        $this->warehouse = $warehouse;
    }
    public function setCostCurrency(?string $costCurrency): void {
        $this->costCurrency = $costCurrency;
    }
    public function setCostPrice(float $costPrice): void {
        $this->costPrice = $costPrice;
    }
    public function setSaleCurrency(?string $saleCurrency): void {
        $this->saleCurrency = $saleCurrency;
    }
    public function setSalePrice(?int $salePrice): void {
        $this->salePrice = $salePrice;
    }
    public function setCodeContents(?string $codeContents): void {
        $this->codeContents = $codeContents;
    }
    public function setImage(?string $image): void {
        $this->image = $image;
    }
    public function setCaducidad(?int $expira_dias): void {
        $this->expira_dias = $expira_dias;
    }
    public function setPeso(?float $peso): void {
        $this->peso = $peso;
    }
    public function setAtrNameTienda(?string $atr_name_tienda): void {
        $this->atr_name_tienda = $atr_name_tienda;
    }
    public function setAtrValoresTienda(?string $atr_valores_tienda): void {
        $this->atr_valores_tienda = $atr_valores_tienda;
    }
    public function setDescripcionCorta(?string $descripcion_corta): void {
        $this->descripcion_corta = $descripcion_corta;
    }
    public function setClasificacionIng(?string $clasificacion_ing): void {
        $this->clasificacion_ing = $clasificacion_ing;
    }
 
}

?>
