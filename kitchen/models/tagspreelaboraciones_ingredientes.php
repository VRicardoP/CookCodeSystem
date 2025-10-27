<?php

class TagsPreelaboraciones_ingredients {
    private int $tag_preelaboracion_id;
    private string $ingrediente;
    private float $cantidad;
    private string $unidad;


    // Constructor
    public function __construct(int $tag_preelaboracion_id = 0,string $ingrediente = "",float $cantidad = 0,string $unidad = "") {
        $this->tag_preelaboracion_id = $tag_preelaboracion_id;
        $this->ingrediente = $ingrediente;
       
        $this->cantidad = $cantidad;
        $this->unidad = $unidad;

    }

    // Método Getter
    public function getTag_preelaboracion_id(): int {
        return $this->tag_preelaboracion_id;
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


    // Método Setter
    public function setTag_preelaboracion_id(int $tag_preelaboracion_id): void {
        $this->tag_preelaboracion_id = $tag_preelaboracion_id;
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

}

?>
