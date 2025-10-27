<?php

class Recetas {
    public ?int $id;
    public string $tipo;
    public string $receta;
    public string $instrucciones;
    public ?int $produce;
    public int $cantidad_producida;
    public string $tipo_cantidad;
    public ?int $num_raciones;
    public ?string $imagen;
    public ?float $peso;
    public ?int $expira_dias;
    public ?string $empaquetado;
    public ?string $localizacion;
    public string $descripcion_corta;
    public ?string $categoria;


    // Constructor
    public function __construct(
        ?int $id = 0,
        string $tipo = "",
        string $receta = "",
        string $instrucciones = "",
        ?int $produce = null,
        int $cantidad_producida = 0,
        string $tipo_cantidad = "",
        string $descripcion_corta = "",
        string $categoria = "",
    ) {
        $this->id = $id;
        $this->tipo = $tipo;
        $this->receta = $receta;
        $this->instrucciones = $instrucciones;
        $this->produce = $produce;
        $this->cantidad_producida = $cantidad_producida;
        $this->tipo_cantidad = $tipo_cantidad;
        $this->descripcion_corta = $descripcion_corta;
        $this->categoria = $categoria;
    }

    // Métodos Getter
    public function getID(): int {
        return $this->id;
    }
    public function getTipo(): string {
        return $this->tipo;
    }
    public function getReceta(): string {
        return $this->receta;
    }
    public function getInstrucciones(): string {
        return $this->instrucciones;
    }
    public function getProduce(): int {
        return $this->produce;
    }
    public function getCantidadProducida(): int {
        return $this->cantidad_producida;
    }
    public function getTipoCantidad(): string {
        return $this->tipo_cantidad;
    }
    public function getNumRaciones(): int {
        return $this->num_raciones;
    }
    public function getImagen(): string {
        return $this->imagen;
    }
    public function getPeso(): float {
        return $this->peso;
    }
    public function getCaducidad(): int {
        return $this->expira_dias;
    }
    public function getEmpaquetado(): string {
        return $this->empaquetado;
    }
    public function getLocalizacion(): string {
        return $this->localizacion;
    }
    public function getDescripcionCorta(): string {
        return $this->descripcion_corta;
    }
    public function getCategoria(): string{
        return $this->categoria;
    }

    // Métodos Setter
    public function setID(int $id): void {
        $this->id = $id;
    }
    public function setTipo(string $tipo): void {
        $this->tipo = $tipo;
    }
    public function setRecetaName(string $receta): void {
        $this->receta = $receta;
    }
    public function setInstrucciones(string $instrucciones): void {
        $this->instrucciones = $instrucciones;
    }
    public function setProduce(?int $produce): void {
        $this->produce = $produce;
    }
    public function setCantidadProducida(int $cantidad_producida): void {
        $this->cantidad_producida = $cantidad_producida;
    }
    public function setTipoCantidad(string $tipo_cantidad): void {
        $this->tipo_cantidad = $tipo_cantidad;
    }
    public function setNumRaciones(?int $num_raciones): void {
        $this->num_raciones = $num_raciones;
    }
    public function setImagen(?string $imagen): void {
        $this->imagen = $imagen;
    }
    public function setPeso(?float $peso): void {
        $this->peso = $peso;
    }
    public function setCaducidad(?int $expira_dias): void {
        $this->expira_dias = $expira_dias;
    }
    public function setEmpaquetado(?string $empaquetado): void {
        $this->empaquetado = $empaquetado;
    }
    public function setLocalizacion(?string $localizacion): void {
        $this->localizacion = $localizacion;
    }
    public function setDescripcionCorta(?string $descripcion_corta): void {
        $this->descripcion_corta = $descripcion_corta;
    }
    public function setCategoria(?string $categoria){
        $this->categoria = $categoria;
    }
}
?>
