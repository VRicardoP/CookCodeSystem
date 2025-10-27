<?php

declare(strict_types=1);

class ElaboracionIngredient
{
    private ?int $id;
    private int $elaboracion_id;
    private string $ingrediente;
    private float $cantidad;
    private string $unidad;
    private string $alergeno;
    private ?string $fecha_creacion;
    private ?string $fecha_modificacion;

    public function __construct(
        ?int $id = 0,
        int $elaboracion_id = 0,
        string $ingrediente = "",
        float $cantidad =0,
        string $unidad ="",
        string $alergeno ="",
        ?string $fecha_creacion = null,
        ?string $fecha_modificacion = null
    ) {
        $this->id = $id;
        $this->elaboracion_id = $elaboracion_id;
        $this->ingrediente = $ingrediente;
        $this->cantidad = $cantidad;
        $this->unidad = $unidad;
        $this->alergeno = $alergeno;
        $this->fecha_creacion = $fecha_creacion;
        $this->fecha_modificacion = $fecha_modificacion;
    }

    // Getters y setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getElaboracionId(): int
    {
        return $this->elaboracion_id;
    }

    public function getIngrediente(): string
    {
        return $this->ingrediente;
    }

    public function getCantidad(): float
    {
        return $this->cantidad;
    }

    public function getUnidad(): string
    {
        return $this->unidad;
    }

    public function getAlergeno(): string
    {
        return $this->alergeno;
    }

    public function getFechaCreacion(): ?string
    {
        return $this->fecha_creacion;
    }

    public function getFechaModificacion(): ?string
    {
        return $this->fecha_modificacion;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function setElaboracionId(int $elaboracion_id): void
    {
        $this->elaboracion_id = $elaboracion_id;
    }

    public function setIngrediente(string $ingrediente): void
    {
        $this->ingrediente = $ingrediente;
    }

    public function setCantidad(float $cantidad): void
    {
        $this->cantidad = $cantidad;
    }

    public function setUnidad(string $unidad): void
    {
        $this->unidad = $unidad;
    }

    public function setAlergeno(string $alergeno): void
    {
        $this->alergeno = $alergeno;
    }

    public function setFechaCreacion(?string $fecha_creacion): void
    {
        $this->fecha_creacion = $fecha_creacion;
    }

    public function setFechaModificacion(?string $fecha_modificacion): void
    {
        $this->fecha_modificacion = $fecha_modificacion;
    }
}
