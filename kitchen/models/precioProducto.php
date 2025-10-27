<?php
class PreciosProducto {
    private int $id;
    private string $producto;
    private string $unidad;
    private float $precio;
    private ?float $merma; // Nuevo campo

    // Constructor
    public function __construct(int $id = 0, string $producto = "", string $unidad = "", float $precio = 0, float $merma = 0) {
        $this->id = $id;
        $this->producto = $producto;
        $this->unidad = $unidad;
        $this->precio = $precio;
        $this->merma = $merma; // Asignación del nuevo campo
    }

    // Getters
    public function getId(): int {
        return $this->id;
    }

    public function getProducto(): string {
        return $this->producto;
    }

    public function getUnidad(): string {
        return $this->unidad;
    }

    public function getPrecio(): float {
        return $this->precio;
    }

    public function getMerma(): float { // Getter para el nuevo campo
        return $this->merma;
    }

    // Setters
    public function setId(int $id): void {
        $this->id = $id;
    }

    public function setProducto(string $producto): void {
        $this->producto = $producto;
    }

    public function setUnidad(string $unidad): void {
        $this->unidad = $unidad;
    }

    public function setPrecio(float $precio): void {
        $this->precio = $precio;
    }

    public function setMerma(float $merma): void { // Setter para el nuevo campo
        $this->merma = $merma;
    }
}
