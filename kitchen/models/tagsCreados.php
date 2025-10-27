<?php

class TagCreado {
    private int $IDTag;
    private string $tempDir;
    private string $email;
    private string $filename;
    private string $fName;
    private string $packaging;
    private int $productamount;
    private string $fechaElab;
    private string $fechaCad;
    private string $warehouse;
    private string $costCurrency;
    private int $costPrice;
    private string $saleCurrency;
    private int $salePrice;
    private string $codeContents;

    // Constructor
    public function __construct(
        int $IDTag = 0,
        string $tempDir = "",
        string $email = "",
        string $filename = "",
        string $fName = "",
        string $packaging = "",
        int $productamount = 0,
        string $fechaElab = "",
        string $fechaCad = "",
        string $warehouse = "",
        string $costCurrency = "",
        int $costPrice = 0,
        string $saleCurrency = "",
        int $salePrice = 0,
        string $codeContents = ""
    ) {
        $this->IDTag = $IDTag;
        $this->tempDir = $tempDir;
        $this->email = $email;
        $this->filename = $filename;
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
    }

    // Métodos Getter
    public function getIDTag(): int {
        return $this->IDTag;
    }
    public function getTempDir(): string {
        return $this->tempDir;
    }
    public function getEmail(): string {
        return $this->email;
    }
    public function getFilename(): string {
        return $this->filename;
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

    // Métodos Setter
    public function setIDTag(int $IDTag): void {
        $this->IDTag = $IDTag;
    }
    public function setTempDir(string $tempDir): void {
        $this->tempDir = $tempDir;
    }
    public function setEmail(string $email): void {
        $this->email = $email;
    }
    public function setFilename(string $filename): void {
        $this->filename = $filename;
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
}
?>
