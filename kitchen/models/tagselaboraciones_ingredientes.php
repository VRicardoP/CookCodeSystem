<?php

class Tagselaboraciones_ingredients {
    private int $tag_elaboracion_id;
    private string $ingrediente;
    private float $cantidad;
    private string $unidad;
    private string $alergeno;

    // Constructor
    public function __construct(int $tag_elaboracion_id = 0,string $ingrediente = "",float $cantidad = 0,string $unidad = "", string $alergeno = "") {
        $this->tag_elaboracion_id = $tag_elaboracion_id;
        $this->ingrediente = $ingrediente;
        $this->cantidad = $cantidad;
        $this->unidad = $unidad;
        $this->alergeno = $alergeno;
    }

    // Método Getter
    public function getTag_elaboracion_id(): int {
        return $this->tag_elaboracion_id;
    }

    public function getIngrediente(): int {
        return $this->ingrediente;
    }

    public function getCantidad(): int {
        return $this->cantidad;
    }
    public function getUnidad(): int {
        return $this->unidad;
    }

    public function getAlergeno(): string { // Método Getter para la propiedad alergeno
        return $this->alergeno;
    }
    // Método Setter
    public function setTag_elaboracion_id(int $tag_elaboracion_id): void {
        $this->tag_elaboracion_id = $tag_elaboracion_id;
    }

    public function setIngrediente(int $ingrediente): void {
        $this->ingrediente = $ingrediente;
    }

    public function setCantidad(int $cantidad): void {
        $this->cantidad = $cantidad;
    }
    public function setUnidad(int $unidad): void {
        $this->unidad = $unidad;
    }


    public function setAlergeno(string $alergeno): void { // Método Setter para la propiedad alergeno
        $this->alergeno = $alergeno;
    }

}

?>
