<?php

class Unit {
    private string $unit;

    // Constructor
    public function __construct(string $unit = "") {
        $this->unit = $unit;
    }

    // Método Getter
    public function getUnit(): string {
        return $this->unit;
    }

    // Método Setter
    public function setUnit(string $unit): void {
        $this->unit = $unit;
    }
}

?>
