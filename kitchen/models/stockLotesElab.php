<?php

class StockLotesElab {
    private int $id;
    private int $receta_id;
    private string $lote;
    private float $cantidad;
    private int $unidades;
    private string $elaboracion;
    private string $caducidad;
    private float $coste;
    private string $tipo_unidad;
    private float $cantidad_total;

    // Constructor
    public function __construct() {
      
    }

    // Getters y Setters

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

    public function getLote(): string {
        return $this->lote;
    }

    public function setLote(string $lote): void {
        $this->lote = $lote;
    }

    public function getCantidad(): float {
        return $this->cantidad;
    }

    public function setCantidad(float $cantidad): void {
        $this->cantidad = $cantidad;
    }

    public function getUnidades(): int {
        return $this->unidades;
    }

    public function setUnidades(int $unidades): void {
        $this->unidades = $unidades;
    }

    public function getElaboracion(): string {
        return $this->elaboracion;
    }

    public function setElaboracion(string $elaboracion): void {
        $this->elaboracion = $elaboracion;
    }

    public function getCaducidad(): string {
        return $this->caducidad;
    }

    public function setCaducidad(string $caducidad): void {
        $this->caducidad = $caducidad;
    }

    public function getCoste(): float {
        return $this->coste;
    }

    public function setCoste(float $coste): void {
        $this->coste = $coste;
    }

    public function getTipoUnidad(): string {
        return $this->tipo_unidad;
    }

    public function setTipoUnidad(string $tipo_unidad): void {
        $this->tipo_unidad = $tipo_unidad;
    }

    public function getCantidadTotal(): float {
        return $this->cantidad_total;
    }

    public function setCantidadTotal(float $cantidad_total): void {
        $this->cantidad_total = $cantidad_total;
    }
}

?>
