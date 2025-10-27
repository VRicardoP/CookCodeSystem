<?php

class Autoconsumo {
    private int $id;
    private string $name;
    private float $cantidad;
    private string $fecha_consumo;
    private float $coste;

    // Constructor
    public function __construct(int $id = 0, string $name , float $cantidad, string $fecha_consumo, float $coste) {
        $this->id = $id;
        $this->name = $name;
        $this->cantidad = $cantidad;
        $this->fecha_consumo = $fecha_consumo;
        $this->coste = $coste;
    }

    // Método Getter

    public function getId(): int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    // Método Setter
    public function setName(string $name): void {
        $this->name = $name;
    }


    public function getCantidad(): float {
        return $this->cantidad;
    }

    // Método Setter
    public function setCantidad(float $cantidad): void {
        $this->cantidad = $cantidad;
    }
    public function getFechaConsumo(): string {
        return $this->fecha_consumo;
    }

    // Método Setter
    public function setFechaConsumo(string $fecha_consumo): void {
        $this->fecha_consumo = $fecha_consumo;
    }

    public function getCoste(): float {
        return $this->coste;
    }

    // Método Setter
    public function setCoste(float $coste): void {
        $this->coste = $coste;
    }
}

?>
