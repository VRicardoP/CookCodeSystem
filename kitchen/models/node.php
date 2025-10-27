<?php

class Node {
    private int $id;
    private ?string $method;
    private ?string $noun;
    private ?string $unit;
    private ?float $quantity;
    private ?float $standardDeviation;
    private ?int $frequency;

    // Constructor
    public function __construct(
        int $id = 0,
        ?string $method = null,
        ?string $noun = null,
        ?string $unit = null,
        ?float $quantity = null,
        ?float $standardDeviation = null,
        ?int $frequency = null
    ) {
        $this->id = $id;
        $this->method = $method;
        $this->noun = $noun;
        $this->unit = $unit;
        $this->quantity = $quantity;
        $this->standardDeviation = $standardDeviation;
        $this->frequency = $frequency;
    }

    // Métodos Getter
    public function getId(): int {
        return $this->id;
    }
    public function getMethod(): ?string {
        return $this->method;
    }
    public function getNoun(): ?string {
        return $this->noun;
    }
    public function getUnit(): ?string {
        return $this->unit;
    }
    public function getQuantity(): ?float {
        return $this->quantity;
    }
    public function getStandardDeviation(): ?float {
        return $this->standardDeviation;
    }
    public function getFrequency(): ?int {
        return $this->frequency;
    }

    // Métodos Setter
    public function setId(int $id): void {
        $this->id = $id;
    }
    public function setMethod(?string $method): void {
        $this->method = $method;
    }
    public function setNoun(?string $noun): void {
        $this->noun = $noun;
    }
    public function setUnit(?string $unit): void {
        $this->unit = $unit;
    }
    public function setQuantity(?float $quantity): void {
        $this->quantity = $quantity;
    }
    public function setStandardDeviation(?float $standardDeviation): void {
        $this->standardDeviation = $standardDeviation;
    }
    public function setFrequency(?int $frequency): void {
        $this->frequency = $frequency;
    }
}
?>
